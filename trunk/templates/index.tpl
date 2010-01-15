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
<br />
<br />
<br />

Seeker is a online game that is played in real life.  
The concept is simple, you are given a contract to find a specific person in a given amount of time.
When you find them, you enter their secret key into the website or via text message to complete the contract.
More than one person could have the same person, and if someone else reaches your target first you fail your contract.
If someone find you, you are eliminated and lose your current contract.  
Additionally, you can not rejoin the game and get a new contract for a certain amount of time.

<br /><br />

Right now there are <b>{$active_member_count}</b> registered players with <b>{$live_member_count}</b> still alive.
<br /><br />
A total of <b>{$total_contract_count}</b> contracts have been issued.
<br /><br />
<b>{$successful_contract_count}</b> contracts have been successfully completed.
<br /><br />
Right now there are <b>{$open_contract_count}</b> open contracts.

<br />
<h2>News</h2>
<table>
{section name=mysec loop=$news}
{strip}
	<tr bgcolor="{cycle values="#eeeeee,#dddddd"}">
		<td>{$news[mysec].message}</a></td>
		<td>{$news[mysec].timestamp}</a></td>
	</tr>
{/strip}
{/section}
</table>

{include file="footer.tpl"}
