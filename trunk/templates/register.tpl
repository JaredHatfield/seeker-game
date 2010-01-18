{**
 * Project:     Seeker
 * File:        register.tpl
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
<h2>Register</h2>
<FORM action="./index.php?page=process" method="post">
	<P>
	Username: <INPUT type="text" name="uname"><br />
	Password: <INPUT type="password" name="passwd1"><br />
	Password Again: <INPUT type="password" name="passwd2"><br />
	Full Name: <INPUT type="text" name="fname"><br />
	Email Address: <INPUT type="text" name="email"><br />
	{$recaptcha}<br />	
	{*<INPUT type="hidden" name="key" value="{php}echo secureform_add('register', 4){/php}">*}
	<input type="hidden" name="action" value="register">
	<INPUT type="submit" value="Register">
	</P>
 </FORM>
{include file="footer.tpl"}
