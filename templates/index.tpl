{**
 * Project:     Seeker
 * File:        index.tpl
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

<h2>How to Play</h2>
Seeker is a online game that is played in real life.  The rules are as follows: You are given a contract lasting  <b>{$contract_length}</b> hours to find a specific person that is also playing the game.  When you find them, they give you their secret you enter it into the website or via text message to complete your contract.  Contracts are assigned randomly so more than one person could have the same target.  If someone else reaches your target before you do, you fail your current contract.  When someone completes a contract where you are the target, you lose your current contract and will need to wait for <b>{$spawn_time}</b> hours before you rejoin the game.  Contracts are given out to eligible players every hour between 10:00 AM and 10:00 PM every day.

<br />

<h2>Game Status</h2>
<ul>
	{strip}
		{if $new_contracts eq "yes"}
			<li>New contracts are being issued to players.</li>
		{else}
		<li>No new contracts are being issued to players.</li>
		{/if}
	{/strip}
	<li>There are currently <b>{$active_member_count}</b> registered players with <b>{$live_member_count}</b> players still alive.</li>
	<li>A total of <b>{$total_contract_count}</b> contracts have been issued.</li>
	<li><b>{$successful_contract_count}</b> contracts have been successfully completed by all of the players.</li>
	<li>Right now there are <b>{$open_contract_count}</b> open contracts.</li>
</ul>

<h2>News</h2>
<table class="data">
{section name=mysec loop=$news}
{strip}
	<tr>
		<td style="width: 550px;">{$news[mysec].message}</a></td>
		<td>{$news[mysec].timestamp}</a></td>
	</tr>
{/strip}
{/section}
</table>

{include file="footer.tpl"}
