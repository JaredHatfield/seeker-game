{**
 * Project:     Seeker
 * File:        login.tpl
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
<h3>Login</h3>
<FORM action="./index.php?page=process" method="post">
	<P>
	Username: <INPUT type="text" name="uname"><br />
	Password: <INPUT type="password" name="passwd"><br />
	{*<INPUT type="hidden" name="key" value="{php}echo secureform_add('authenticate', 4){/php}">*}
	<input type="hidden" name="action" value="authenticate">
	<INPUT type="submit" value="Send">
	</P>
 </FORM>
{include file="footer.tpl"}
