{**
 * Project:     Seeker
 * File:        user.tpl
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

<h3>{$fullname}</h3>

<table>
{strip}
	<tr bgcolor="#cccccc">
		<td>Target</td>
		<td>Assigned</td>
		<td>Updated</td>
		<td>Status</td>
	</tr>
{/strip}
{section name=mysec loop=$contracts}
{strip}
	<tr bgcolor="{cycle values="#eeeeee,#dddddd"}">
		<td><a href="./index.php?page=user&id={$contracts[mysec].target}">{$contracts[mysec].target_name}</a></td>
		<td>{$contracts[mysec].assigned}</td>
		<td>{$contracts[mysec].updated}</td>
		<td>{$contracts[mysec].status_name}</td>
	</tr>
{/strip}
{/section}
</table>

{include file="footer.tpl"}
