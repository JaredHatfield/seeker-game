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
{else}
	Inactive / Not accepting contracts  
	<FORM action="./index.php?page=process" method="post">
		{*<INPUT type="hidden" name="key" value="{php}echo secureform_add('togglestatus', 4){/php}">*}
		<input type="hidden" name="action" value="togglestatus">
		<INPUT type="submit" value="Change Account Status to Active">
	</FORM>
{/if}
<br /><br />

<b>Manage Phone Settings:</b> <a href="./index.php?page=register_zeep">Zeepmobile Settings</a>

{include file="footer.tpl"}
