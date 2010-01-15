<?php

/**
 * Project:     Seeker
 * File:        include.manage.php
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
 * @copyright 2009 Speed School Student Council
 * @author Jared Hatfield
 * @package seeker-game
 * @version 1.0
 */

function assign_new_contracts(){
	// Find all of the students that do not have a contract
	$query = "SELECT `id` FROM users u WHERE `id` NOT IN (SELECT `assassin` FROM contract WHERE `status` = 1) AND `spawn` < NOW();";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row;
	}
	
	// Assign contracts to those students
	for($i = 0; $i < sizeof($val); $i++){
		echo print_r($val[$i]);
	}
}

function get_target_for($assassin){
	$query  = "SELECT `id`, IFNULL(`weight`,0) weight ";
	$query .= "FROM users u ";
	$query .= "LEFT JOIN (SELECT `target`, count(*) weight FROM contract WHERE `status` = 1 GROUP BY `target`) p ON u.`id` = p.`target` ";
	$query .= "WHERE `spawn` < NOW() AND `id` != " . $assassin . " ";
	$query .= "AND `id` NOT IN (SELECT `assassin` FROM contract WHERE `target` = " . $assassin . " AND `status` = 1);";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row;
	}
	
	// Pick someone randomly from the list of possible targets
	if(sizeof($val) > 0){
		return $val[rand(0, sizeof($val) - 1)]['id'];
	}
	else{
		return -1;
	}
}

function add_contract($assassin, $target, $hour){
	$query  = "INSERT INTO contract (`assassin`, `target`, `assigned`, `expiration`, `updated`, `status`) ";
	$query .= "VALUE (" . $assassin . "," . $target . ",NOW(),ADDDATE(NOW(), INTERVAL " . $hour . " HOUR),NOW(), 1);";
	$result = mysql_query($query);
}

function contract_open_count($user_id){
	$query = "SELECT COUNT(*) number FROM contract WHERE `status` = 1 AND `assassin` = " . $user_id . ";";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	return $row[0];
}





?>