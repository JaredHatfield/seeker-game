<?php

/**
 * Project:     Seeker
 * File:        include.news.php
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

function post_news_item($message){
	$query = "INSERT INTO news (`message`, `timestamp`) VALUES ('" . $message . "', NOW());";
	$result = mysql_query($query);
}

// missed, killed, expired
function post_news_item_failed($contract_id, $type){
 	$info = get_contract_information($contract_id);
	if($type == "missed"){
		$message = "<a href=\"./index.php?page=user&id=" . $info['assassin'] . "\">" . $info['assassin_name'] . "</a> failed a contract because someone got to <a href=\"./index.php?page=user&id=" . $info['target'] . "\">" . $info['target_name'] . "</a> first.";
		post_news_item($message);
	}
	else if($type == "killed"){
		$message = "<a href=\"./index.php?page=user&id=" . $info['assassin'] . "\">" . $info['assassin_name'] . "</a> was found and the contract on <a href=\"./index.php?page=user&id=" . $info['target'] . "\">" . $info['target_name'] . "</a> was nullified.";
		post_news_item($message);
	}
	else if($type == "expired"){
		$message = "<a href=\"./index.php?page=user&id=" . $info['assassin'] . "\">" . $info['assassin_name'] . "</a> failed a contract because <a href=\"./index.php?page=user&id=" . $info['target'] . "\">" . $info['target_name'] . "</a> managed to avoid them for the length of the contract.";
		post_news_item($message);
	}
}

function post_news_status_change($userid, $active){
	$info = get_user_information($userid);
	
	if($active){
		// User switched to active
		$message = "<a href=\"./index.php?page=user&id=" . $info['id'] . "\">" . $info['name'] . "</a> switched to active.";
		post_news_item($message);
	}
	else{
		// User switched to inactive
		$message = "<a href=\"./index.php?page=user&id=" . $info['id'] . "\">" . $info['name'] . "</a> switched to inactive.";
		post_news_item($message);
	}
}

function post_news_automatic_inactive($userid){
	$info = get_user_information($userid);
	$message = "<a href=\"./index.php?page=user&id=" . $info['id'] . "\">" . $info['name'] . "</a> has not been playing and was automatically switched to inactive.";
	post_news_item($message);
}

function get_recent_news_items(){
	global $_CONFIG;
	$query = "SELECT `id`, `message`, `timestamp` FROM news ORDER BY `timestamp` DESC, `id` DESC LIMIT " . $_CONFIG['newslength'] . ";";
	$result = mysql_query($query);
	$val = array();
	while($row = mysql_fetch_assoc($result)){
		$val[] = $row;
	}
	
	for($i = 0; $i < sizeof($val); $i++){
		$val[$i]['message'] = prett_up_news_url($val[$i]['message']);
	}
	
	return $val;
}


function prett_up_news_url($url){
	global $_CONFIG;
	// Replace the ugly urls with nice looking ones
	return preg_replace('/.\/index.php\?page=user&id=(\d+)/', $_CONFIG['url'] . 'user/$1/', $url);
	
}


?>