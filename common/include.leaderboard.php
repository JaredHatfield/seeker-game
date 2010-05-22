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
 * along with Seeker.  If not, see 
 * http://www.gnu.org/licenses/.
 *
 * @link http://code.google.com/p/seeker-game/
 * @copyright 2010 Speed School Student Council
 * @author Jared Hatfield
 * @package seeker-game
 * @version 1.0
 */


function add_leaderboard_places($list){
	$place = 1;
	for($i = 0; $i < sizeof($list); $i++){
		if($i > 0 && $list[$i]['number'] < $list[$i - 1]['number']){
			$place = $i + 1;
		}
		$list[$i]['position'] = $place;
	}
	return $list;
}

function get_all_time_leaderboard(){
	$query  = "SELECT `assassin` id, u.`name`, COUNT(*) number FROM contract c JOIN users u ON c.`assassin` = u.`id` ";
	$query .= "WHERE `status` = 2 GROUP BY `assassin` ORDER BY COUNT(*) DESC, u.`name` ASC;";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row;
	}
	return add_leaderboard_places($val);
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
	return add_leaderboard_places($val);
}

function get_current_month(){
	$val['year'] = date("Y");
	$val['month'] = date("n");
	return $val;
}

function get_month_date($year, $month){
	$val['start'] = date("Y-m-d H:i:s", mktime(0, 0, 0, $month, 1, $year));
	$val['end'] = date("Y-m-d H:i:s", mktime(0, 0, 0, $month + 1, 1, $year));
	
	if($month == 12){
		$val['end'] = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, $year+1));
	}
	
	return $val;
}

function get_previous_month($year, $month){
	$val['year'] = $year;
	$val['month'] = $month;
	
	if($month == 1){
		$val['year'] = $year - 1;
		$val['month'] = 12;
	}
	else{
		$val['year'] = $year;
		$val['month'] = $month - 1;
	}
	
	return $val;
}

function get_next_month($year, $month){
	$val['year'] = $year;
	$val['month'] = $month;
	
	if($month == 12){
		$val['year'] = $year + 1;
		$val['month'] = 1;
	}
	else{
		$val['year'] = $year;
		$val['month'] = $month + 1;
	}
	
	return $val;
}

function get_current_semester(){
	$val['year'] = date("Y");
	$val['number'] = "";
	$currentmonth = date("n");
	
	if($currentmonth < 5){
		$val['number'] = 1;
	}
	else if($currentmonth < 9){
		$val['number'] = 2;
	}
	else{
		$val['number'] = 3;
	}
	
	return $val;
}

function get_semester_date($year, $number){
	$val['start'] = "";
	$val['end'] = "";
	
	if($number == 1){
		// Spring
		$val['start'] = $year . "-01-01 00:00:00";
		$val['end']   = $year . "-05-01 00:00:00";
	}
	else if($number  == 2){
		// Summer
		$val['start'] = $year . "-05-01 00:00:00";
		$val['end']   = $year . "-09-01 00:00:00";
	}
	else if($number == 3){
		// Falls
		$val['start'] =  $year. "-09-01 00:00:00";
		$val['end']   = $year + 1 . "-01-01 00:00:00";
	}
	
	return $val;
}

function get_previous_semester($year, $number){
	$val['year'] = "";
	$val['number'] = "";
	
	if($number == 1){
		$val['year'] = $year - 1;
		$val['number'] = 3;
	}
	else if($number == 2){
		$val['year'] = $year;
		$val['number'] = 1;
	}
	else if($number == 3){
		$val['year'] = $year;
		$val['number'] = 2;
	}
	
	return $val;
}

function get_next_semester($year, $number){
	$val['year'] = "";
	$val['number'] = "";
	
	if($number == 1){
		$val['year'] = $year;
		$val['number'] = 2;
	}
	else if($number == 2){
		$val['year'] = $year;
		$val['number'] = 3;
	}
	else if($number == 3){
		$val['year'] = $year + 1;
		$val['number'] = 1;
	}
	
	return $val;
}

function get_leaderboard_for_range($start, $end){
	$query  = "SELECT `assassin` id, u.`name`, COUNT(*) number FROM contract c JOIN users u ON c.`assassin` = u.`id` ";
	$query .= "WHERE `status` = 2 AND c.`updated` > '" . $start . "' AND c.`updated` < '" . $end . "' ";
	$query .= "GROUP BY `assassin` ORDER BY COUNT(*) DESC, u.`name` ASC;";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row;
	}
	return add_leaderboard_places($val);
}

?>