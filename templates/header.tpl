{**
 * Project:     Seeker
 * File:        header.tpl
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
 *}
<html>
<head>
	<title>{$title} - {$pagename}</title>
	<link rel="stylesheet" type="text/css" href="{$baseurl}static/style.css" />
	{if isset($xfn) && $contracts|@count > 0}
	{section name=mysec loop=$xfn}
	<link href='{$xfn[mysec].link}' rel='{$xfn[mysec].rel}' type='text/html'/> 
	{/section}
	{/if}
</head>
<body bgcolor="#ffffff">
<table class="maintable" align="center">
	<tr class="header">
		<td>
			<img src="{$baseurl}static/header.png" width=500 height=150 style=" class="display" />
		</td>
	</tr>
	<tr>
		<td>
			<center>
			<a href="{$baseurl}home">Home</a> | <a href="{$baseurl}listusers">List of Players</a> | <a href="{$baseurl}leaderboard/thissemester">Leaderboard</a> | <a href="{$baseurl}mobile_commands">Mobile Commands</a>
			<br />
			{if $logged_in eq 0}
				<a href="./index.php?page=login">Login</a> | <a href="./index.php?page=register">Register</a>
			{else}
				Welcome, {$user_name} | <a href="./index.php?page=current_contract">Current Contract</a> | <a href="./index.php?page=myaccount">My Account</a> | <a href="./index.php?page=logoff">Log off</a>
			{/if}
			</center>
		</td>
	</tr>
	<tr>
		<td>
