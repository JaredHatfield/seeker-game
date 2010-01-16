<?php

/**
 * Project:     Seeker
 * File:        include.textmessage.php
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
 
function insert_zeep_mo($uid, $body, $return){
	$query = "INSERT INTO zeep_mo (`uid`, `body`, `return`, `time`) VALUES (" . $uid . ", '" . $body . "', '" . $return . "', NOW());";
	$result = mysql_query($query);
}

function insert_zeep_sub($uid, $min, $return){
	$query = "INSERT INTO zeep_sub (`uid`, `min`, `return, `time`) VALUES(" . $uid . ", '" . $min . "', '" . $return . "', NOW());";
	$result = mysql_query($query);
}

function get_user_short_score($userid){
	$query = "SELECT COUNT(*) number FROM contract WHERE `assassin` = " . $userid . " AND `status` = 2;";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	$successful = $row[0];
	$query = "SELECT COUNT(*) number FROM contract WHERE `assassin` = 6;";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	$total = $row[0];
	return "Successfully completed " . $successful . " of " . $total . " contracts.";
}

function get_user_short_target($userid){
	$contracts = get_user_contract_id($userid);
	if(sizeof($contracts) == 1){
		$contract_info = get_contract_information($contracts[0]);
		$contract_hours_left = floor($contract_info['seconds_remaining']/60/60);
		$contract_minutes_left =  floor(($contract_info['seconds_remaining'] - $contract_hours_left*60*60)/60);
		return "Target: " . $contract_info['target_name'] . " Time Remaining: " . $contract_hours_left . " hours and " . $contract_minutes_left . " minutes";
	}
	else if(sizeof($contracts) == 0){
		return "You have no outstanding contracts.";
	}
	else{
		return "There was an error processing your request.";
	}
}

function get_user_short_status($userid){
	$user_info = get_user_information($userid);
	if($user_info['status'] == 0){
		$diff = $users[$i]['spawn_unix'] - date(time());
		$contract_hours_left = floor($diff/60/60);
		$contract_minutes_left =  floor(($diff - $contract_hours_left*60*60)/60);
		return "Respawn in " . $contract_hours_left . " hours and " . $contract_minutes_left . " minutes";
	}
	else{
		return get_user_short_target($userid);
	}
}

 ?>