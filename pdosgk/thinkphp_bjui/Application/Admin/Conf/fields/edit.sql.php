<?php

$defaultvalue = isset($_POST['setting']['defaultvalue']) ? $_POST['setting']['defaultvalue'] : '';
$minnumber = isset($_POST['setting']['minnumber']) ? $_POST['setting']['minnumber'] : 1;
$decimaldigits = isset($_POST['setting']['decimaldigits']) ? $_POST['setting']['decimaldigits'] : '';

switch($field_type) {
	case 'varchar':
		if(!$maxlength) $maxlength = 255;
		$maxlength = min($maxlength, 255);
		$fieldtype = $issystem ? 'CHAR' : 'VARCHAR';
		$sql = "ALTER TABLE `$tablename` CHANGE `$oldfield` `$field` $fieldtype( $maxlength ) NOT NULL DEFAULT '$defaultvalue'";
		M()->execute($sql);
	break;

	case 'tinyint':
		$minnumber = intval($minnumber);
		$defaultvalue = intval($defaultvalue);
		M()->execute("ALTER TABLE `$tablename` CHANGE `$oldfield` `$field` TINYINT ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue'");
	break;

	case 'number':
		$minnumber = intval($minnumber);
		$defaultvalue = $decimaldigits == 0 ? intval($defaultvalue) : floatval($defaultvalue);
		$sql = "ALTER TABLE `$tablename` CHANGE `$oldfield` `$field` ".($decimaldigits == 0 ? 'INT' : 'FLOAT')." ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue'";
		M()->execute($sql);
	break;

	case 'smallint':
		$minnumber = intval($minnumber);
		$defaultvalue = intval($defaultvalue);
		M()->execute("ALTER TABLE `$tablename` CHANGE `$oldfield` `$field` SMALLINT ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue'");
	break;

	case 'mediumint':
		$minnumber = intval($minnumber);
		$defaultvalue = intval($defaultvalue);
		M()->execute("ALTER TABLE `$tablename` CHANGE `$oldfield` `$field` MEDIUMINT ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue'");
	break;


	case 'int':
		$minnumber = intval($minnumber);
		$defaultvalue = intval($defaultvalue);
		M()->execute("ALTER TABLE `$tablename` CHANGE `$oldfield` `$field` INT ".($minnumber >= 0 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$defaultvalue'");
	break;

	case 'mediumtext':
		M()->execute("ALTER TABLE `$tablename` CHANGE `$oldfield` `$field` MEDIUMTEXT NOT NULL");
	break;
	
	case 'text':
		M()->execute("ALTER TABLE `$tablename` CHANGE `$oldfield` `$field` TEXT NOT NULL");
	break;

	case 'date':
		M()->execute("ALTER TABLE `$tablename` CHANGE `$oldfield` `$field` DATE NULL");
	break;
	
	case 'datetime':
		M()->execute("ALTER TABLE `$tablename` CHANGE `$oldfield` `$field` DATETIME NULL");
	break;
	
	case 'timestamp':
		M()->execute("ALTER TABLE `$tablename` CHANGE `$oldfield` `$field` TIMESTAMP NOT NULL");
	break;
	//特殊自定义字段
	case 'pages':
		
	break;
	case 'readpoint':
		$defaultvalue = intval($defaultvalue);
		M()->execute("ALTER TABLE `$tablename` CHANGE `$oldfield` `readpoint` smallint(5) unsigned NOT NULL default '$defaultvalue'");
	break;

}
?>