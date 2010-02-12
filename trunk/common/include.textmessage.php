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
 
function send_message_to_user($userid, $message, $notes){
	global $_CONFIG;
	
	if($_CONFIG['sendtext']){
		$http_date = gmdate( DATE_RFC822 );
		$parameters = "user_id=" . $userid . "&body=".urlencode($message);
		$canonical_string = $_CONFIG['zeep_api'] . $http_date . $parameters;
		$b64_mac = base64_encode(hash_hmac("sha1", $canonical_string,$_CONFIG['zeep_secret'], TRUE));
		$authentication = "Zeep " . $_CONFIG['zeep_api'] . ":$b64_mac";
		
		$header = array(
		  "Authorization: ".$authentication,
		  "Date: ".$http_date,
		  "Content-Type: application/x-www-form-urlencoded",
		  "Content-Length: " . strval(strlen($parameters))
		);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $_CONFIG['zeep_api_url'] );
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters );
		$response = curl_exec($ch);
		curl_close($ch);
	}
	else{
		$response = "Message not sent based on application configuration.";
	}
	
	$query = "INSERT INTO zeep_out (`uid`, `message`, `response`, `notes`, `time`) VALUES(" . $userid . ", '" . mysql_real_escape_string($message) . "', '" . mysql_real_escape_string($response) . "', '" . mysql_real_escape_string($notes) . "', NOW());";
	$result = mysql_query($query);
	
	if($response == "OK"){
		return true;
	}
	else{
		return false;
	}
}
 
function insert_zeep_mo($uid, $body, $return){
	$query = "INSERT INTO zeep_mo (`uid`, `body`, `return`, `time`) VALUES (" . $uid . ", '" . mysql_real_escape_string($body) . "', '" . mysql_real_escape_string($return) . "', NOW());";
	$result = mysql_query($query);
}

function insert_zeep_sub($uid, $min, $return){
	$query = "INSERT INTO zeep_sub (`uid`, `min`, `return, `time`) VALUES(" . $uid . ", '" . mysql_real_escape_string($min) . "', '" . mysql_real_escape_string($return) . "', NOW());";
	$result = mysql_query($query);
}

function get_user_short_score($userid){
	$query = "SELECT COUNT(*) number FROM contract WHERE `assassin` = " . $userid . " AND `status` = 2;";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	$successful = $row[0];
	$query = "SELECT COUNT(*) number FROM contract WHERE `assassin` = " . $userid . ";";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	$total = $row[0];
	return "You have successfully completed " . $successful . " of " . $total . " contracts.";
}

function get_user_short_target($userid){
	$contracts = get_user_contract_id($userid);
	if($contracts != -1){
		$contract_info = get_contract_information($contracts);
		$contract_hours_left = floor($contract_info['seconds_remaining']/60/60);
		$contract_minutes_left =  floor(($contract_info['seconds_remaining'] - $contract_hours_left*60*60)/60);
		return "Your target is " . $contract_info['target_name'] . " and you have " . $contract_hours_left . " hours and " . $contract_minutes_left . " minutes left on your contract.";
	}
	else{
		return "You have no active contracts at the moment.";
	}
}

function get_user_short_status($userid){
	$user_info = get_user_information($userid);
	if($user_info['status'] == 0){
		$diff = $user_info['spawn_unix'] - date(time());
		$contract_hours_left = floor($diff/60/60);
		$contract_minutes_left =  floor(($diff - $contract_hours_left*60*60)/60);
		return "You will respawn in " . $contract_hours_left . " hours and " . $contract_minutes_left . " minutes.";
	}
	else{
		return get_user_short_target($userid);
	}
}

 ?>