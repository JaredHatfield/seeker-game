<?php

/**
 * Project:     Seeker
 * File:        sitemap.xml.php
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
require_once('./libs/Smarty.class.php');
require_once('./recaptcha/recaptchalib.php');
include_once("./configs/config.php");
include_once("./common/include.index.php");

// Smarty
$smarty = new Smarty;
$smarty->compile_check = true;
//$smarty->debugging = true;


$query = "SELECT `id`, `uupdated` FROM users;";
$result = mysql_query($query);
$val = array();
$i = 0;
while($row = mysql_fetch_assoc($result)){
	$val[$i]['id'] = $row['id'];
	$val[$i]['lastmod'] = date('c',strtotime($row['uupdated']));
	$i++;
}
$smarty->assign("urls", $val);

$url = "http://" . $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
$url = str_replace("sitemap.xml.php","index.php?page=user&id=", $url);
$smarty->assign("url", $url);

$smarty->display('sitemap.tpl');
?>