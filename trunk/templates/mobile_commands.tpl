{**
 * Project:     Seeker
 * File:        mobile_commands.tpl
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

<h2>Playing Seeker from a Mobile Phone</h2>
Seeker can be played entirely by using text messages.  After you register you have the option of linking your cell phone number to your seeker account.  While the game can be played using email and the website, the ability to play via text messages is an attempt to make the game easier to play more fun for everyone.<br /><br />
<b>How to use:</b> Send a text message to the shortcode 88147.  All messages must start with the word "seeker" to indicate they are for this website.<br /><br />

<h2>List of Mobile Commands</h2>

<table>
	<tr bgcolor="#cccccc" style="font-weight : bold;">
		<td>Command</td>
		<td>What this command does</td>
		<td>Example</td>
	</tr>
	<tr bgcolor="#eeeeee">
		<td>target</td>
		<td>Responds with your current target if you have one</td>
		<td>seeker target</td>
	</tr>
	<tr bgcolor="#dddddd">
		<td>secret</td>
		<td>Responds with your current secret</td>
		<td>seeker secert</td>
	</tr>
	<tr bgcolor="#eeeeee">
		<td>confirm</td>
		<td>Attempt to complete a target by providing a secert</td>
		<td>seeker confirm {$sample_secert}</td>
	</tr>
	<tr bgcolor="#dddddd">
		<td>status</td>
		<td>Responds with your current status in the game</td>
		<td>seeker status</td>
	</tr>
	<tr bgcolor="#eeeeee">
		<td>score</td>
		<td>Responds with your lifetime contract score</td>
		<td>seeker score</td>
	</tr>
	<tr bgcolor="#dddddd">
		<td>help</td>
		<td>Responds with a list of valid commands</td>
		<td>seeker help</td>
	</tr>
</table>

{include file="footer.tpl"}
