<?php

/**
 * Project:     Seeker
 * File:        include.contracts.php
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

function get_user_contract_id($user_id){
	$query  = "SELECT `id` FROM contract WHERE `assassin` = " . $user_id . " AND `status` = 1;";
	$result = mysql_query($query);
	if(mysql_num_rows($result) == 1){
		$row = mysql_fetch_row($result);
		return $row[0];
	}
	else{
		return -1;
	}
}

function get_contract_information($contract_id){
	$query  = "SELECT c.id, c.assassin, ua.name assassin_name, ua.email assassin_email, c.target, ut.name target_name, ";
	$query .= "assigned, expiration, time_to_sec(timediff(expiration, NOW())) seconds_remaining, updated, status FROM contract c ";
	$query .= "JOIN users ua ON c.assassin = ua.id JOIN users ut ON c.target = ut.id WHERE c.`id` = " . $contract_id . ";";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	return $row;
}

function check_kill_attempt($contract_id, $key){
	
}
?>