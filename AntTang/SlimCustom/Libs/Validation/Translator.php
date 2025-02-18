<?php
/**
 * @package     Translator.php
 * @author      Jing <tangjing3321@gmail.com>
 * @link        http://www.slimphp.net
 * @version     1.0
 * @copyright   Copyright (c) SlimCustom.
 * @date        2016年10月9日
 */
namespace SlimCustom\Libs\Validation;

use SlimCustom\Libs\Support\Arr;

class Translator
{

    /**
     * Get the translation for the given key.
     *
     * @param string $key            
     * @param array $replace            
     * @param string $locale            
     * @return string
     */
    public function get($key, array $replace = array(), $locale = null)
    {
        $errKey = explode('.', $key);
        $message = config($errKey['0'], []);
        $line = Arr::get($message, $errKey['1']);
        if (Arr::has($errKey, 2)) {
            $line = $line[$errKey['2']];
        }
        // If the line doesn't exist, we will return back the key which was requested as
        // that will be quick to spot in the UI if language keys are wrong or missing
        // from the application's language files. Otherwise we can return the line.
        if (! isset($line))
            return $key;
        
        return $line;
    }

    /**
     * Get the translation for a given key.
     *
     * @param string $id            
     * @param array $parameters            
     * @param string $domain            
     * @param string $locale            
     * @return string
     */
    public function trans($id, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        return $this->get($id, $parameters, $locale);
    }
}
