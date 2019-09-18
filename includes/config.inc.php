<?php
// SESSION MATTERS
session_start();

// CONSTANTS
define('CONTACT_EMAIL', 'quyphs@outlook.com');
define('BASE_URI', 'C:/wamp64/www/legalweb/');
define('BASE_URL', 'seriouspapers.com/');
define('MYSQL', BASE_URI.'includes/mysql.inc.php');
define('HTML_PROTO' , BASE_URI.'includes/html_prototype.inc.php');
define('HTML_FUNCTIONS' , BASE_URI.'includes/html_functions.inc.php');
define('FORMS_FUNCTIONS' , BASE_URI.'includes/forms_functions.inc.php');
define('HEADER' , BASE_URI.'includes/header.php');
define('FOOTER' , BASE_URI.'includes/footer.php');
define('MOTTO' , 'Experimenting Legal Matters');
define('OURPOLICY' , '#');

// GLOBAL USEFUL VARS
$topicsPerPage = 5;
$homePage = 'main_page.php';
$projectPage = 'project.php';
$ptnName = '/^[a-zA-Z ]{2,20}$/';
$ptnUserName = '/^[a-zA-Z0-9]{6,20}$/';
$pntPassword = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/';
$pages = array(
	'Home'=>'main_page.php',
	'My Project'=>'project.php',
	'User' => array(
		'Register' => 'register.php',
		'Log In' => 'log_in.php',
		'Key Reset' => 'reset_pass.php'
	)
);

if (isset($_SESSION['userId'])) {
	unset($pages['User']['Register']);
	unset($pages['User']['Log In']);
	unset($pages['User']['Reset Password']);
	$pages['User']['Log Out'] = 'log_out.php';
	$pages['User']['Change Password'] = 'pass_change.php';
}

function redirectInvalidUser($typeCheck = 'userId', $dest = 'main_page.php', $protocol = 'http://') {
	if (!isset($_SESSION[$typeCheck])) {
		$url = $protocol.BASE_URL.$dest;
		if (!headers_sent()) {
			header('Location:'.$url);
			exit();
		} else {
			trigger_error('You do not have premission to this page. Sorry!');
		}
	}
}

function redirectValidUser($typeCheck = 'userId', $dest = 'main_page.php', $protocol = 'http://') {
	if (isset($_SESSION[$typeCheck])) {
		$url = $protocol.BASE_URL.$dest;
		if (!headers_sent()) {
			header('Location:'.$url);
			exit();
		} else {
			trigger_error('You do not need this functionality. :)!');
		}
	}
}

function weAreWatchingThisTopicId() {
	$q = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
	parse_str($q, $data);
	$of_topic = str_replace('/', '', $data['topicId']);
	return $of_topic;
}

function randomCode() {
	$checkCode = substr(md5(uniqid(rand(), true)), 10, 5);
	return $checkCode;
}
