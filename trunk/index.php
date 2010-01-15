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


$smarty = new Smarty;
$smarty->compile_check = true;
//$smarty->debugging = true;
$smarty->assign("pagename", "");

if(!isset($_GET['page'])){
	// Main page
	$smarty->display('index.tpl');
}
else if($_GET['page'] == "process"){
	// This is special, this is where stuff is actually executed and then redirected
	print_r($_POST);
}
else if($_GET['page'] == "login"){
	$smarty->display('login.tpl');
}
else if($_GET['page'] == "register"){
	$smarty->display('register.tpl');
}
else if($_GET['page'] == "listusers"){
	// other pages
	$smarty->assign("all_users", get_active_users());
	$smarty->display('listusers.tpl');
}
else if($_GET['page'] == "current_contract"){
	$contract_id = get_user_contract_id(2); // TODO: The user's id should be passed to this when they are logged in
	$smarty->assign("contract_id", $contract_id);
	if($contract_id != -1){
		$contract_info = get_contract_information($contract_id);
		$contract_hours_left = floor($contract_info['seconds_remaining']/60/60);
		$contract_minutes_left =  ceil(($contract_info['seconds_remaining'] - $contract_hours_left*60*60)/60);
		$smarty->assign("contract_hours_left", $contract_hours_left);
		$smarty->assign("contract_minutes_left", $contract_minutes_left);
		$smarty->assign("contract_info",$contract_info);
	}
	$smarty->display('current_contract.tpl');
}
else if($_GET['page'] == "test"){
	// Debuging information here
}
else{
	$smarty->display('notfound.tpl');
}




?>