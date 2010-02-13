<?php

/**
 * Project:     Seeker
 * File:        include.leaderboard.php
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


function get_all_time_leaderboard(){
	$query  = "SELECT `assassin` id, u.`name`, COUNT(*) number FROM contract c JOIN users u ON c.`assassin` = u.`id` ";
	$query .= "WHERE `status` = 2 GROUP BY `assassin` ORDER BY COUNT(*) DESC, u.`name` ASC;";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row;
	}
	return $val;
}

function get_leaderboard_past_days($numberofdays){
	$query  = "SELECT `assassin` id, u.`name`, COUNT(*) number FROM contract c JOIN users u ON c.`assassin` = u.`id` ";
	$query .= "WHERE `status` = 2 AND ADDDATE(c.`updated`, INTERVAL " . $numberofdays . " DAY) > NOW() ";
	$query .= "GROUP BY `assassin` ORDER BY COUNT(*) DESC, u.`name` ASC;";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row;
	}
	return $val;
}

function get_leaderboard_current_semester(){
	$currentmonth = date("n");
	
	if($currentmonth < 5){
		// Spring
		$start = date("Y") . "-01-01 00:00:00";
		$end   = date("Y") . "-05-01 00:00:00";
	}
	else if($currentmonth < 9){
		// Summer
		$start = date("Y") . "-05-01 00:00:00";
		$end   = date("Y") . "-09-01 00:00:00";
	}
	else{
		// Falls
		$start = date("Y") . "-09-01 00:00:00";
		$end   = (date("Y")+1) . "-01-01 00:00:00";
	}
	
	$query  = "SELECT `assassin` id, u.`name`, COUNT(*) number FROM contract c JOIN users u ON c.`assassin` = u.`id` ";
	$query .= "WHERE `status` = 2 AND c.`updated` > '" . $start . "' AND c.`updated` < '" . $end . "' ";
	$query .= "GROUP BY `assassin` ORDER BY COUNT(*) DESC, u.`name` ASC;";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row;
	}
	return $val;
}

?>