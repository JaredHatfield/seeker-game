<?php

/**
 * Project:     Seeker
 * File:        include.mail.php
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

function send_message($to, $subject, $body){
	global $_CONFIG;
	if($_CONFIG['sendemail']){
		mail($to, $subject, $body);
	}
	$query = "INSERT INTO mail (`to`, `subject`, `body`, `when`) VALUES(\"" . mysql_real_escape_string($to);
	$query .= "\", \"" . mysql_real_escape_string($subject) . "\", \"" . mysql_real_escape_string($body) . "\", NOW());";
	$result = mysql_query($query);
}

function send_welcome_email($userid){
	$info = get_user_information($userid);
	$subject = "[Seeker] Welcome to the Game";
	$to = $info['email'];
	$body  = $info['name'] . ",\n\n" . "Welcome to Seeker.  This email is just the notification that you have registered.  ";
	$body .= "You will receive an email when you are issued a contract.\n\n";
	$body .= "Your secret is " . $info['secret'] . " and you will give this to someone that eliminates you, keep this handy.  ";
	$body .= "When you are eliminated your secret will change.\n\n";
	$body .= "To eliminate your contract you can enter your contracts secret on the website or via text message\n\n";
	$body .= "Good luck!";
	send_message($to, $subject, $body);
}

function send_contract_notification($contract_id){
	$info = get_contract_information($contract_id);
	$hours = ceil($info['seconds_remaining']/60/60);
	$subject = "[Seeker] New Contract Issued";
	$to = $info['assassin_email'];
	$body = $info['assassin_name'] . ",\n\n" . "You have been assigned a new contract.  Your target's name is " . $info['target_name'];
	$body .= ".  You have just under " . $hours . " hours to complete your objective before the contract expires.\n\n Good luck!";
	send_message($to, $subject, $body);
}

function send_contract_success($contract_id){
	$info = get_contract_information($contract_id);
	$subject = "[Seeker] Successfully Completed Contract!";
	$to = $info['assassin_email'];
	$body = $info['assassin_name'] . ",\n\n" . "Congratulations!  You have successfully completed the following contract:\n\n";
	$body .= "Target: " . $info['target_name'] . "\n";
	$body .= "Issued: " . $info['assigned'] . "\n";
	$body .= "Completed: " . $info['updated'] . "\n";
	send_message($to, $subject, $body);
}

function send_contract_missed($contract_id, $actual_assassin_id){
	$info = get_contract_information($contract_id);
	$actual_assassin = get_user_information($actual_assassin_id);
	$subject = "[Seeker] You Missed Your Target";
	$to = $info['assassin_email'];
	$body = $info['assassin_name'] . ",\n\n" . "You have failed following contract:\n\n";
	$body .= "Target: " . $info['target_name'] . "\n";
	$body .= "Issued: " . $info['assigned'] . "\n";
	$body .= "Failed: " . $info['updated'] . "\n\n\n";
	$body .= $actual_assassin['name'] . " made it to the target before you did.  Better luck next time.";
	send_message($to, $subject, $body);
}


function send_contract_failed_by_death($user_id, $actual_assassin_id, $contract_id){
	$user_info = get_user_information($user_id);
	$actual_assassin = get_user_information($actual_assassin_id);
	$subject = "[Seeker] You Have Been Eliminated!";
	$to = $user_info['email'];
	$body = $user_info['name'] . ",\n\n" . "You have been eliminated by " . $actual_assassin['name'] . "!\n\n";
	$body .= "Your respawn time has been set for " . $user_info['spawn'] . " at which time you will be able to get a new contract.\n\n";
	if($contract_id != -1){
		$info = get_contract_information($contract_id);
		$body .= "Additionally, you have failed to complete your contract on " . $info['target_name'] . ".\n\n";
	}
	$body .= "As a result of your elimination, your secret has been changed to " . $user_info['secret'] . ".";
	send_message($to, $subject, $body);
}



?>