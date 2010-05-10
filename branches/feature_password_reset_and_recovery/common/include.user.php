<?php

/**
 * Project:     Seeker
 * File:        include.user.php
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
 * along with Seeker.  If not, see 
 * http://www.gnu.org/licenses/.
 *
 * @link http://code.google.com/p/seeker-game/
 * @copyright 2010 Speed School Student Council
 * @author Jared Hatfield
 * @package seeker-game
 * @version 1.0
 */

function update_user_date($user_id){
	$query = "UPDATE `users` SET `uupdated` = NOW() WHERE `id` = " . $user_id . ";";
	$result = mysql_query($query);
}

function get_active_users(){
	$query  = "SELECT `id`, `username`, `name`, `email`, `phone`, UNIX_TIMESTAMP(`spawn`) spawn, ";
	$query .= "(SELECT COUNT(*) FROM contract WHERE `assassin` = u.`id` AND `status` = 2) successful, ";
	$query .= "(SELECT COUNT(*) FROM contract WHERE `assassin` = u.`id` AND `status` != 1) total ";
	$query .= "FROM users u WHERE `active` = 1 ORDER BY `name`;";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row;
	}
	return $val;
}

function get_inactive_users(){
	$query  = "SELECT `id`, `username`, `name`, `email`, `phone`, UNIX_TIMESTAMP(`spawn`) spawn, ";
	$query .= "(SELECT COUNT(*) FROM contract WHERE `assassin` = u.`id` AND `status` = 2) successful, ";
	$query .= "(SELECT COUNT(*) FROM contract WHERE `assassin` = u.`id` AND `status` != 1) total ";
	$query .= "FROM users u WHERE `active` = 0 ORDER BY `name`;";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row;
	}
	return $val;
}

function get_user_information($user_id){
	$query = 'SELECT `id`, `username`, `name`, `email`, `phone`, `secret`, `active`, `spawn`, IF(NOW() < spawn,0,1) status, UNIX_TIMESTAMP(`spawn`) spawn_unix FROM users WHERE `id` = ' . $user_id . ";";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	return $row;
}

function get_user_id($username){
	$query = "SELECT `id` FROM users WHERE `username` = '" . $username . "';";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	return $row[0];
}

function authenticate($username, $password){
	$query = "SELECT COUNT(*) number FROM users WHERE `username` = '" . $username . "' AND `password` = '" . sha1($password) . "';";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	if($row['number'] == 1){
		return true;
	}
	else{
		return false;
	}
}

function authenticate_id($id, $password){
	$query = "SELECT COUNT(*) number FROM users WHERE `id` = '" . $id . "' AND `password` = '" . sha1($password) . "';";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	if($row['number'] == 1){
		return true;
	}
	else{
		return false;
	}
}

function register($username, $password, $name, $email){
	$query = "INSERT INTO users (`username`, `password`, `name`, `email`, `phone`, `secret`, `active`, `spawn`, `uupdated`) ";
	$query .= "VALUES('" . $username . "', '" . sha1($password) . "', '" . $name . "', '" . $email . "', '0000000000', '" . generate_secret() . "',  1, NOW(), NOW());";
	$result = mysql_query($query);
	$id = mysql_insert_id ();
	$message = "<a href=\"./index.php?page=user&id=" . $id . "\">" . $name . "</a> has joined the game.";
	post_news_item($message);
	return $id;
}

function change_password($id, $password){
	$query = "UPDATE `users` SET  `password` = '" . sha1($password) . "' WHERE  `id` = " . $id . " LIMIT 1 ;";
	mysql_query($query);
}

function set_user_phone($userid, $phone){
	$query = "UPDATE users SET `phone` = '" . $phone . "' WHERE `id` = " . $userid . " LIMIT 1;";
	$result = mysql_query($query);
}

function toggle_user_account_status($userid){
	$query = "SELECT `active` FROM users WHERE `id` = " . $userid . ";";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	
	if(!can_user_become_active($userid)){
		// The user is not allowed to toggel their status
	}
	else if($row[0] == "1"){
		$query = "UPDATE users SET `active` = 0 WHERE `id` = " . $userid . ";";
		$result = mysql_query($query);
		$query = "INSERT INTO audit_status (`userid`, `previous`, `new`, `time`) VALUES(" . $userid . ", 1, 0, NOW());";
		$result = mysql_query($query);
	}
	else if($row[0] == "0"){
		$query = "UPDATE users SET `active` = 1 WHERE `id` = " . $userid . ";";
		$result = mysql_query($query);
		$query = "INSERT INTO audit_status (`userid`, `previous`, `new`, `time`) VALUES(" . $userid . ", 0, 1, NOW());";
		$result = mysql_query($query);
	}
}

function can_user_become_active($userid){
	global $_CONFIG;
	// There is nothing in the audit_status table so this user can change their status
	$query = "SELECT COUNT(*) number FROM `audit_status` WHERE `userid` = " . $userid . ";";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	if($row[0] == 0){
		return true;
	}
	
	// See if the user has not changed their status in the given period of time that they are allowed to change their status
	$query = "SELECT IF(DATE_ADD(MAX(`time`), INTERVAL " . $_CONFIG['inactivedelay'] . " HOUR)<NOW(),1,0) canrejoin FROM audit_status WHERE `userid` = " . $userid . ";";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	if($row[0] == 1){
		return true;
	}
	return false;
}

function time_left_till_user_can_become_active($userid){
	global $_CONFIG;
	$query = "SELECT IFNULL((UNIX_TIMESTAMP(DATE_ADD(MAX(`time`), INTERVAL " . $_CONFIG['inactivedelay'] . " HOUR)) - UNIX_TIMESTAMP(NOW())),0) timeleft FROM audit_status
WHERE `userid` = " . $userid . ";";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	return convert_seconds_to_human_time($row[0]);
}

function switch_user_to_inactive($userid){
	$query = "UPDATE `users` SET `active` = 0 WHERE `id` = " . $userid . ";";
	$result = mysql_query($query);
}

?>