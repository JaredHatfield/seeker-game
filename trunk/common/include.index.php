<?php

/**
 * Project:     Seeker
 * File:        include.index.php
 *
 * Seeker is free software: you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published 
 * by the Free Software Foundation, either version 3 of the License, 
 * or (at your option) any later version.
 * 
 * Seeker is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Student Council Attendance.  If not, see 
 * http://www.gnu.org/licenses/.
 *
 * @link http://code.google.com/p/seeker-game/
 * @copyright 2010 Speed School Student Council
 * @author Jared Hatfield
 * @package seeker-game
 * @version 1.0
 */

 session_start();
 
include_once("./common/include.contracts.php");
include_once("./common/include.user.php");
include_once("./common/include.manage.php");
include_once("./common/include.mail.php");
include_once("./common/include.news.php");
include_once("./common/include.textmessage.php");


$conn = mysql_connect($_CONFIG['host'], $_CONFIG['username'] , $_CONFIG['password'] ) or die ('Error connecting to mysql');
$selected = mysql_select_db($_CONFIG['database'], $conn) or die ('Database unavailable');

function get_open_contract_count(){
	$query = "SELECT COUNT(*) number FROM contract WHERE `status` = 1;";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_successful_contract_count(){
	$query = "SELECT COUNT(*) FROM contract WHERE `status` = 2;";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_total_contract_count(){
	$query = "SELECT COUNT(*) FROM contract;";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_active_member_count(){
	$query = "SELECT COUNT(*) number FROM users WHERE `active` = 1;";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	return $row[0];
}

function get_live_member_count(){
	$query = "SELECT COUNT(*) number FROM users WHERE `active` = 1 AND `spawn` < NOW();";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	return $row[0];
}

?>