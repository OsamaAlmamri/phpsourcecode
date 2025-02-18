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
 * @info $Id: ShowMenuPage.php 2746 2013-05-18 11:38:36Z slaver7 $
 * @link http://2moons.cc/
 */

function ShowMenuPage()
{
	global $USER;
	$template	= new template();
	
	$template->assign_vars(array(	
		'supportticks'	=> $GLOBALS['DATABASE']->getFirstCell("SELECT COUNT(*) FROM ".TICKETS." WHERE universe = ".Universe::getEmulated()." AND status = 0;"),
	));
	
	$template->show('ShowMenuPage.tpl');
}
