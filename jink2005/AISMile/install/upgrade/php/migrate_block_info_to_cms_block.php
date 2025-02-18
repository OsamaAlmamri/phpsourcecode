<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

function migrate_block_info_to_cms_block()
{
	$res = true;

	$languages = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'lang`');
	//get ids cms of block information
	$id_blockinfos = Db::getInstance()->getValue('SELECT id_module FROM  `'._DB_PREFIX_.'module` WHERE name = \'blockinfos\'');
	//get ids cms of block information
	$ids_cms = Db::getInstance()->executeS('SELECT id_cms FROM  `'._DB_PREFIX_.'block_cms` WHERE `id_block` = '.(int)$id_blockinfos);
	//check if block info is installed and active
	if (is_array($ids_cms))
	{
		//install module blockcms
		// Module::getInstanceByName('blockcms')->install()
		// 1) from module
		$ps_lang_default = Db::getInstance()->getValue('SELECT value 
			FROM `'._DB_PREFIX_.'configuration`
			WHERE name="PS_LANG_DEFAULT"');
		// 2) parent::install()
		$result = Db::getInstance()->insert('module',
			array('name' => 'blockcms', 'active' => 1));
		$id_module = Db::getInstance()->insert_Id();
		// 3) hooks
		$hooks = array('leftColumn', 'rightColumn', 'footer', 'header');
		foreach($hooks as $hook_name)
		{
			// do not pSql hook_name 
			$row = Db::getInstance()->getRow('SELECT h.id_hook, '.$id_module.' as id_module, MAX(hm.position)+1 as position
				FROM  `'._DB_PREFIX_.'hook_module` hm
				LEFT JOIN `'._DB_PREFIX_.'hook` h on hm.id_hook=h.id_hook
				WHERE h.name = "'.$hook_name.'" group by id_hook');
			$res &= Db::getInstance()->insert('hook_module', $row);
		}

		// module install
		$res &= Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'cms_block`(
		`id_cms_block` int(10) unsigned NOT NULL auto_increment,
		`id_cms_category` int(10) unsigned NOT NULL,
		`location` tinyint(1) unsigned NOT NULL,
		`position` int(10) unsigned NOT NULL default \'0\',
		`display_store` tinyint(1) unsigned NOT NULL default \'1\',
		PRIMARY KEY (`id_cms_block`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');
		$res &= Db::getInstance()->execute('
		INSERT INTO `'._DB_PREFIX_.'cms_block` (`id_cms_category`, `location`, `position`) VALUES(1, 0, 0)');
		$res &= Db::getInstance()->execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'cms_block_lang`(
		`id_cms_block` int(10) unsigned NOT NULL,
		`id_lang` int(10) unsigned NOT NULL,
		`name` varchar(40) NOT NULL default \'\',
		PRIMARY KEY (`id_cms_block`, `id_lang`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');

		$query_lang = 'INSERT INTO `'._DB_PREFIX_.'cms_block_lang` (`id_cms_block`, `id_lang`) VALUES';
		foreach ($languages AS $language)
			$query_lang .= '(1, '.(int)($language['id_lang']).'),';
		$query_lang = rtrim($query_lang, ',');
		$res &= Db::getInstance()->execute($query_lang);

		$res &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'cms_block_page`(
		`id_cms_block_page` int(10) unsigned NOT NULL auto_increment,
		`id_cms_block` int(10) unsigned NOT NULL,
		`id_cms` int(10) unsigned NOT NULL,
		`is_category` tinyint(1) unsigned NOT NULL,
		PRIMARY KEY (`id_cms_block_page`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');

		$res &= Db::getInstance()->getValue('REPLACE INTO `'._DB_PREFIX_.'configuration`
			(name, value) VALUES ("FOOTER_CMS", "")');
		$res &= Db::getInstance()->getValue('REPLACE INTO `'._DB_PREFIX_.'configuration`
			(name, value) VALUES ("FOOTER_BLOCK_ACTIVATION", "1")');
		$res &= Db::getInstance()->getValue('REPLACE INTO `'._DB_PREFIX_.'configuration`
			(name, value) VALUES ("FOOTER_POWEREDBY", "1")');
		
			//add new block in new cms block
			$res &= Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'cms_block` 
				(`id_cms_category`, `name`, `location`, `position`) 
				VALUES( 1, "", 0, 0)');
			$id_block = Db::getInstance()->insert_id();
			
			foreach ($languages AS $language)
				Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'cms_block_lang` (`id_cms_block`, `id_lang`, `name`) VALUES ('.(int)$id_block.', '.(int)$language['id_lang'].', \'Information\')');
			
			//save ids cms of block information in new module cms bloc
			foreach ($ids_cms AS $id_cms)
				Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'cms_block_page` (`id_cms_block`, `id_cms`, `is_category`) VALUES ('.(int)$id_block.', '.(int)$id_cms['id_cms'].', 0)');
		}
		else
			return true;
}
