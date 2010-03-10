<?php

/**
 * Project:     Seeker
 * File:        cron.php
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
 * along with Seeker.  If not, see 
 * http://www.gnu.org/licenses/.
 *
 * @link http://code.google.com/p/seeker-game/
 * @copyright 2010 Speed School Student Council
 * @author Jared Hatfield
 * @package seeker-game
 * @version 1.0
 */

include_once("./configs/config.php");
require_once($_CONFIG['smarty']);
require_once("./recaptcha/recaptchalib.php");
include_once("./common/include.index.php");
include_once("./common/include.cron.php");

// Lazy cron to expire contracts
expire_contracts();


if(contract_can_be_issued()){
	automatic_user_deactivate();
	assign_new_contracts();
}



?>