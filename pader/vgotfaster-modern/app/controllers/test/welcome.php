<?php
/*
	����һ��ע��
*/
class WelcomeController extends Controller {

	function __construct()
	{
		parent::Controller();
	}

	function index()
	{
		echo '<p>Welcome to '.anchor('test','test').' Controler</p>';
	}
	
	function GUID()
	{
		$this->load->helper('misc');
		$this->load->database();
		echo getGUID();
	}

	function testredirect()
	{
		echo anchor('test/welcome','Go Index');

	}

	function input()
	{
		echo $this->input->get('aa');
		echo $this->input->ipAddress();
	}

	function ftp()
	{
		$this->load->library('ftp');

		$this->ftp->connect('content.52pk.cc','test','test');

		$this->ftp->chdir('ajaxpost');
		$dir = $this->ftp->dir('chinese');

		echo $this->ftp->current();

		$this->ftp->close();

		if (is_array($dir)) {
			echo "<table border='1'>\r\n";
			echo "<tr><th>Ŀ¼</th><th>����</th><th>��С</th><th>�޸�</th><th>����Ȩ</th><th>��</th><th>����</th></tr>\r\n";

			foreach ($dir as $row) {
				echo "<tr><td>{$row['isdir']}</td><td>{$row['filename']}</td><td>{$row['filesize']}</td><td>{$row['filemtime']}</td><td>{$row['user']}</td><td>{$row['group']}</td><td>{$row['permission']}</td></tr>\r\n";
			}

			echo '</table>';
		} else {
			exit('Ŀ¼δ�ҵ���');
		}
	}

}
