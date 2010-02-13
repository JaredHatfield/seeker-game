{**
 * Project:     Seeker
 * File:        leaderboard.tpl
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
<br />
<center>
	{if $boardlink == 0}
		This Week | 
	{else}
		<a href="./index.php?page=leaderboard&thisweek">This Week</a> | 
	{/if}
	
	{if $boardlink == 1}
		This Month | 
	{else}
		<a href="./index.php?page=leaderboard&thismonth">This Month</a> | 
	{/if}
	
	{if $boardlink == 2}
		This Semester | 
	{else}
		<a href="./index.php?page=leaderboard&thissemester">This Semester</a> | 
	{/if}
	
	{if $boardlink == 3}
		All Time
	{else}
		<a href="./index.php?page=leaderboard">All Time</a>
	{/if}
</center>
<h2>{$board} Leaderboard</h2>
{if $leaderboard|@count > 0}
<table class="data">
{strip}
	<tr class="theading">
		<td style="width:100px;">Position</td>
		<td style="width:180px;">Name</td>
		<td>Successful Contracts</td>
	</tr>
{/strip}
{section name=mysec loop=$leaderboard}
{strip}
	<tr>
		<td style="text-align:center;">{$leaderboard[mysec].position}</td>
		<td><a href="./index.php?page=user&id={$leaderboard[mysec].id}">{$leaderboard[mysec].name}</a></td>
		<td style="text-align:center;">{$leaderboard[mysec].number}</td>
	</tr>
{/strip}
{/section}
</table>
{else}
<center>No Contracts have Been Completed</center>
{/if}
<br />
{include file="footer.tpl"}
