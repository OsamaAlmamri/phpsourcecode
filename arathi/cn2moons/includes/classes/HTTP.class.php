<?php

/**
 *  2Moons
 *  Copyright (C) 2012 Jan Kröpke
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package 2Moons
 * @author Jan Kröpke <info@2moons.cc>
 * @copyright 2012 Jan Kröpke <info@2moons.cc>
 * @license http://www.gnu.org/licenses/gpl.html GNU GPLv3 License
 * @version 1.7.2 (2013-03-18)
 * @info $Id: HTTP.class.php 2752 2013-05-20 15:13:04Z slaver7 $
 * @link http://2moons.cc/
 */

class HTTP {
	
	static public function redirectTo($URL, $external = false)
	{
		if($external)
		{
			self::sendHeader('Location', $URL);
		}
		else
		{
			self::sendHeader('Location', HTTP_PATH.$URL);
		}
		exit;
	}

	static public function sendHeader($name, $value = NULL)
	{
		header($name.(!is_null($value) ? ': '.$value : ''));
	}

	static public function redirectToUniverse($universe)
	{
		HTTP::redirectTo(PROTOCOL.HTTP_HOST.HTTP_BASE."uni".$universe."/".HTTP_FILE, true);
	}

	static public function sendCookie($name, $value = "", $toTime = NULL)
	{
		setcookie($name, $value, $toTime);
	}
	
	static public function _GP($name, $default, $multibyte = false, $highnum = false)
	{
		if(!isset($_REQUEST[$name]))
		{
			return $default;
		}

		if(is_float($default) || $highnum)
		{
			return (float) $_REQUEST[$name];
		}
		
		if(is_int($default))
		{
			return (int) $_REQUEST[$name];			
		}

		if(is_string($default))
		{
			return self::_quote($_REQUEST[$name], $multibyte);
		}
		
		if(is_array($default))
		{
			return self::_quoteArray($_REQUEST[$name], $multibyte);
		}
		
		return $default;
	}

	private static function _quoteArray($var, $multibyte)
	{
		$data	= array();
		foreach($var as $key => $value)
		{
			if(is_array($value))
			{
				$data[$key]	= self::_quoteArray($value, $multibyte);
			}
			else
			{
				$data[$key]	= self::_quote($value, $multibyte);
			}
		}

		return $data;
	}

	private static function _quote($var, $multibyte)
	{
		$var	= str_replace(array("\r\n", "\r", "\0"), array("\n", "\n", ''), $var);
		$var	= htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
		$var	= trim($var);

		if ($multibyte) {
			if (!preg_match('/^./u', $var))
			{
				$var = '';
			}
		}
		else
		{
			$var = preg_replace('/[\x80-\xFF]/', '?', $var); // no multibyte, allow only ASCII (0-127)
		}

		return $var;
	}
}