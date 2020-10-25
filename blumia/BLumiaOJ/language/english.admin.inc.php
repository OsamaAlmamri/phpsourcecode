<?php
	/************* Off Canvas **************/
	const LA_BKSTAGE_ADMIN		= "Admin";
	const LA_PROB_EDITOR		= "Problem Editor";
	const LA_PROB_LIST			= "Problem List";
	const LA_PROB_ADD			= "Add Problem";
	const LA_PROB_MAN			= "Problem Management";
	const LA_DATA_MAN			= "Test Data Management";
	const LA_CONT_EDITOR		= "Contest Editor";
	const LA_CONT_MAN			= "Contest Management";
	const LA_CONT_ADD			= "Add Contest";
	const LA_CONT_LIST			= "Contest List";
	const LA_NEWS_MAN			= "News Management";
	const LA_CAST_MAN			= "Broadcast Management";
	const LA_RESET_PSW			= "Reset Password";
	const LA_ACCOUNT_GEN		= "Account Generator";
	const LA_PAGE_MODIFIER		= "Page Modifier";
	const LA_USER_MGR			= "User Manager";
	const LA_PRIVILEGE_MAN		= "Privilege Management";
	const LA_SUPER_USER			= "Super User";
	const LA_EXIT_ADMIN			= "Back to OJ";
	/************* Index **************/
	const LA_WELCOME			= "Welcome";
	const LA_INDEX_LEAD			= "To start management, please <b>click a link in the side-menu</b> on the left side of this page.";
	const LA_INDEX_MORE			= "If you are using mobile device (Pad, Smart Phone, etc.) or small screen device, you should click the button on the left-top side of the page. <br/>Following is a table about OJ properties, if you want update some of them, edit <code>config.php</code> in this OJ's <code>include</code> folder.";
	const LA_PROPERTY			= "Property";
	const LA_STATUS				= "Status";
	const LA_YES				= "Yes";
	const LA_NO					= "No";
	const LA_DEFAULT_CFG		= "Using default sample configre file";
	const LA_DEFAULT_CFG_HELP	= "We recommand you copy <code>config.sample.php</code> as <code>config.php</code> in <code>include</code> folder and modify it to configure this OJ.";
	const LA_MAGIC_QUOTE_WARN	= "<code>magic_quotes_gpc</code> is now <b>On</b>, that will cause problem when code submit processing, please trun it off by config your <code>php.ini</code> and restart your http server service, or upgrade your PHP package. (<a href='https://secure.php.net/manual/en/security.magicquotes.disabling.php'>See how to fix it</a>)";
	const LA_MBSTRING_WARN		= "PHP extension <code>mbstring</code> not installed or enabled, please install and enable it in <code>php.ini</code>. (<a href='https://secure.php.net/manual/en/mbstring.installation.php'>See how to fix it</a>)";
	const LA_XML_DOM_WARN		= "PHP extension <code>xml</code> is not avaliabled, please install and enable it to fix the problem. (<a href='https://secure.php.net/manual/en/dom.setup.php'>See how to fix it</a>)";
	const LA_IMG_GD_WARN		= "PHP extension <code>gd</code> is not installed or enabled, it's recommended to install and enable it in <code>php.ini</code> to fix the problem. (<a href='https://secure.php.net/manual/en/image.installation.php'>See how to fix it</a>)";
	const LA_HACKER_ROCKS		= "You hacked into the server! Congratulations! You rocks!";
	const LA_SHOW_WA_INFO		= "Show solution Wrong Answer detail";
	const LA_ENABLED_LANG		= "Allowed languages by default";
	const LA_CODE_SUBMIT_LIMIT	= "Code submit frequence limit";
	const LA_DO_LOCK_RANKLIST	= "Lock ranklist when contest near end";
	const LA_LOCK_RANKLIST_PCT	= "When to lock ranklist";
	/************* Contest Management Page **************/
	const LA_CONTLIST_HEAD		= "You can start editing contest data here.";
	const LA_MORE_OPTIONS		= "More Options";
	const LA_U_ARE_EDITING		= "You are editing";
	const LA_PERMISSION_N_LANG	= "Permission and Language";
	const LA_PROBLIST_HELP		= "Enter Problem ID Here, seperate with a comma punctuation. e.g. 1000,1001";
	/************* Test Case Editor **************/
	const LA_EDIT_DATA			= "Edit Data";
	const LA_TCE_LEAD			= "Edit problem's test data  here.<br/>If you want to edit a problem, please navigate to 'Problem List' page.";
	const LA_DELETE_DATA		= "Delete Test Data";
	const LA_DELETE_WARNING		= "Are you sure you want to delete";
	/************* News Management Page **************/
	const LA_ADD_NEWS			= "Add News";
	const LA_EDIT_BROADCAST		= "Edit Broadcast";
	const LA_NEWS_HEAD			= "You can manage all news and broadcast here.";
	/************* Problem Management Page **************/
	const LA_PROB_MAN_HEAD		= "You can start manage problems and do other operations about problem.<br/>To edit a problem, please go to `Problem List` page.";
	const LA_ADD_A_PROBLEM		= "Add a problem to problem set";
	const LA_VIEW_PROB_LIST		= "View list of all problems";
	const LA_ADD_TEST_LATER_HINT = "You can only add test cases after you add the problem.";
	const LA_EDIT_TESTCASE_HERE = "Manage the test case of the problem at: ";
?>