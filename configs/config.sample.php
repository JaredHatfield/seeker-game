<?php

/**
 * Project:     Seeker
 * File:        config.php
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
 */
 
/****************************************************************************
 * INSTRUCTIONS
 * 
 * Fill in the following information and then rename this file to config.php
 * 
 ****************************************************************************/

// Database Settings
$_CONFIG['host'] = 'localhost';
$_CONFIG['database'] = 'seeker';
$_CONFIG['username'] = 'username';
$_CONFIG['password'] = 'password';

// Game Play Settings
$_CONFIG['respawntime'] = 24;
$_CONFIG['contractlength'] = 72;

// Message Settings
$_CONFIG['sendemail'] = TRUE;
$_CONFIG['sendtext'] = TRUE;

// Contract Settings
$_CONFIG['secretlength'] = 4;
$_CONFIG['secretalphabet'] = "23456789bcdfghjkmnpqrstvwxyz";

// Home page settings
$_CONFIG['newslength'] = 10;

// http://www.zeepmobile.com settings
$_CONFIG['zeep_api'] = "";
$_CONFIG['zeep_secret'] = "";
$_CONFIG['zeep_api_url'] = 'https://api.zeepmobile.com/messaging/2008-07-14/send_message';
$_CONFIG['zeep_custom_authenticator'] = "ABCD"; // The callback url: http://domain.com/textmessage.php?id=ABCD

// http://recaptcha.net/ settings
$_CONFIG['recaptcha_public'] = "";
$_CONFIG['recaptcha_private'] = "";

?>