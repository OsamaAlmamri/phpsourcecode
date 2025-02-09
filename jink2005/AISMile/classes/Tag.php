<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

class TagCore extends ObjectModel
{
 	/** @var integer Language id */
	public $id_lang;

 	/** @var string Name */
	public $name;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'tag',
		'primary' => 'id_tag',
		'fields' => array(
			'id_lang' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'name' => 		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
		),
	);


	protected $webserviceParameters = array(
		'fields' => array(
			'id_lang' => array('xlink_resource' => 'languages'),
		),
	);

	public function __construct($id = null, $name = null, $id_lang = null)
	{
		$this->def = Tag::getDefinition($this);
		$this->setDefinitionRetrocompatibility();

		if ($id)
			parent::__construct($id);
		else if ($name && Validate::isGenericName($name) && $id_lang && Validate::isUnsignedId($id_lang))
		{
			$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
			SELECT *
			FROM `'._DB_PREFIX_.'tag` t
			WHERE `name` LIKE \''.pSQL($name).'\' AND `id_lang` = '.(int)$id_lang);

			if ($row)
			{
			 	$this->id = (int)$row['id_tag'];
			 	$this->id_lang = (int)$row['id_lang'];
				$this->name = $row['name'];
			}
		}
	}

	public function add($autodate = true, $null_values = false)
	{
		if (!parent::add($autodate, $null_values))
			return false;
		else if (isset($_POST['products']))
			return $this->setProducts(Tools::getValue('products'));
		return true;
	}

	/**
	* Add several tags in database and link it to a product
	*
	* @param integer $id_lang Language id
	* @param integer $id_product Product id to link tags with
	* @param string|array $tag_list List of tags, as array or as a string with comas
	* @return boolean Operation success
	*/
	public static function addTags($id_lang, $id_product, $tag_list, $separator = ',')
	{
	 	if (!Validate::isUnsignedId($id_lang))
			return false;

		if (!is_array($tag_list))
	 		$tag_list = array_unique(array_map('trim', preg_split('#\\'.$separator.'#', $tag_list, null, PREG_SPLIT_NO_EMPTY)));

	 	$list = array();
		foreach ($tag_list as $tag)
		{
	 	 	if (!Validate::isGenericName($tag))
	 	 		return false;
			$tag_obj = new Tag(null, trim($tag), (int)$id_lang);

			/* Tag does not exist in database */
			if (!Validate::isLoadedObject($tag_obj))
			{
				$tag_obj->name = trim($tag);
				$tag_obj->id_lang = (int)$id_lang;
				$tag_obj->add();
			}
			if (!in_array($tag_obj->id, $list))
				$list[] = $tag_obj->id;
		}
		$data = '';
		foreach ($list as $tag)
			$data .= '('.(int)$tag.','.(int)$id_product.'),';
		$data = rtrim($data, ',');

		return Db::getInstance()->execute('
		INSERT INTO `'._DB_PREFIX_.'product_tag` (`id_tag`, `id_product`)
		VALUES '.$data);
	}

	public static function getMainTags($id_lang, $nb = 10)
	{
		$groups = FrontController::getCurrentCustomerGroups();
		$sql_groups = (count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');

		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT t.name, COUNT(pt.id_tag) AS times
		FROM `'._DB_PREFIX_.'product_tag` pt
		LEFT JOIN `'._DB_PREFIX_.'tag` t ON (t.id_tag = pt.id_tag)
		LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.id_product = pt.id_product)
		'.Shop::addSqlAssociation('product', 'p').'
		WHERE t.`id_lang` = '.(int)$id_lang.'
		AND product_shop.`active` = 1
		AND product_shop.`id_product` IN (
			SELECT cp.`id_product`
			FROM `'._DB_PREFIX_.'category_group` cg
			LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
			WHERE cg.`id_group` '.$sql_groups.'
		)
		GROUP BY t.id_tag
		ORDER BY times DESC
		LIMIT 0, '.(int)$nb);
	}

	public static function getProductTags($id_product)
	{
	 	if (!$tmp = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT t.`id_lang`, t.`name`
		FROM '._DB_PREFIX_.'tag t
		LEFT JOIN '._DB_PREFIX_.'product_tag pt ON (pt.id_tag = t.id_tag)
		WHERE pt.`id_product`='.(int)$id_product))
	 		return false;
	 	$result = array();
	 	foreach ($tmp as $tag)
	 		$result[$tag['id_lang']][] = $tag['name'];
	 	return $result;
	}

	public function getProducts($associated = true, Context $context = null)
	{
		if (!$context)
			$context = Context::getContext();
		$id_lang = $this->id_lang ? $this->id_lang : $context->language->id;

		if (!$this->id && $associated)
			return array();

		$in = $associated ? 'IN' : 'NOT IN';
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT pl.name, pl.id_product
		FROM `'._DB_PREFIX_.'product` p
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON p.id_product = pl.id_product'.Shop::addSqlRestrictionOnLang('pl').'
		'.Shop::addSqlAssociation('product', 'p').'
		WHERE pl.id_lang = '.(int)$id_lang.'
		AND product_shop.active = 1
		'.($this->id ? ('AND p.id_product '.$in.' (SELECT pt.id_product FROM `'._DB_PREFIX_.'product_tag` pt WHERE pt.id_tag = '.(int)$this->id.')') : '').'
		ORDER BY pl.name');
	}

	public function setProducts($array)
	{
		$result = Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'product_tag WHERE id_tag = '.(int)$this->id);
		if (is_array($array))
		{
			$array = array_map('intval', $array);
			$result &= ObjectModel::updateMultishopTable('Product', array('indexed' => 0), 'a.id_product IN ('.implode(',', $array).')');
			$ids = array();
			foreach ($array as $id_product)
				$ids[] = '('.(int)$id_product.','.(int)$this->id.')';

			if ($result)
			{
				$result &= Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'product_tag (id_product, id_tag) VALUES '.implode(',', $ids));
				if (Configuration::get('PS_SEARCH_INDEXATION'))
					$result &= Search::indexation(false);
			}
		}
		return $result;
	}

	public static function deleteTagsForProduct($id_product)
	{
		return Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_tag` WHERE `id_product` = '.(int)$id_product);
	}
}


