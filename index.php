<?php

/**
 * Project:     Seeker
 * File:        index.php
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

// Lazy cron to expire contracts
expire_contracts();

// Smarty
$smarty = new Smarty;
$smarty->compile_check = true;
//$smarty->debugging = true;
$smarty->assign("pagename", "");
$smarty->assign("baseurl", $_CONFIG['url']);


// User management
if(isset($_SESSION['userid']) && $_SESSION['userid'] != -1){
	$current_user = get_user_information($_SESSION['userid']);
	$smarty->assign("logged_in", 1);
	$smarty->assign("user_name", $current_user['name']);
}
else{
	$smarty->assign("logged_in", 0);
}

// Process the page
if(!isset($_GET['page'])){
	/*******************************************************************************************************
	 * Main page
	 ******************************************************************************************************/
	$smarty->assign("pagename", "Home");
	$smarty->assign("contract_length", $_CONFIG['contractlength']);
	$smarty->assign("spawn_time", $_CONFIG['respawntime']);
	$smarty->assign("open_contract_count", get_open_contract_count());
	$smarty->assign("successful_contract_count", get_successful_contract_count());
	$smarty->assign("total_contract_count", get_total_contract_count());
	$smarty->assign("active_member_count", get_active_member_count());
	$smarty->assign("live_member_count", get_live_member_count());
	$smarty->assign("new_contracts", get_if_contracts_are_being_issued());
	$smarty->assign("news", get_recent_news_items());
	$smarty->assign("contract_history_chart", get_date_chart());
	$smarty->display('index.tpl');
}
else if($_GET['page'] == "logoff"){
	/*******************************************************************************************************
	 * Log off
	 ******************************************************************************************************/
	$_SESSION['userid'] = -1;
	$smarty->assign("url","./index.php");
	$smarty->display('redirect.tpl');
	exit();
}
else if($_GET['page'] == "process"){
	/*******************************************************************************************************
	 * Process a submitted form
	 ******************************************************************************************************/
	$smarty->assign("pagename", "Processing...");
	// This is special, this is where stuff is actually executed and then redirected
	if(!isset($_POST['action'])){
		$smarty->assign("message","Error: Something went very wrong.");
			$smarty->display('error.tpl');
		exit();
	}
	
	// Process the input
	$action = mysql_real_escape_string($_POST['action']);
	if($action == "login"){
		/********************************
		* process user login
		********************************/
		if(authenticate(mysql_real_escape_string($_POST['uname']), mysql_real_escape_string($_POST['passwd']))){
			// The user is logged in
			$_SESSION['userid'] = get_user_id(mysql_real_escape_string($_POST['uname']));
			update_user_date($_SESSION['userid']);
			$smarty->assign("url","./index.php");
		}
		else{
			// Incorrect username or password
			$smarty->assign("url","./index.php?page=login");
		}
		$smarty->display('redirect.tpl');
		exit();
	}
	else if($action == "register"){
		/********************************
		* process user registration
		********************************/
		$gamepassword = mysql_real_escape_string($_POST['gpassword']);
		$username = mysql_real_escape_string($_POST['uname']);
		$password1 = mysql_real_escape_string($_POST['passwd1']);
		$password2 = mysql_real_escape_string($_POST['passwd2']);
		$fullname = mysql_real_escape_string($_POST['fname']);
		$emailaddress = mysql_real_escape_string($_POST['email']);
		
		// Determine if the captcha was entered in correctly
		$recaptcha_fail = true;
		$recaptcha_error = "";
		if(!$_CONFIG['recaptcha_enabled']){
			// Recaptcha is not enabled so we do not need to validate the field.
			$recaptcha_fail = false;
		}
		else if (isset($_POST["recaptcha_response_field"])) {
			// The post variables are not escaped here, but since they don't touch the database we should be safe
			$resp = recaptcha_check_answer($_CONFIG['recaptcha_private'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
			if ($resp->is_valid) {
                $recaptcha_fail = false;
			} else {
                # set the error code so that we can display it
                $recaptcha_error = $resp->error;
			}
		}
		
		// Determine what page to display or action to take
		if(strtolower($_CONFIG['registrationpassword']) != strtolower($gamepassword)){
			$smarty->assign("message","Error: The game password you entered was incorrect.  You can get the password from any player that is already playing the game.");
			$smarty->display('error.tpl');
			exit();
		}
		else if($recaptcha_fail){
			$smarty->assign("message","Recaptcha Error: " . $recaptcha_error);
			$smarty->display('error.tpl');
			exit();
		}
		else if($password1 != $password2){
			$smarty->assign("message","Error: The passwords you entered did not match, try registering again.");
			$smarty->display('error.tpl');
			exit();
		}
		else if(strlen($password1) < 6){
			$smarty->assign("message","Error: Your password must be at least 6 characters long.");
			$smarty->display('error.tpl');
			exit();
		}
		else if(strlen($username) < 4){
			$smarty->assign("message","Error: Your username must be at least 4 characters long.");
			$smarty->display('error.tpl');
			exit();
		}
		else if(strlen($fullname) < 6){
			$smarty->assign("message","Error: Your full name must be at least 6 characters long.");
			$smarty->display('error.tpl');
			exit();
		}
		else if(strlen($emailaddress) < 9){
			// This could be enhanced some more
			$smarty->assign("message","Error: Your email address is not valid.");
			$smarty->display('error.tpl');
			exit();
		}
		else{
			$_SESSION['userid'] = register($username, $password1, $fullname, $emailaddress);
			send_welcome_email($_SESSION['userid']);
			$smarty->assign("url","./index.php?page=register_zeep");
			$smarty->display('redirect.tpl');
			exit();
		}
	}
	else if($action == "killtarget"){
		/********************************
		* process kill attempt
		********************************/
		if(isset($_SESSION['userid']) && $_SESSION['userid'] != -1){
			$outcome = kill_attempt(mysql_real_escape_string($_POST['contract_id']), mysql_real_escape_string($_POST['secret']), "web");
			if($outcome){
				// The kill was successful
				$smarty->assign("url","./index.php?page=process_contract&outcome=1");
			}
			else{
				// The kill was not successful
				$smarty->assign("url","./index.php?page=process_contract&outcome=0");
			}
		}
		else{
			// User not authenticated, send them to the home page
			$smarty->assign("url","./index.php");
		}
		update_user_date($_SESSION['userid']);
		$smarty->display('redirect.tpl');
		exit();
	}
	else if($action == "togglestatus"){
		/********************************
		* process toggle status
		********************************/
		if(isset($_SESSION['userid']) && $_SESSION['userid'] != -1){
			toggle_user_account_status($_SESSION['userid']);
			$smarty->assign("url","./index.php?page=myaccount");
			update_user_date($_SESSION['userid']);
		}
		else{
			// User not authenticated, send them to the home page
			$smarty->assign("url","./index.php");
		}
		$smarty->display('redirect.tpl');
		exit();
	}
	else if($action == "changepassword"){
		/********************************
		* process change password
		********************************/
		if(isset($_SESSION['userid']) && $_SESSION['userid'] != -1){
			$id = mysql_real_escape_string($_POST['id']);
			$password = mysql_real_escape_string($_POST['password']);
			$password1 = mysql_real_escape_string($_POST['passwd1']);
			$password2 = mysql_real_escape_string($_POST['passwd2']);
			
			if($id != $_SESSION['userid']){
				// The form submitted does not match the authenticated user, send them to the home page
				$smarty->assign("url","./index.php");
			}
			else if($password1 != $password2){
				$smarty->assign("message","Error: The passwords you entered did not match, try changing your password again.");
				$smarty->display('error.tpl');
				exit();
			}
			else if(strlen($password1) < 6){
				$smarty->assign("message","Error: Your new password must be at least 6 characters long.");
				$smarty->display('error.tpl');
				exit();
			}
			else{
				// Validate the current password
				if(authenticate_id($id, $password)){
					// All systems go!  Change that password...
					change_password($id, $password1);
					update_user_date($_SESSION['userid']);
				}
				else{
					$smarty->assign("message","Error: Your old password did not match.");
					$smarty->display('error.tpl');
					exit();
				}
			}
			
			$smarty->assign("url","./index.php?page=myaccount");
		}
		else{
			// User not authenticated, send them to the home page
			$smarty->assign("url","./index.php");
		}
		$smarty->display('redirect.tpl');
		exit();
	}
	else if($action == "recoverpassword"){
		/********************************
		* process recover password
		********************************/
		$username = mysql_real_escape_string($_POST['uname']);
		$fullname = mysql_real_escape_string($_POST['fname']);
		$emailaddress = mysql_real_escape_string($_POST['email']);
		
		// Determine if the captcha was entered in correctly
		$recaptcha_fail = true;
		$recaptcha_error = "";
		if(!$_CONFIG['recaptcha_enabled']){
			// Recaptcha is not enabled so we do not need to validate the field.
			$recaptcha_fail = false;
		}
		else if (isset($_POST["recaptcha_response_field"])) {
			// The post variables are not escaped here, but since they don't touch the database we should be safe
			$resp = recaptcha_check_answer($_CONFIG['recaptcha_private'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
			if ($resp->is_valid) {
                $recaptcha_fail = false;
			} else {
                # set the error code so that we can display it
                $recaptcha_error = $resp->error;
			}
		}
		
		if($recaptcha_fail){
			$smarty->assign("message","Recaptcha Error: " . $recaptcha_error);
			$smarty->display('error.tpl');
			exit();
		}
		else if(authenticate_password_reset($username, $fullname, $emailaddress)){
			// Generate the new password for the user
			$newPassword = generate_secret() . generate_secret() . generate_secret();
			
			// Change the user's password
			change_password(get_user_id($username), $newPassword);
			
			// Send the new password to the user's email address
			send_password_changed(get_user_id($username), $newPassword);
			
			// Display the page that tells the user their password was reset
			$smarty->assign("message","A new password was sent to your email address.");
			$smarty->display('error.tpl');
			exit();
		}
		else{
			$smarty->assign("message","Error: Password could not be recovered.");
			$smarty->display('error.tpl');
			exit();
		}
		
		exit();
	}
}
else if($_GET['page'] == "login"){
	/*******************************************************************************************************
	 * Login page
	 ******************************************************************************************************/
	$smarty->assign("pagename", "Login");
	$smarty->display('login.tpl');
}
else if($_GET['page'] == "register"){
	/*******************************************************************************************************
	 * Register page
	 ******************************************************************************************************/
	$smarty->assign("pagename", "Register");
	$error = "";
	if($_CONFIG['recaptcha_enabled']){
		$smarty->assign("recaptcha", recaptcha_get_html($_CONFIG['recaptcha_public'], $error));
	}
	$smarty->assign("recaptcha_enabled", $_CONFIG['recaptcha_enabled']);
	$smarty->display('register.tpl');
}
else if($_GET['page'] == "recover_password"){
	/*******************************************************************************************************
	 * Recover password page
	 ******************************************************************************************************/
	$smarty->assign("pagename", "Recover Password");
	$error = "";
	if($_CONFIG['recaptcha_enabled']){
		$smarty->assign("recaptcha", recaptcha_get_html($_CONFIG['recaptcha_public'], $error));
	}
	$smarty->assign("recaptcha_enabled", $_CONFIG['recaptcha_enabled']);
	$smarty->display('recover_password.tpl');
}
else if($_GET['page'] == "register_zeep"){
	/*******************************************************************************************************
	 * Zeepmobile registration page
	 ******************************************************************************************************/
	$smarty->assign("pagename", "Register Zeep");
	if(isset($_SESSION['userid']) && $_SESSION['userid'] != -1){
		$smarty->assign("api_key", $_CONFIG['zeep_api']);
		$smarty->assign("userid", $_SESSION['userid']);
		$smarty->display('register_zeep.tpl');
	}
	else {
		$smarty->assign("message","Error: You must be logged in to view this page.");
		$smarty->display('error.tpl');
		exit();
	}
}
else if($_GET['page'] == "user"){
	/*******************************************************************************************************
	 * Public User information page
	 ******************************************************************************************************/
	$page_userid = intval(mysql_real_escape_string($_GET['id']));
	$smarty->assign("past_contract_chart", get_user_contract_pie_chart($page_userid));
	$smarty->assign("past_target_chart", get_user_target_pie_chart($page_userid));
	$page_user = get_user_information($page_userid);
	$smarty->assign("fullname", $page_user['name']);
	$smarty->assign("pagename", $page_user['name']);
	$smarty->assign("contracts", get_users_contracts($page_userid));
	$smarty->assign("enemies", get_user_enemies($page_userid));
	$smarty->assign("contract_summary", get_user_contract_summary($page_userid));
	$smarty->assign("xfn", xfn_relations($page_userid));
	$smarty->display('user.tpl');
}
else if($_GET['page'] == "listusers"){
	/*******************************************************************************************************
	 * public list of users
	 ******************************************************************************************************/
	$smarty->assign("pagename", "List of Players");
	$users = get_active_users();
	for($i = 0; $i < sizeof($users); $i++){
		if(date(time()) > $users[$i]['spawn']){
			$users[$i]['alive'] = 1;
		}
		else{
			$users[$i]['alive'] = 0;
			$diff = $users[$i]['spawn'] - date(time());
			$contract_hours_left = floor($diff/60/60);
			$contract_minutes_left =  floor(($diff - $contract_hours_left*60*60)/60);
			$users[$i]['delay'] = $contract_hours_left . " hours and " . $contract_minutes_left . " minutes";
		}
	}
	$smarty->assign("all_users", $users);
	$smarty->assign("all_users_inactive", get_inactive_users());
	$smarty->display('listusers.tpl');
}
else if($_GET['page'] == "current_contract"){
	/*******************************************************************************************************
	 * Private current contract page
	 ******************************************************************************************************/
	$smarty->assign("pagename", "Current Contract");
	if(isset($_SESSION['userid']) && $_SESSION['userid'] != -1){
		$contract_id = get_user_contract_id($_SESSION['userid']);
		$smarty->assign("contract_id", $contract_id);
		if($contract_id != -1){
			$contract_info = get_contract_information($contract_id);
			$contract_hours_left = floor($contract_info['seconds_remaining']/60/60);
			$contract_minutes_left =  floor(($contract_info['seconds_remaining'] - $contract_hours_left*60*60)/60);
			$smarty->assign("contract_hours_left", $contract_hours_left);
			$smarty->assign("contract_minutes_left", $contract_minutes_left);
			$smarty->assign("contract_info",$contract_info);
		}
		$smarty->display('current_contract.tpl');
	}
	else{
		$smarty->assign("message","Error: You must be logged in to view this page.");
		$smarty->display('error.tpl');
		exit();
	}
}
else if($_GET['page'] == "myaccount"){
	/*******************************************************************************************************
	 * private my account page
	 ******************************************************************************************************/
	$smarty->assign("pagename", "My Account");
	if(isset($_SESSION['userid']) && $_SESSION['userid'] != -1){
		$page_user = get_user_information($_SESSION['userid']);
		$smarty->assign("gamepassword", $_CONFIG['registrationpassword']);
		$smarty->assign("isinactivedelay", can_user_become_active($_SESSION['userid']));
		$smarty->assign("inactivetimeleft", time_left_till_user_can_become_active($_SESSION['userid']));
		$smarty->assign("inactivedelay", $_CONFIG['inactivedelay']);
		$smarty->assign("id", $page_user['id']);
		$smarty->assign("fullname", $page_user['name']);
		$smarty->assign("secret", $page_user['secret']);
		$smarty->assign("active", $page_user['active']);
		//$smarty->assign("contracts", get_users_contracts($page_userid));
		$smarty->display('myaccount.tpl');
	}
	else{
		$smarty->assign("message","Error: You must be logged in to view this page.");
		$smarty->display('error.tpl');
		exit();
	}
}
else if($_GET['page'] == "mobile_commands"){
	/*******************************************************************************************************
	 * Mobile Commands
	 ******************************************************************************************************/
	$smarty->assign("pagename", "Mobile Commands");
	$smarty->assign("sample_secert", generate_secret());
	$smarty->display('mobile_commands.tpl');
}
else if($_GET['page'] == "leaderboard"){
	/*******************************************************************************************************
	 * Leaderboard
	 ******************************************************************************************************/
	if(isset($_GET['time'])){
		if($_GET['time'] == "month"){
			if(isset($_GET['year']) && isset($_GET['month'])){
				// Display the specified month
				$thismonth['year'] = $_GET['year'];
				$thismonth['month'] = $_GET['month'];
			}
			else{
				// Display current month
				$thismonth = get_current_month();
			}
			
			$monthdates = get_month_date($thismonth['year'], $thismonth['month']);
			$nextmonth = get_next_month($thismonth['year'], $thismonth['month']);
			$previousmonth = get_previous_month($thismonth['year'], $thismonth['month']);
			$smarty->assign("board", date("F Y", mktime(0, 0, 0, $thismonth['month'], 1, $thismonth['year'])));
			$smarty->assign("pagename", "Leaderboard - " . $thismonth['month'] . "/" . $thismonth['year']);
			$smarty->assign("boardlink", 1);
			$smarty->assign("previouspage", "leaderboard/month/" . $previousmonth['year'] . "/" . $previousmonth['month'] . "/");
			$smarty->assign("nextpage", "leaderboard/month/" . $nextmonth['year'] . "/" . $nextmonth['month'] . "/");
			
			$smarty->assign("leaderboard", get_leaderboard_for_range($monthdates['start'], $monthdates['end']));
		}
		else if($_GET['time'] == "semester"){
			if(isset($_GET['year']) && isset($_GET['semester'])){
				// Display the specified semester
				$thissemester['year'] = $_GET['year'];
				$thissemester['number'] = $_GET['semester'];
			}
			else{
				// Display current semester
				$thissemester = get_current_semester();
			}
			
			$semesterdates = get_semester_date($thissemester['year'], $thissemester['number']);
			$nextsemester = get_next_semester($thissemester['year'], $thissemester['number']);
			$previoussemester = get_previous_semester($thissemester['year'], $thissemester['number']);
			
			if($thissemester['number'] == 1){
				$boardName = "Spring " . $thissemester['year'];
			}
			else if($thissemester['number'] == 2){
				$boardName = "Summer " . $thissemester['year'];
			}
			else if($thissemester['number'] == 3){
				$boardName = "Fall " . $thissemester['year'];
				
			}
			
			$smarty->assign("board", $boardName);
			$smarty->assign("pagename", "Leaderboard - " . $boardName );
			$smarty->assign("boardlink", 2);
			$smarty->assign("previouspage", "leaderboard/semester/" . $previoussemester['year'] . "/" . $previoussemester['number'] . "/");
			$smarty->assign("nextpage", "leaderboard/semester/" . $nextsemester['year'] . "/" . $nextsemester['number'] . "/");
			$smarty->assign("leaderboard", get_leaderboard_for_range($semesterdates['start'], $semesterdates['end']));
		}	
	}
	else{
		// All time
		$smarty->assign("pagename", "Leaderboard - All Time");
		$smarty->assign("board", "All Time");
		$smarty->assign("boardlink", 3);
		$smarty->assign("leaderboard", get_all_time_leaderboard());
	}
	
	$smarty->display('leaderboard.tpl');
}
else if($_GET['page'] == "feed"){
	/*******************************************************************************************************
	 * News RSS Feed
	 ******************************************************************************************************/
	$news = get_recent_news_items();
	for($i = 0; $i < sizeof($news); $i++){
		$news[$i]['message'] = strip_tags ($news[$i]['message']);
	}
	$smarty->assign("news", $news);
	$smarty->display('feed.tpl');
}
else if($_GET['page'] == "process_contract"){
	/*******************************************************************************************************
	 * Results page after a contract has been submitted
	 ******************************************************************************************************/
	$smarty->assign("pagename", "Process Contract");
	if(isset($_GET['outcome']) && $_GET['outcome'] == "1"){
		$smarty->assign("success", 1);
	}
	else{
		$smarty->assign("success", 2);
	}
	$smarty->display('process_contract.tpl');
}
else{
	/*******************************************************************************************************
	 * Page not found
	 ******************************************************************************************************/
	$smarty->assign("pagename", "Page Not Found");
	$smarty->display('notfound.tpl');
}


?>