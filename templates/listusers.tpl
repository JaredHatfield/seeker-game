{**
 * Project:     Seeker
 * File:        listusers.tpl
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

<table>
{strip}
	<tr bgcolor="#cccccc">
		<td>Name</td>
		<td>Status</td>
	</tr>
{/strip}
{section name=mysec loop=$all_users}
{strip}
	<tr bgcolor="{cycle values="#eeeeee,#dddddd"}">
		<td><a href="./index.php?page=user&id={$all_users[mysec].id}">{$all_users[mysec].name}</a></td>
		<td>
			{if $all_users[mysec].alive eq 1}
				Alive
			{else}
				Respawn in {$all_users[mysec].delay}
			{/if}
		</td>
	</tr>
{/strip}
{/section}
</table>

{include file="footer.tpl"}