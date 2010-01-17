<?php

/**
 * Project:     Seeker
 * File:        include.cron.php
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


function contract_can_be_issued(){
	// In the settings we contracts should be allowed to be issued
	$query = "SELECT `value` FROM config WHERE `name` = 'open';";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	$open = $row[0];
	
	// Contracts are only issued between the hours of 9 AM and 9 PM
	$query = "SELECT IF(DATE_ADD(DATE(NOW()), INTERVAL 9 HOUR) < NOW(),IF(DATE_ADD(DATE(NOW()), INTERVAL 21 HOUR) > NOW(),1,0),0) live;";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	$time = $row[0];
	
	// Contracts are only issued if they were not issued in the pay 55 minutes
	$query = "SELECT IF(TIME_TO_SEC(TIMEDIFF(NOW(),MAX(assigned)))>3300,1,0) valid FROM contract;";
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	$past = $row[0];
	
	if($open == "yes" && $time == "1" && $past == "1"){
		return true;
	}
	else{
		return false;
	}
}


?>