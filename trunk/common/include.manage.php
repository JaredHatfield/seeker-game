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
 * along with Seeker.  If not, see 
 * http://www.gnu.org/licenses/.
 *
 * @link http://code.google.com/p/seeker-game/
 * @copyright 2010 Speed School Student Council
 * @author Jared Hatfield
 * @package seeker-game
 * @version 1.0
 */

function expire_contracts(){
	// Get the id's of the contracts that have expired and need to be marked as such
	$query = "SELECT `id` FROM contract WHERE `expiration` < NOW() AND `status` = 1;";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row['id'];
	}
	
	// Go through all of these contracts
	for($i = 0; $i < sizeof($val); $i++){
		// Mark the contract as expired
		$query = "UPDATE contract SET `status` = 3, `updated` = NOW() WHERE `id` = " . $val[$i] . " AND `expiration` < NOW() AND `status` = 1;";
		$result = mysql_query($query);
		
		// Send a message to the user
		send_contract_failed_by_expiration($val[$i]);
		// Post this to the news feed
		post_news_item_failed($val[$i], "expired");
	}
}
 
function assign_new_contracts(){
	global $_CONFIG;
	// Find all of the users that do not have a contract and are eligible to have one assigned to them
	$query = "SELECT `id` FROM users u WHERE `id` NOT IN (SELECT `assassin` FROM contract WHERE `status` = 1) AND `spawn` < NOW() AND `active` = 1;";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row['id'];
	}
	
	// Shuffel the list of users so they are not always assigned contracts in the same order
	shuffle($val);
	
	// Assign contracts to those students
	$count = 0;
	for($i = 0; $i < sizeof($val); $i++){
		$assassin = $val[$i];
		$target = get_target_for($assassin);
		// There is a potential target
		if($target != -1){
			// The message notifying the user about their contract is sent in the add_contract function
			add_contract($assassin, $target, $_CONFIG['contractlength']);
			$count++;
		}
	}
	if($count == 1){
		post_news_item("A new contract has been issued.");
	}
	else if($count > 1){
		post_news_item($count . " new contracts have been issued.");
	}
}

function get_target_for($assassin){
	global $_CONFIG;
	$query  = "SELECT `id`, IFNULL(`weight`,0) weight ";
	$query .= "FROM users u ";
	$query .= "LEFT JOIN (SELECT `target`, count(*) weight FROM contract WHERE `status` = 1 GROUP BY `target`) p ON u.`id` = p.`target` ";
	$query .= "WHERE `spawn` < NOW() AND `id` != " . $assassin . "  AND `active` = 1 ";
	$query .= "AND `id` NOT IN (SELECT `assassin` FROM contract WHERE `target` = " . $assassin . " AND `status` = 1) ";
	$query .= "AND `id` NOT IN (SELECT target FROM (SELECT `target` FROM contract c WHERE `assassin` = " . $assassin . " ORDER BY assigned DESC LIMIT 4) previous_contracts)";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row['id'];
	}
	
	// Pick someone randomly from the list of possible targets
	if(sizeof($val) > 0){
		return $val[rand(0, sizeof($val) - 1)];
	}
	else{
		return -1;
	}
}

function add_contract($assassin, $target, $hour){
	$query  = "INSERT INTO contract (`assassin`, `target`, `assigned`, `expiration`, `updated`, `status`) ";
	$query .= "VALUE (" . $assassin . "," . $target . ",NOW(),ADDDATE(NOW(), INTERVAL " . $hour . " HOUR),NOW(), 1);";
	$result = mysql_query($query);
	
	// We need to send a message to the user so they know about their contract
	$contract_id = mysql_insert_id();
	send_contract_notification($contract_id);
}

function contract_open_count($user_id){
	$query = "SELECT COUNT(*) number FROM contract WHERE `status` = 1 AND `assassin` = " . $user_id . ";";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	return $row[0];
}





?>