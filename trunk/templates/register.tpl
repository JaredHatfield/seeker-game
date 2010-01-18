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
	Game Password: <INPUT type="text" name="gpassword"><br />
	<p style="margin-left:20px; font-size:11;">This is a short phrase that is required to create an account, existing players can tell you this phrase</p>
	<p style="margin-left:20px; font-size:11;">If you do not know the game password you will not be able to register</p>
	Username: <INPUT type="text" name="uname"><br />
	<p style="margin-left:20px; font-size:11;">Your username will only be used for you to log into the web based interface</p>
	Password: <INPUT type="password" name="passwd1"><br />
	Password Again: <INPUT type="password" name="passwd2"><br />
	<p style="margin-left:20px; font-size:11;">Your password must be at least 6 characters long</p>
	Full Name: <INPUT type="text" name="fname"><br />
	<p style="margin-left:20px; font-size:11;">This is how other players will identify you in the game</p>
	Email Address: <INPUT type="text" name="email"><br />
	<p style="margin-left:20px; font-size:11;">You will receive email notifications as you play the game</p>
	{$recaptcha}<br />	
	<p style="margin-left:20px; font-size:11;">Just making sure that you are human...</p>
	{*<INPUT type="hidden" name="key" value="{php}echo secureform_add('register', 4){/php}">*}
	<input type="hidden" name="action" value="register">
	<br />
	<INPUT type="submit" value="Register">
	</P>
 </FORM>
{include file="footer.tpl"}
