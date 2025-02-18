<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

function set_payment_module()
{
	// Get all modules then select only payment ones
	$modules = Module::getModulesInstalled();
	foreach ($modules AS $module)
	{
		$file = _PS_MODULE_DIR_.$module['name'].'/'.$module['name'].'.php';
		if (!file_exists($file))
			continue;
		$fd = fopen($file, 'r');
		if (!$fd)
			continue ;
		$content = fread($fd, filesize($file));
		if (preg_match_all('/extends PaymentModule/U', $content, $matches))
		{
			Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'module_country` (id_module, id_country)
			SELECT '.(int)($module['id_module']).', id_country FROM `'._DB_PREFIX_.'country` WHERE active = 1');
			Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'module_currency` (id_module, id_currency)
			SELECT '.(int)($module['id_module']).', id_currency FROM `'._DB_PREFIX_.'currency` WHERE deleted = 0');
		}
		fclose($fd);
	}
}


