{**
 * Project:     Seeker
 * File:        myaccount.tpl
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
{include file="header.tpl" title=Seeker}

<h2>My Account</h2>
<h3>{$fullname}</h3>

<b>Your Current Secret:</b> {$secret}<br /><br />
<b>Current Status:</b>
{if $active eq 1}
	Active / Accepting contracts  
	<FORM action="./index.php?page=process" method="post">
		{*<INPUT type="hidden" name="key" value="{php}echo secureform_add('togglestatus', 4){/php}">*}
		<input type="hidden" name="action" value="togglestatus">
		<INPUT type="submit" value="Change Acount Status to Inactive">
	</FORM>
	<br />
	<p style="margin-left:20px; font-size:11;">Note: If you change your status to inactive you will need to wait {$inactivedelay} hours before you can rejoin the game.  If you decided to become inactive you will no longer receive new contracts and no new contracts will be issued with you as the target.  However, you existing contract and all existing contracts with you as the target will still be eligible for completion, otherwise they will expire when their deadline is reached.  You should change your status to inactive if you will not be able to play Seeker for a period of time.</p>
{else}
	Inactive / Not accepting contracts  
	{if $isinactivedelay}
		<FORM action="./index.php?page=process" method="post">
			{*<INPUT type="hidden" name="key" value="{php}echo secureform_add('togglestatus', 4){/php}">*}
			<input type="hidden" name="action" value="togglestatus">
			<INPUT type="submit" value="Change Account Status to Active">
		</FORM>
	{else}
		<br />
		<p style="margin-left:20px; font-size:11;">You must wait for {$inactivetimeleft} before you can become active again.</p>
	{/if}
{/if}
<br /><br />

<b>Manage Phone Settings:</b> <a href="./index.php?page=register_zeep">Zeepmobile Settings</a>

<h2>New User Registration</h2>

In order for new players to register, they are required to enter a game password.  Existing players, such as you, are able to access that password here.
<br /><br />

<b>Game Password:</b> {$gamepassword}
<br /><br />


{include file="footer.tpl"}
