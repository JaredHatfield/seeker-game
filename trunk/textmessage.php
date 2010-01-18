<?php

/**
 * Project:     Seeker
 * File:        textmessage.php
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

include_once("./configs/config.php");
include_once("./common/include.index.php");

// Lazy cron to expire contracts
expire_contracts();

// Correct header
header("content-type: text/plain");


$event = trim($_POST['event']);

// Verify that the request is from the correct source
if(!isset($_GET['id'])){
	echo "The server could not authenticate your request.";
	exit();
}
else if($_GET['id'] != $_CONFIG['zeep_custom_authenticator']){
	echo "The server could not authenticate your request.";
	exit();
}


switch ($event){
	case 'SUBSCRIPTION_UPDATE':
		$userid = intval($_POST['uid']);
		$min = mysql_escape_string(trim($_POST['min']));

		// The user is registering
		$message = "You have successfully linked your phone to your account.\n";
		insert_zeep_sub($userid, $min, $message);
		echo $message;
		break;

	case 'MO':
		$userid = intval($_POST['uid']);
		$msg = mysql_escape_string(trim($_POST['body']));

		// User query
		$message = "";
		$parameters = explode(" ", $msg);
		
		if(trim(strtolower($msg)) == "target"){
			$message = get_user_short_target($userid);
		}
		else if(trim(strtolower($msg)) == "secret"){
			$user_info = get_user_information($userid);
			$message = "Your secret: " . $user_info['secret'];
		}
		else if(trim(strtolower($msg)) == "status"){
			$message = get_user_short_status($userid);
		}
		else if(trim(strtolower($msg)) == "score"){
			$message = get_user_short_score($userid);
		}
		else if(strtolower($parameters[0]) == "confirm"){
			$contracts = get_user_contract_id($userid);
			if($contracts != -1){
				$result = kill_attempt($contracts, $parameters[1], "textmessage");
				if($result){
					$message = "Your contract has been completed successfully.";
				}
				else{
					$message = "The code you entered was invalid.";
				}
			}
			else{
				$message = "You have no outstanding contracts.";
			}
		}
		else if(trim(strtolower($msg)) == "help"){
			$samplepass = generate_secret();
			$hiddenpass = "";
			for($i = 0; $i < strlen($samplepass); $i++){
				$hiddenpass .= "X";
			}
			$message = "Valid Commands: target, secret, confirm " . $hiddenpass . ", status, score";
		}
		else{
			$message = "You have entered an invalid command.  Reply with help for a list of commands.";
		}
		insert_zeep_mo($userid, $msg, $message);
		echo $message;
		break;
}

?>
