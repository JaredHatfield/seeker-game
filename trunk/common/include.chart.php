<?php

/**
 * Project:     Seeker
 * File:        include.chart.php
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

function get_user_target_pie_chart($userid){
	$query  = "SELECT s.`value`, COUNT(*) number FROM contract c JOIN status s ON `status` = s.id WHERE `target` = " . $userid . " ";
	$query .= "AND `status` != 1 GROUP BY `status` ORDER BY c.`status`";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[$row['value']] = $row['number'];
	}
	
	return generate_google_pie_chart_url($val);
}

function get_user_contract_pie_chart($userid){
	$query  = "SELECT s.`value`, COUNT(*) number FROM contract c JOIN status s ON `status` = s.id WHERE `assassin` = " . $userid . " ";
	$query .= "AND `status` != 1 GROUP BY `status` ORDER BY c.`status`";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[$row['value']] = $row['number'];
	}
	
	return generate_google_pie_chart_url($val);
}

function generate_google_pie_chart_url($values){
	$baseurl = 'http://chart.apis.google.com/chart?';
	$parms['cht'] = 'p3';
	$parms['chs'] = '500x200';
	$parms['chco'] = '18B14C';
	$parms['chf'] = 'bg,s,000000';
	$parms['chts'] = '18B14C,18';
	
	foreach($parms as $key => $value){
		$baseurl .= $key . "=" . $value . "&";
	}
	
	$counter = 1;
	$baseurl .= "chd=t:";
	foreach($values as $key => $value){
		$baseurl .= $value;
		if($counter++ < sizeof($values)){
			$baseurl .= ",";
		}
	}
	$baseurl .= "&";
	
	$counter = 1;
	$baseurl .= "chl=";
	foreach($values as $key => $value){
		$baseurl .= $key;
		if($counter++ < sizeof($values)){
			$baseurl .= "|";
		}
	}
	$baseurl .= "&";
	
	return $baseurl;
}

function get_date_statistics($date){
	$query  = "SELECT 1 status, count(*) number FROM contract WHERE date(assigned) = '" . $date . "' UNION ";
	$query .= "SELECT `status`, count(*) number FROM contract c WHERE `status` != 1 AND date(updated) = '" . $date . "' GROUP BY `status`";
	$result = mysql_query($query);
	$val = array();
	for($i = 1; $i <= 5; $i++){
		$val[$i] = 0;
	}
	
	while($row = mysql_fetch_assoc($result)){
		$val[$row['status']] = $row['number'];
	}
	
	return $val;
}

function get_date_chart(){
	$daycount = 20;
	$max = 0;
	$data = array();
	for($i = 0; $i < 21; $i++){
		$date = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - $daycount + $i, date("Y")));
		$data[$i] = get_date_statistics($date);
		$data[$i]['date'] = $date;
		$data[$i]['shortdate'] = date('n/j', mktime(0, 0, 0, date("m") , date("d") - $daycount + $i, date("Y")));
		
		$count = 0;
		for($j = 1; $j <= 5; $j++){
			$count += $data[$i][$j];
		}
		if($count > $max){
			$max = $count;
		}
	}
	
	$baseurl = 'http://chart.apis.google.com/chart?';
	$parms['cht'] = 'bvs';
	$parms['chs'] = '600x300';
	$parms['chf'] = 'bg,s,000000';
	$parms['chco'] = '18B14C,46C170,74D194,A3E0B8,D1F0DC';
	$parms['chdl'] = 'Assigned|Succeeded|Expired|Failed|Missed';
	$parms['chdlp'] = 't';
	$parms['chds'] = '0,' . $max;
	$parms['chxt'] = 'x,y';
	
	foreach($parms as $key => $value){
		$baseurl .= $key . "=" . $value . "&";
	}
	
	$baseurl .= "chd=t:";
	for($i = 1; $i <= 5; $i++){
		for($j = 0; $j < sizeof($data); $j++){
			$baseurl .= $data[$j][$i];
			if(($j + 1) < sizeof($data)){
				$baseurl .= ",";
			}
		}
		if($i < 5){
			$baseurl .= "|";
		}
	}
	$baseurl .= "&chxl=1:|0|" . floor($max/3) . "|" . floor(2*$max/3) . "|" . $max . "|0:|";
	
	for($i = 0; $i < sizeof($data); $i++){
		$baseurl .= $data[$i]['shortdate'];
		if(($i + 1) < sizeof($data)){
			$baseurl .= "|";
		}
	}
	
	return $baseurl;
}










?>