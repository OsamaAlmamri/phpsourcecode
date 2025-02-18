<?php
/**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 */

class InstallLanguages
{
	const DEFAULT_ISO = 'zh';
	/**
	 * @var array List of available languages
	 */
	protected $languages;

	/**
	 * @var string Current language
	 */
	protected $language;

	/**
	 * @var InstallLanguage Default language (english)
	 */
	protected $default;

	protected static $_instance;

	public static function getInstance()
	{
		if (!self::$_instance)
			self::$_instance = new self();
		return self::$_instance;
	}

	public function __construct()
	{
		// English language is required
		if (!file_exists(_PS_INSTALL_LANGS_PATH_.'zh/language.xml'))
			throw new MileBizInstallerException('English language is missing');

		$this->languages = array(
			self::DEFAULT_ISO => new InstallLanguage(self::DEFAULT_ISO),
		);

		// Load other languages
		foreach (scandir(_PS_INSTALL_LANGS_PATH_) as $lang)
			if ($lang[0] != '.' && is_dir(_PS_INSTALL_LANGS_PATH_.$lang) && $lang != self::DEFAULT_ISO)
				$this->languages[$lang] = new InstallLanguage($lang);
	}

	/**
	 * Set current language
	 *
	 * @param string $iso Language iso
	 */
	public function setLanguage($iso)
	{
		if (!in_array($iso, $this->getIsoList()))
			throw new MileBizInstallerException('Language '.$iso.' not found');

		$this->language = $iso;
	}

	/**
	 * Get current language
	 *
	 * @return string
	 */
	public function getLanguageIso()
	{
		return $this->language;
	}

	/**
	 * Get current language
	 *
	 * @return InstallLanguage
	 */
	public function getLanguage($iso = null)
	{
		if (!$iso)
			$iso = $this->language;
		return $this->languages[$iso];
	}

	public function getIsoList()
	{
		return array_keys($this->languages);
	}

	/**
	 * Get list of languages iso supported by installer
	 *
	 * @return array
	 */
	public function getLanguages()
	{
		return $this->languages;
	}

	/**
	 * Get translated string
	 *
	 * @param string $str String to translate
	 * @param ... All other params will be used with sprintf
	 * @return string
	 */
	public function l($str)
	{
		$args = func_get_args();
		$translation = $this->getLanguage()->getTranslation($args[0]);
		if (is_null($translation))
		{
			$translation = $this->getLanguage(self::DEFAULT_ISO)->getTranslation($args[0]);
			if (is_null($translation))
				$translation = $args[0];
		}

		$args[0] = $translation;
		return call_user_func_array('sprintf', $args);
	}

	/**
	 * Get an information from language (phone, links, etc.)
	 *
	 * @param string $key Information identifier
	 */
	public function getInformation($key, $with_default = true)
	{
		$information = $this->getLanguage()->getTranslation($key, 'informations');
		if (is_null($information) && $with_default)
			return $this->getLanguage(self::DEFAULT_ISO)->getTranslation($key, 'informations');
		return $information;
	}

	/**
	 * Get list of countries for current language
	 *
	 * @return array
	 */
	public function getCountries()
	{
		static $countries = null;

		if (is_null($countries))
		{
			$countries = array();
			$countries_lang = $this->getLanguage()->getCountries();
			$countries_default = $this->getLanguage(self::DEFAULT_ISO)->getCountries();
			$xml = simplexml_load_file(_PS_INSTALL_DATA_PATH_.'xml/country.xml');
			foreach ($xml->entities->country as $country)
			{
				$iso = strtolower((string)$country['iso_code']);
				$countries[$iso] = isset($countries_lang[$iso]) ? $countries_lang[$iso] : $countries_default[$iso];
			}
			asort($countries);
		}

		return $countries;
	}

	/**
	 * Parse HTTP_ACCEPT_LANGUAGE and get first data matching list of available languages
	 *
	 * @return bool|array
	 */
	public function detectLanguage()
	{
		// This code is from a php.net comment : http://www.php.net/manual/fr/reserved.variables.server.php#94237
		$split_languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		if (!is_array($split_languages))
			return false;

		foreach ($split_languages as $lang)
		{
			$pattern = '/^(?P<primarytag>[a-zA-Z]{2,8})'.
				'(?:-(?P<subtag>[a-zA-Z]{2,8}))?(?:(?:;q=)'.
				'(?P<quantifier>\d\.\d))?$/';
			if (preg_match($pattern, $lang, $m))
				if (in_array($m['primarytag'], $this->getIsoList()))
					return $m;
		}
		return false;
	}
}
