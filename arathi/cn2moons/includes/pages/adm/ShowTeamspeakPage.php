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
 * @info $Id: ShowTeamspeakPage.php 2746 2013-05-18 11:38:36Z slaver7 $
 * @link http://2moons.cc/
 */

if (!allowedTo(str_replace(array(dirname(__FILE__), '\\', '/', '.php'), '', __FILE__))) throw new Exception("Permission error!");

function ShowTeamspeakPage() {
	global $LNG;


	$config = Config::get(Universe::getEmulated());

	if ($_POST)
	{
		$config_before = array(
			'ts_timeout'		=> $config->ts_timeout,
			'ts_modon'			=> $config->ts_modon,
			'ts_server'			=> $config->ts_server,
			'ts_tcpport'		=> $config->ts_tcpport,
			'ts_udpport'		=> $config->ts_udpport,
			'ts_version'		=> $config->ts_version,
			'ts_login'			=> $config->ts_login,
			'ts_password'		=> $config->ts_password,
			'ts_cron_interval'	=> $config->ts_cron_interval
		);
		
		$ts_modon 			= isset($_POST['ts_on']) && $_POST['ts_on'] == 'on' ? 1 : 0;		
		$ts_server			= HTTP::_GP('ts_ip', '');
		$ts_tcpport			= HTTP::_GP('ts_tcp', 0);
		$ts_udpport			= HTTP::_GP('ts_udp', 0);
		$ts_timeout			= HTTP::_GP('ts_to', 0);
		$ts_version			= HTTP::_GP('ts_v', 0);
		$ts_login			= HTTP::_GP('ts_login', '');
		$ts_password		= HTTP::_GP('ts_password', '', true);
		$ts_cron_interval	= HTTP::_GP('ts_cron', 0);
		
		$config_after = array(
			'ts_timeout'		=> $ts_timeout,
			'ts_modon'			=> $ts_modon,
			'ts_server'			=> $ts_server,
			'ts_tcpport'		=> $ts_tcpport,
			'ts_udpport'		=> $ts_udpport,
			'ts_version'		=> $ts_version,
			'ts_login'			=> $ts_login,
			'ts_password'		=> $ts_password,
			'ts_cron_interval'	=> $ts_cron_interval
		);


		foreach($config_after as $key => $value)
		{
			$config->$key	= $value;
		}

		$config->save();

		$sql	= "UPDATE %%CRONJOBS%%
		SET isActive = :isActive, `lock` = NULL, nextTime = 0
		WHERE name = 'teamspeak';";

		Database::get()->update($sql, array(
			':isActive'	=> $ts_modon,
		));
		
		$LOG = new Log(3);
		$LOG->target = 4;
		$LOG->old = $config_before;
		$LOG->new = $config_after;
		$LOG->save();
		
	}
	$template	= new template();
	

	$template->assign_vars(array(
		'se_save_parameters'	=> $LNG['se_save_parameters'],
		'ts_tcpport'			=> $LNG['ts_tcpport'],
		'ts_serverip'			=> $LNG['ts_serverip'],
		'ts_version'			=> $LNG['ts_version'],
		'ts_active'				=> $LNG['ts_active'],
		'ts_settings'			=> $LNG['ts_settings'],
		'ts_udpport'			=> $LNG['ts_udpport'],
		'ts_timeout'			=> $LNG['ts_timeout'],
		'ts_server_query'		=> $LNG['ts_server_query'],
		'ts_sq_login'			=> $LNG['ts_login'],
		'ts_sq_pass'			=> $LNG['ts_pass'],
		'ts_lng_cron'			=> $LNG['ts_cron'],
		'ts_to'					=> $config->ts_timeout,
		'ts_on'					=> $config->ts_modon,
		'ts_ip'					=> $config->ts_server,
		'ts_tcp'				=> $config->ts_tcpport,
		'ts_udp'				=> $config->ts_udpport,
		'ts_v'					=> $config->ts_version,
		'ts_login'				=> $config->ts_login,
		'ts_password'			=> $config->ts_password,
		'ts_cron'				=> $config->ts_cron_interval
	));
	$template->show('TeamspeakPage.tpl');

}