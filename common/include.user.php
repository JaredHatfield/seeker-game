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
 * along with Student Council Attendance.  If not, see 
 * http://www.gnu.org/licenses/.
 *
 * @link http://code.google.com/p/seeker-game/
 * @copyright 2010 Speed School Student Council
 * @author Jared Hatfield
 * @package seeker-game
 * @version 1.0
 */

function get_active_users(){
	$query = "SELECT `id`, `username`, `name`, `email`, `phone` FROM users u WHERE `active` = 1 ORDER BY `name`;";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row;
	}
	return $val;
}

function get_user_information($user_id){
	$query = "SELECT `id`, `username`, `name`, `email`, `phone`, `secret`, `active`, `spawn` FROM users WHERE `id` = " . $user_id . ";";
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

function register($username, $password, $name, $email){
	$query = "INSERT INTO users (`username`, `password`, `name`, `email`, `phone`, `secret`, `active`, `spawn`) ";
	$query .= "VALUES('" . $username . "', '" . sha1($password) . "', '" . $name . "', '" . $email . "', '0000000000', '" . generate_secret() . "',  1, NOW());";
	$result = mysql_query($query);
	return mysql_insert_id ();
}









?>