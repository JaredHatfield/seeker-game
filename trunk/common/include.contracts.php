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

function get_users_contracts($user_id){
	$query  = "SELECT c.`id`, c.`target`, ut.`name` target_name, c.`assigned`, c.`expiration`, c.`updated`, c.`status`, s.`value` status_name ";
	$query .= "FROM contract c JOIN status s ON c.status = s.id JOIN users ut ON c.target = ut.id WHERE `assassin` = " . $user_id . " ";
	$query .= "AND c.`status` != 1 ORDER BY `assigned` DESC;";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row;
	}
	return $val;
}

function is_valid_contract_id($contract_id){
	$query = "SELECT COUNT(*) number FROM contract WHERE `id` = 3;";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	if($row[0] == 1){
		return true;
	}
	else{
		return false;
	}
}

function kill_attempt($contract_id, $key, $source){
	global $_CONFIG;
	// Check to see if the contract even exists, if it is not we are stopping here
	/*
	if(!is_valid_contract_id($contract_id)){
		return false;
	}
	*/
	
	// Get the contract status and information
	$contract_info = get_contract_information($contract_id);
	$target_info = get_user_information($contract_info['target']);
	
	// Log this attempt in the audit table
	$query = "INSERT INTO audit_contract (`contractid`, `submitted`, `actual`, `source`, `time`) VALUES(" . $contract_id . ", '" . $key . "', '" . $target_info['secret'] . "', '" . $source . "', NOW());";
	$result = mysql_query($query);
	
	// Determine if the key was valid
	if(strtolower($key) == strtolower($target_info['secret'])){
		// The secrets match, the kill was a success so update the game state
		
		// Mark this contract as successful
		$query = "UPDATE contract  SET `status` = 2, UPDATED = NOW() WHERE `id` = " . $contract_id . ";";
		$result = mysql_query($query);
		
		// Tell the assassin that he/she was successful
		send_contract_success($contract_id);
		post_news_item("<a href=\"./index.php?page=user&id=" . $contract_info['assassin'] . "\">" . $contract_info['assassin_name'] . "</a> successfully completed a contract by locating <a href=\"./index.php?page=user&id=" . $contract_info['target'] . "\">" . $contract_info['target_name'] . "</a>");
		
		// Get a list of contracts that were missed because the target was killed
		$query  = "SELECT `id` FROM contract WHERE `status` = 1 AND `assassin` != " . $contract_info['assassin'];
		$query .= " AND `target` = " . $contract_info['target'] . ";";
		$result = mysql_query($query);
		$missed_contracts = array();
		while($row = mysql_fetch_assoc($result)){
			$missed_contracts[] = $row['id'];
		}
		
		// Mark other contracts for this target as failed
		$query = "UPDATE contract SET `status` = 5, UPDATED = NOW() WHERE `target` = " . $contract_info['target'] . " AND `status` = 1;";
		$result = mysql_query($query);
		
		// Tell those who missed their target that they missed their target
		for($i = 0; $i < sizeof($missed_contracts); $i++){
			send_contract_missed($missed_contracts[$i], $contract_info['assassin']);
			post_news_item_failed($missed_contracts[$i], "missed");
		}
		
		// Mark the targets contract as failed if this person had an active contract
		$targets_failed_contract_id = -1;
		$query = "SELECT `id` FROM contract WHERE `assassin` = " . $contract_info['target'] . " AND `status` = 1;";
		$result = mysql_query($query);
		if($row = mysql_fetch_assoc($result)){
			$targets_failed_contract_id = $row['id'];
		}
		$query = "UPDATE contract SET `status` = 4, UPDATED = NOW() WHERE `assassin` = " . $contract_info['target'] . " AND `status` = 1;";
		$result = mysql_query($query);
		
		// Set the targets respawn time for the target
		$query = "UPDATE users SET `spawn` = ADDDATE(NOW(), INTERVAL " . $_CONFIG['respawntime'] . " HOUR) WHERE `id` = " . $contract_info['target'] . ";";
		$result = mysql_query($query);
		
		// Assign a new secret for the member that was killed
		$query = "UPDATE users SET `secret` = \"" . generate_secret() . "\" WHERE `id` = " . $contract_info['target'] . ";";
		$result = mysql_query($query);
		
		// Tell the target that they were killed
		send_contract_failed_by_death($contract_info['target'], $contract_info['assassin'], $targets_failed_contract_id);
		if($targets_failed_contract_id != -1){
			post_news_item_failed($targets_failed_contract_id, "killed");
		}
		
		
		// Issue new contracts
		// TODO: This can be done here, but more likely this will just place a call s
		
		return true;
	}
	else{
		// The key was not a match so the target was not killed
		return false;
	}
}

function generate_secret()
{
	global $_CONFIG;
	$length = $_CONFIG['secretlength'];
	$password = "";
	$alphabet = $_CONFIG['secretalphabet'];
	if($length >= strlen($alphabet)){
		$length = strlen($alphabet) - 2;
	}
	$i = 0; 
	while ($i < $length) { 
		// pick a random character from the alphabet
		$char = substr($alphabet, mt_rand(0, strlen($alphabet)-1), 1);
		// skip characters that are already in the string
		if (!strstr($password, $char)) { 
			$password .= $char;
			$i++;
		}
	}
	return $password;
}

?>