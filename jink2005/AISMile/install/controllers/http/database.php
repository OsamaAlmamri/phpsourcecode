<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

/**
 * Step 3 : configure database and email connection
 */
class InstallControllerHttpDatabase extends InstallControllerHttp
{
	/**
	 * @var InstallModelDatabase
	 */
	public $model_database;

	/**
	 * @var InstallModelMail
	 */
	public $model_mail;

	public function init()
	{
		require_once _PS_INSTALL_MODELS_PATH_.'database.php';
		$this->model_database = new InstallModelDatabase();
	}

	/**
	 * @see InstallAbstractModel::processNextStep()
	 */
	public function processNextStep()
	{
		// Save database config
		$this->session->database_server = trim(Tools::getValue('dbServer'));
		$this->session->database_name = trim(Tools::getValue('dbName'));
		$this->session->database_login = trim(Tools::getValue('dbLogin'));
		$this->session->database_password = trim(Tools::getValue('dbPassword'));
		$this->session->database_prefix = trim(Tools::getValue('db_prefix'));
		$this->session->database_engine = Tools::getValue('dbEngine');
		$this->session->database_clear = Tools::getValue('database_clear');

		// Save email config
		$this->session->use_smtp = (bool)Tools::getValue('smtpChecked');
		$this->session->smtp_server = trim(Tools::getValue('smtpSrv'));
		$this->session->smtp_encryption = Tools::getValue('smtpEnc');
		$this->session->smtp_port = (int)Tools::getValue('smtpPort');
		$this->session->smtp_login = trim(Tools::getValue('smtpLogin'));
		$this->session->smtp_password = trim(Tools::getValue('smtpPassword'));
	}

	/**
	 * Database configuration must be valid to validate this step
	 *
	 * @see InstallAbstractModel::validate()
	 */
	public function validate()
	{
		$this->errors = $this->model_database->testDatabaseSettings(
			$this->session->database_server,
			$this->session->database_name,
			$this->session->database_login,
			$this->session->database_password,
			$this->session->database_prefix,
			$this->session->database_engine,

			// We do not want to validate table prefix if we are already in install process
			($this->session->step == 'process') ? true : $this->session->database_clear
		);

		return count($this->errors) ? false : true;
	}

	public function process()
	{
		if (Tools::getValue('checkDb'))
			$this->processCheckDb();
		else if (Tools::getValue('sendMail'))
			$this->processSendMail();
	}

	/**
	 * Check if a connection to database is possible with these data
	 */
	public function processCheckDb()
	{
		$server = Tools::getValue('dbServer');
		$database = Tools::getValue('dbName');
		$login = Tools::getValue('dbLogin');
		$password = Tools::getValue('dbPassword');
		$prefix = Tools::getValue('db_prefix');
		$engine = Tools::getValue('dbEngine');
		$clear = Tools::getValue('clear');

		$errors = $this->model_database->testDatabaseSettings($server, $database, $login, $password, $prefix, $engine, $clear);

		$this->ajaxJsonAnswer(
			(count($errors)) ? false : true,
			(count($errors)) ? implode('<br />', $errors) : $this->l('Database is connected')
		);
	}

	/**
	 * Send a test email
	 */
	public function processSendMail()
	{
		$smtp_checked = (Tools::getValue('smtpChecked') == 'true');
		$server = Tools::getValue('smtpSrv');
		$encryption = Tools::getValue('smtpEnc');
		$port = Tools::getValue('smtpPort');
		$login = Tools::getValue('smtpLogin');
		$password = Tools::getValue('smtpPassword');
		$email = Tools::getValue('testEmail');

		require_once _PS_INSTALL_MODELS_PATH_.'mail.php';
		$this->model_mail = new InstallModelMail($smtp_checked, $server, $login, $password, $port, $encryption, $email);
		$result = $this->model_mail->send(
			$this->l('Test message from MileBiz'),
			$this->l('This is a test message, your server is now available to send email')
		);

		$this->ajaxJsonAnswer(
			$result === false,
			($result === false) ? $this->l('A test e-mail has been sent to %s', $email) : $this->l('An error occurred while sending email, please verify your parameters')
		);
	}

	/**
	 * @see InstallAbstractModel::display()
	 */
	public function display()
	{
		if (!$this->session->database_server)
		{
			if (file_exists(_PS_ROOT_DIR_.'/config/settings.inc.php'))
			{
				@include_once _PS_ROOT_DIR_.'/config/settings.inc.php';
				$this->database_server = _DB_SERVER_;
				$this->database_name = _DB_NAME_;
				$this->database_login = _DB_USER_;
				$this->database_password = _DB_PASSWD_;
				$this->database_engine = _MYSQL_ENGINE_;
				$this->database_prefix = _DB_PREFIX_;
			}
			else
			{
				$this->database_server = 'localhost';
				$this->database_name = 'milebiz';
				$this->database_login = 'root';
				$this->database_password = '';
				$this->database_engine = 'InnoDB';
				$this->database_prefix = 'mb_';
			}

			$this->database_clear = true;
			$this->use_smtp = false;
			$this->smtp_server = 'smtp.';
			$this->smtp_encryption = 'off';
			$this->smtp_port = 25;
			$this->smtp_login = '';
			$this->smtp_password = '';
		}
		else
		{
			$this->database_server = $this->session->database_server;
			$this->database_name = $this->session->database_name;
			$this->database_login = $this->session->database_login;
			$this->database_password = $this->session->database_password;
			$this->database_engine = $this->session->database_engine;
			$this->database_prefix = $this->session->database_prefix;
			$this->database_clear = $this->session->database_clear;

			$this->use_smtp = $this->session->use_smtp;
			$this->smtp_server = $this->session->smtp_server;
			$this->smtp_encryption = $this->session->smtp_encryption;
			$this->smtp_port = $this->session->smtp_port;
			$this->smtp_login = $this->session->smtp_login;
			$this->smtp_password = $this->session->smtp_password;
		}

		$this->displayTemplate('database');
	}
}

