<?php
//版权所有(C) 2014 www.ilinei.com

namespace ilinei;

class smtp{
	private $smtp_port;
	private $time_out;
	private $host_name;
	private $log_file;
	private $relay_host;
	public $debug;
	public $auth;
	public $user;
	public $pass;
	public $sender;
	private $sock;
	
	public function __construct($smtp_host = '', $smtp_port = 25, $auth = true, $user, $pass, $sender){
		$this->debug = false;
		$this->smtp_port = $smtp_port;
		$this->relay_host = $smtp_host;
		$this->time_out = 30;
		$this->auth = $auth;
		$this->user = $user;
		$this->pass = $pass;
		$this->sender = $sender;
		$this->host_name = "localhost";
		$this->log_file = ROOTPATH.'/_cache/log_smtp.txt';
		$this->logs = "";
		$this->sock = false;
	}
	
	public function sendmail($to, $from, $subject = "", $body = "", $mailtype, $cc = "", $bcc = "", $additional_headers = ""){
		$sent = true;
		
		$mail_from = $this->get_address($this->strip_comment($from));
		$body = preg_replace("/(^|(\r\n))(\.)/", "\1.\3", $body);
		$header = "MIME-Version:1.0\r\n";
		
		if($mailtype == 'HTML') $header .= "Content-Type:text/html\r\n";
		
		$header .= "To: {$to}\r\n";
		
		if ($cc != '') $header .= "Cc: {$cc}\r\n";
		
		$header .= "From: {$from}\r\n";
		$header .= "Subject: {$subject}\r\n";
		$header .= $additional_headers;
		$header .= "Date: ".date("r")."\r\n";
		$header .= "X-Mailer: 163.com (PHP/".phpversion().")\r\n";
		
		list($msec, $sec) = explode(" ", microtime());
		
		$header .= "Message-ID: <".date("YmdHis", $sec).".".($msec * 1000000).".".$mail_from.">\r\n";
		
		$TO = explode(",", $this->strip_comment($to));
		
		if ($cc != '') $TO = array_merge($TO, explode(",", $this->strip_comment($cc)));
		if ($bcc != '') $TO = array_merge($TO, explode(",", $this->strip_comment($bcc)));
		
		foreach ($TO as $rcpt_to) {
			$rcpt_to = $this->get_address($rcpt_to);
			
			if (!$this->smtp_sockopen($rcpt_to)) {
				$this->log_write("Error: Cannot send email to ".$rcpt_to."\n");
				$sent = false;
				continue;
			}
			
			if ($this->smtp_send($this->host_name, $mail_from, $rcpt_to, $header, $body)) {
				$this->log_write("E-mail has been sent to <".$rcpt_to.">\n");
			} else {
				$this->log_write("Error: Cannot send email to <".$rcpt_to.">\n");
				$sent = false;
			}
			
			fclose($this->sock);
			
			$this->log_write("Disconnected from remote host\n");
		}
		
		return $sent;
	}
	
	private function smtp_send($helo, $from, $to, $header, $body = ""){
		if (!$this->smtp_putcmd("HELO", $helo)) {
			return $this->smtp_error("sending HELO command");
		}
		
		if($this->auth){
			if (!$this->smtp_putcmd("AUTH LOGIN", base64_encode($this->user))) {
				return $this->smtp_error("sending AUTH command");
			}
			
			if (!$this->smtp_putcmd("", base64_encode($this->pass))) {
				return $this->smtp_error("sending AUTH command");
			}
		}
		
		if (!$this->smtp_putcmd("MAIL", "FROM:<".$this->sender.">")) {
			return $this->smtp_error("sending MAIL FROM command");
		}
		
		if (!$this->smtp_putcmd("RCPT", "TO:<".$to.">")) {
			return $this->smtp_error("sending RCPT TO command");
		}
		
		if (!$this->smtp_putcmd("DATA")) {
			return $this->smtp_error("sending DATA command");
		}
		
		if (!$this->smtp_message($header, $body)) {
			return $this->smtp_error("sending message");
		}
		
		if (!$this->smtp_eom()) {
			return $this->smtp_error("sending <CR><LF>.<CR><LF> [EOM]");
		}
		
		if (!$this->smtp_putcmd("QUIT")) {
			return $this->smtp_error("sending QUIT command");
		}
		
		return true;
	}
	
	private function smtp_sockopen($address){
		if ($this->relay_host == "") {
			return $this->smtp_sockopen_mx($address);
		} else {
			return $this->smtp_sockopen_relay();
		}
	}
	
	private function smtp_sockopen_relay(){
		$this->log_write("Trying to ".$this->relay_host.":".$this->smtp_port."\n");
		$this->sock = @fsockopen($this->relay_host, $this->smtp_port, $errno, $errstr, $this->time_out);
		if (!($this->sock && $this->smtp_ok())) {
			$this->log_write("Error: Cannot connenct to relay host ".$this->relay_host."\n");
			$this->log_write("Error: ".$errstr." (".$errno.")\n");
			return false;
		}
		
		$this->log_write("Connected to relay host ".$this->relay_host."\n");
		
		return true;
	}
	
	private function smtp_sockopen_mx($address){
		$domain = preg_replace("^.+@([^@]+)$", "\1", $address);
		if (!@getmxrr($domain, $MXHOSTS)) {
			$this->log_write("Error: Cannot resolve MX \"".$domain."\"\n");
			return false;
		}
		
		foreach ($MXHOSTS as $host) {
			$this->log_write("Trying to ".$host.":".$this->smtp_port."\n");
			$this->sock = @fsockopen($host, $this->smtp_port, $errno, $errstr, $this->time_out);
			if (!($this->sock && $this->smtp_ok())) {
				$this->log_write("Warning: Cannot connect to mx host ".$host."\n");
				$this->log_write("Error: ".$errstr." (".$errno.")\n");
				continue;
			}
			
			$this->log_write("Connected to mx host ".$host."\n");
			return true;
		}
		
		$this->log_write("Error: Cannot connect to any mx hosts (".implode(", ", $MXHOSTS).")\n");
		
		return false;
	}
	
	private function smtp_message($header, $body){
		fputs($this->sock, $header."\r\n".$body);
		$this->smtp_debug("> ".str_replace("\r\n", "\n"."> ", $header."\n> ".$body."\n> "));
		
		return true;
	}
	
	private function smtp_eom(){
		fputs($this->sock, "\r\n.\r\n");
		$this->smtp_debug(". [EOM]\n");
		return $this->smtp_ok();
	}
	
	private function smtp_ok(){
		$response = str_replace("\r\n", "", fgets($this->sock, 512));
		$this->smtp_debug($response."\n");
		if (!preg_match("/^[23]/", $response)) {
			fputs($this->sock, "QUIT\r\n");
			fgets($this->sock, 512);
			$this->log_write("Error: Remote host returned \"".$response."\"\n");
			return FALSE;
		}
		
		return true;
	}
	
	private function smtp_putcmd($cmd, $arg = ""){
		if ($arg != "") {
			if($cmd=="") $cmd = $arg;
			else $cmd = $cmd." ".$arg;
		}
		
		fputs($this->sock, $cmd."\r\n");
		$this->smtp_debug("> ".$cmd."\n");
		
		return $this->smtp_ok();
	}
	
	private function smtp_error($string){
		$this->log_write("Error: Error occurred while ".$string.".\n");
		
		return false;
	}

	private function log_write($message){
		$this->logs .= $message;
		$this->smtp_debug($message);
		
		if ($this->log_file == '') return true;
		
		$message = date("M d H:i:s ").get_current_user()."[".getmypid()."]: ".$message;
		
		$fp = fopen($this->log_file, 'a+');
		fwrite($fp, $message);
		fclose($fp);
		
		return true;
	}
	
	private function strip_comment($address){
		$comment = "/\([^()]*\)/";
		while (preg_match($comment, $address)) {
			$address = preg_replace($comment, "", $address);
		}
		
		return $address;
	}
	
	private function get_address($address){
		$address = preg_replace("/([ \t\r\n])+/", '', $address);
		$address = preg_replace("/^.*<(.+)>.*$/", '\1', $address);
		return $address;
	}
	
	private function smtp_debug($message){
		if ($this->debug) {
			echo $message;
		}
	}
}
?>