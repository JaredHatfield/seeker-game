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

function send_contract_notification($contract_id){
	global $_CONFIG;
	if($_CONFIG['sendemail']){
		$info = get_contract_information($contract_id);
		$hours = ceil($info['seconds_remaining']/60/60);
		$subject = "[Seeker] New Contract for " . $info['assassin_name'];
		$to = $info['assassin_email'];
		$body = $info['assassin_name'] . ",\n\n" . "You have been assigned a new contract.  Your target's name is " . $info['target_name'];
		$body .= ".  You have just under " . $hours . " hours to complete your objective before the contract expires.\n\n Good luck!";
		
		
		echo $subject . "<br/>" . $to . "<br/>" . $body . "<br/><br/>";
		
		// TODO: add the code to send the actual email;
	}
}



?>