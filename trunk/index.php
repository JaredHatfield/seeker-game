<?php

/**
 * Project:     Seeker
 * File:        index.php
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

require './libs/Smarty.class.php';
include_once("./configs/config.php");
include_once("./common/include.index.php");

// Lazy cron to expire contracts
expire_contracts();

// Smarty
$smarty = new Smarty;
$smarty->compile_check = true;
//$smarty->debugging = true;
$smarty->assign("pagename", "");


// User management
if(isset($_SESSION['userid']) && $_SESSION['userid'] != -1){
	$current_user = get_user_information($_SESSION['userid']);
	$smarty->assign("logged_in", 1);
	$smarty->assign("user_name", $current_user['name']);
}
else{
	$smarty->assign("logged_in", 0);
}

// Process the page
if(!isset($_GET['page'])){
	// Main page
	$smarty->assign("open_contract_count", get_open_contract_count());
	$smarty->assign("successful_contract_count", get_successful_contract_count());
	$smarty->assign("total_contract_count", get_total_contract_count());
	$smarty->assign("active_member_count", get_active_member_count());
	$smarty->assign("live_member_count", get_live_member_count());
	$smarty->assign("news", get_recent_news_items());
	$smarty->display('index.tpl');
}
else if($_GET['page'] == "logoff"){
	$_SESSION['userid'] = -1;
	$smarty->assign("url","./index.php");
	$smarty->display('redirect.tpl');
	exit();
}
else if($_GET['page'] == "process"){
	// This is special, this is where stuff is actually executed and then redirected
	if(!isset($_POST['action'])){
		$smarty->assign("url","./index.php");
		$smarty->display('redirect.tpl');
		exit();
	}
	
	$action = mysql_real_escape_string($_POST['action']);
	
	// Process the input
	if($action == "login"){
		if(authenticate(mysql_real_escape_string($_POST['uname']), mysql_real_escape_string($_POST['passwd']))){
			// The user is logged in
			$_SESSION['userid'] = get_user_id(mysql_real_escape_string($_POST['uname']));
			$smarty->assign("url","./index.php");
		}
		else{
			// Incorrect username or password
			$smarty->assign("url","./index.php?page=login");
		}
		$smarty->display('redirect.tpl');
		exit();
	}
	else if($action == "register"){
		$username = mysql_real_escape_string($_POST['uname']);
		$password1 = mysql_real_escape_string($_POST['passwd1']);
		$password2 = mysql_real_escape_string($_POST['passwd2']);
		$fullname = mysql_real_escape_string($_POST['fname']);
		$emailaddress = mysql_real_escape_string($_POST['email']);
		
		if($password1 != $password2){
			$smarty->assign("message","Error: The passwords you entered did not match, try registering again.");
			$smarty->display('error.tpl');
			exit();
		}
		else if(strlen($password1) <= 6){
			$smarty->assign("message","Error: Your password must be at least 6 characters long.");
			$smarty->display('error.tpl');
			exit();
		}
		else{
			$_SESSION['userid'] = register($username, $password1, $fullname, $emailaddress);
			
			// TODO: Send a welcome email to the user
			
			$smarty->assign("url","./index.php");
			$smarty->display('redirect.tpl');
			exit();
		}
	}
	else if($action == "killtarget"){
		$outcome = kill_attempt(mysql_real_escape_string($_POST['contract_id']), mysql_real_escape_string($_POST['secret']));
		if($outcome){
			// The kill was successful
			$smarty->assign("url","./index.php?page=process_contract&outcome=1");
		}
		else{
			// The kill was not successful
			$smarty->assign("url","./index.php?page=process_contract&outcome=0");
		}
		$smarty->display('redirect.tpl');
		exit();
	}
}
else if($_GET['page'] == "login"){
	$smarty->display('login.tpl');
}
else if($_GET['page'] == "register"){
	$smarty->display('register.tpl');
}
else if($_GET['page'] == "user"){
	$page_userid = mysql_real_escape_string($_GET['id']);
	$page_user = get_user_information($page_userid);
	$smarty->assign("fullname", $page_user['name']);
	$smarty->assign("contracts", get_users_contracts($page_userid));
	$smarty->display('user.tpl');
}
else if($_GET['page'] == "listusers"){
	$users = get_active_users();
	for($i = 0; $i < sizeof($users); $i++){
		if(date(time()) > $users[$i]['spawn']){
			$users[$i]['alive'] = 1;
		}
		else{
			$users[$i]['alive'] = 0;
			$diff = $users[$i]['spawn'] - date(time());
			$contract_hours_left = floor($diff/60/60);
			$contract_minutes_left =  floor(($diff - $contract_hours_left*60*60)/60);
			$users[$i]['delay'] = $contract_hours_left . " hours and " . $contract_minutes_left . " minutes";
		}
	}
	$smarty->assign("all_users", $users);
	$smarty->display('listusers.tpl');
}
else if($_GET['page'] == "current_contract"){
	$contract_id = get_user_contract_id($_SESSION['userid']);
	$smarty->assign("contract_id", $contract_id);
	if($contract_id != -1){
		$contract_info = get_contract_information($contract_id);
		$contract_hours_left = floor($contract_info['seconds_remaining']/60/60);
		$contract_minutes_left =  floor(($contract_info['seconds_remaining'] - $contract_hours_left*60*60)/60);
		$smarty->assign("contract_hours_left", $contract_hours_left);
		$smarty->assign("contract_minutes_left", $contract_minutes_left);
		$smarty->assign("contract_info",$contract_info);
	}
	$smarty->display('current_contract.tpl');
}
else if($_GET['page'] == "process_contract"){
	if(isset($_GET['outcome']) && $_GET['outcome'] == "1"){
		$smarty->assign("success", 1);
	}
	else{
		$smarty->assign("success", 2);
	}
	$smarty->display('process_contract.tpl');
}
else if($_GET['page'] == "test"){
	// Debuging information here
	assign_new_contracts();
}
else{
	$smarty->display('notfound.tpl');
}




?>