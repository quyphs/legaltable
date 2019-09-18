<?php
	require('includes/config.inc.php');
	redirectValidUser();
	require(MYSQL);
	require(HTML_PROTO);
	require(HTML_FUNCTIONS);
	require_once(FORMS_FUNCTIONS);
	
	$pageTitle = 'Logging In Page';
	include(HEADER);

	$logInErrors = array();
	
	//validating log in information and then creating erros array
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$email = $_POST['email'];
		} else {
			$logInErrors['email'] = $emailLogInErrorHelp;
		}

		if (!empty($_POST['pass'])) {
			$pass = $_POST['pass'];
		} else {
			$logInErrors['pass'] = $passLogInErrorHelp;
		}
		
		if (empty($logInErrors)) {
			$qL = "SELECT users.user_id, users.user_name, users.reputation, users.password FROM users WHERE users.email = '$email'";
			$rsL = datFetching($qL);
			if (password_verify($pass, $rsL['password'])) {
				session_regenerate_id(true);
				if ($rsL['reputation'] === 'admin') {
					$_SESSION['userAdmin'] = true;
				}
				$_SESSION['userId'] = $rsL['user_id'];
				$_SESSION['userName'] = $rsL['user_name'];
				echo '<p>You have logged in as '.$_SESSION['userName'].'</p>';
				echo '<p>Please <a href='.$homePage.'>click here</a> to return to the home page</p>';
				include(FOOTER);
				exit();
			} else {
				$logInErrors['logIn'] = $logInErrorHelp;
			} 
		}
	}
	//checking the validity of the email and password combination to be successfully logged in

	//creating log in form
	$inputSeries = '';
	if (array_key_exists('logIn', $logInErrors)) {
		$inputSeries .= '<div class="block-help">'.$logInErrors['logIn'].'</div>';
	}
	$inputSeries .= creatingInput('', 'email', 'email', 'email', $label='Email', $logInErrors);
	$inputSeries .= creatingInput('', 'pass', 'pass', 'password', $label='Password', $logInErrors);
	$inputSeries .= creatingSubmit('', 'logInBtn', 'logInBtn', 'Log In');
	$logInForm = creatingFormP('', 'log_in.php', $inputSeries);
	
	echo $logInForm;
	
	include(FOOTER);
	
?>