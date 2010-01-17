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

$_CONFIG['host'] = 'localhost';
$_CONFIG['database'] = 'seeker';
$_CONFIG['username'] = 'username';
$_CONFIG['password'] = 'password';

$_CONFIG['respawntime'] = 24;
$_CONFIG['contractlength'] = 72;
$_CONFIG['contracthistoryrestriction'] = 1;

$_CONFIG['sendemail'] = TRUE;

$_CONFIG['secretlength'] = 4;
$_CONFIG['secretalphabet'] = "23456789bcdfghjkmnpqrstvwxyz"; 

$_CONFIG['newslength'] = 10;

// These should be two prime numbers used to obscure the users ids where necessary to avoid possible hacking
// It is recommended that these number be changed for security uprposes
$_CONFIG['obscure_top'] = 4507; // This number should be in the thousands ~4000 - 5000
$_CONFIG['obscure_bottom'] = 4583 ; // This number should be in the thousands ~4000 - 5000

// http://www.zeepmobile.com
$_CONFIG['zeep_api'] = "";
$_CONFIG['zeep_secret'] = "";

// http://recaptcha.net/
$_CONFIG['recaptcha_public'] = "";
$_CONFIG['recaptcha_private'] = "";

?>