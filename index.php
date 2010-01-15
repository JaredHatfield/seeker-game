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
 * @copyright 2009 Speed School Student Council
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
else if($_GET['page'] == "listusers"){
	// other pages
	$smarty->assign("all_users", get_active_users());
	$smarty->display('listusers.tpl');
}
else if($_GET['page'] == "test"){
	// Debuging information here
	
}
else{
	$smarty->display('notfound.tpl');
}




?>