<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Model class
*/
abstract class Model {
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}

    protected function toArray($result)
    {
        if (empty($result)) {
            return [];
        } elseif ($result instanceof \Illuminate\Support\Collection) {
            return $result->map(function ($item) {
                return (array)$item;
            })->toArray();
        } elseif (get_class($result) == 'stdClass') {
            return (array)$result;
        }
        return [];
    }
}