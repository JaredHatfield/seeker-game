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
{literal}
<style type="text/css">
	body
	{
		background-color:black;
		color: green;
		font-family: 'Courier New', monospace;
	}
	
	img{
		border:none;
	}
	
	a{
		text-decoration: none;
		color: white;
	}

	form{
		display: inline;
	}
	
	table{
		width: 100%;
		//border: 5px dotted green;
	}
	
	h2{
		border-bottom: 3px dotted green;
		padding: 10px;
		text-align: center;
	}
	table.maintable{
		width: 800px;
		//border-width: 1px;
		border-spacing: 10px;
		//border-style: outset;
	}
	
	table.maintable tr.header{
		//background-color: lightblue;
		
	}
	
	table.maintable tr.header h1{
		text-align:center;
		font-weight:bold;
		border-bottom: 3px solid green;
		padding: 10px;
		font-size:50;
	}
	
	table.data td{
		border-bottom: green dotted 1px;
		padding: 5px;
		font-size:12;
	}
	table.data tr.theading{
		text-align:center;
	}
	input { 
		color:green; 
		font-family: 'Courier New', monospace;
		font-weight: bold;
		font-size:16;
		background-color:#1F1F1F; 
		border:3px solid; 
		border-color: #696 #363 #363 #696; 
	} 
</style>
{/literal}
</head>
<body bgcolor="#ffffff">
<table class="maintable" align="center">
	<tr class="header">
		<td><h1>SEEKER</h1></td>
	</tr>
	<tr>
		<td>
			<center>
			<a href="./index.php">Home</a> | <a href="./index.php?page=listusers">List of Players</a> | <a href="./index.php?page=mobile_commands">Mobile Commands</a>
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
