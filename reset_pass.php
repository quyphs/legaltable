<?php
	require('includes/config.inc.php');
	redirectValidUser();
	require(MYSQL);
	require(HTML_PROTO);
	require(HTML_FUNCTIONS);
	require_once(FORMS_FUNCTIONS);
	
	$pageTitle = 'Resetting Your Password';
	include(HEADER);
	
	$resetErrors = array();
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$email = $_POST['email'];
			$qS = "SELECT users.user_id FROM users WHERE users.email ='$email'";
			$rsS = datFetching($qS);
			if (count($rsS) === 1) {
				$userId = $rsS[0]['user_id'];
			} else {
				$resetErrors['email'] = $email1ResetErrorHelp;
			}
		} else {
			$resetErrors['email'] = $email2ResetErrorHelp;
		}
		
		if (empty($resetErrors)) {
			$pass = substr(md5(uniqid(rand(), true)), 10, 5);
			$qO = "UPDATE users SET users.password = '".password_hash($pass, PASSWORD_BCRYPT)."' WHERE users.user_id = $userId";
			mysqli_query($dbc, $qO);
			if (mysqli_affected_rows($dbc) === 1) {
				//$emailBody = 'Your password has been temporarily changed to '.$pass.'. Please log in using that password to change your password!';
				//mail($_POST['email'], 'Resetting Your Password', $emailBody, 'From:abc@gmail.com');
				echo 'Please see this temp pass '.$pass.' to log in and then change your password';
				include(FOOTER);
				exit();
			} else {
				echo $sysErrorHelp;
			}
		}
	}

	
	$inputSeries = '';
	if (array_key_exists('reset', $resetErrors)) {
		$inputSeries .= '<div class="block-help">'.$resetErrors['reset'].'</div>';
	}
	$inputSeries .= creatingInput('', 'email', 'email', 'email', $label='Email', $resetErrors);
	$inputSeries .= creatingSubmit('', 'resetBtn', 'resetBtn', 'Reset It!');
	$inputForm = creatingFormP('', 'reset_pass.php', $inputSeries);

	echo $inputForm;

	include(FOOTER);
?>
