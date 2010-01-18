{**
 * Project:     Seeker
 * File:        current_contract.tpl
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
<h2>Current Contract</h2>
{if $contract_id eq -1}
    <h3>You have no contract assigned to you at the moment.</h3>
{else}
	<b>Current Target:</b> {$contract_info.target_name}<br />
	<b>Contract Issued:</b> {$contract_info.assigned}<br />
	<b>Contract Expires:</b> {$contract_info.expiration}<br />
	<b>Time Remaining:</b> {$contract_hours_left} hours and {$contract_minutes_left} minutes<br />
	<b>Secret: 
	<FORM action="./index.php?page=process" method="post">
		<INPUT type="text" name="secret">
		<input type="hidden" name="contract_id" value="{$contract_id}">
		{*<INPUT type="hidden" name="key" value="{php}echo secureform_add('killtarget', 4){/php}">*}
		<input type="hidden" name="action" value="killtarget">
		<INPUT type="submit" value="Send">
	</FORM>
{/if}

{include file="footer.tpl"}
