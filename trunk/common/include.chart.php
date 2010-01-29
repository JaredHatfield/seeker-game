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

 /*

chd=t:60,40,40,40&
chl=Successful|Failed|Missed|Expired&

=&
chtt=Contract+Results&

*/

function get_user_target_pie_chart($userid){
	$query  = "SELECT s.`value`, COUNT(*) number FROM contract c JOIN status s ON `status` = s.id WHERE `target` = " . $userid . " ";
	$query .= "AND `status` != 1 GROUP BY `status` ORDER BY c.`status`";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[$row['value']] = $row['number'];
	}
	
	return "<img src=\"" . generate_google_pie_chart_url($val) . "\" />";
}

function get_user_contract_pie_chart($userid){
	$query  = "SELECT s.`value`, COUNT(*) number FROM contract c JOIN status s ON `status` = s.id WHERE `assassin` = " . $userid . " ";
	$query .= "AND `status` != 1 GROUP BY `status` ORDER BY c.`status`";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[$row['value']] = $row['number'];
	}
	
	return "<img src=\"" . generate_google_pie_chart_url($val) . "\" />";
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


?>