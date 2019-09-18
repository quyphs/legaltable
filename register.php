<?php
	require('includes/config.inc.php');
	redirectValidUser();
	require(MYSQL);
	require(HTML_PROTO);
	require(HTML_FUNCTIONS);
	require_once(FORMS_FUNCTIONS);


	$pageTitle = 'Register';
	include(HEADER);

	$regErrors = array();
	
	//validating inputs and preparing errors
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		//validating the first name using regular expression
		if (preg_match($ptnName, $_POST['firstName'])) {
			$firstName = datEscaping($_POST['firstName']);
		} else {
			$regErrors['firstName'] = $nameRegErrorHelper;
		}
		
		//validating the last name using regular expression
		if (preg_match($ptnName, $_POST['lastName'])) {
			$lastName = datEscaping($_POST['lastName']);
		} else {
			$regErrors['lastName'] = $nameRegErrorHelper;
		}

		//validating the date of birth using regular expression
		if (isset($_POST['dob'])) {
			$dob = explode('-', $_POST['dob']);
			if (count($dob) != 0 && count($dob) == 3) {
				list($year, $month, $day) = $dob;
				if (checkdate($month, $day, $year)) {
					$dob = date("Y-m-d H:i:s", strtotime($_POST['dob']));
				} else {
					$regErrors['dob'] = $dobReg1ErrorHelper;
				}
			} else {
				$regErrors['dob'] = $dobReg1ErrorHelper;
			}
		} else {
			$regErrors['dob'] = $dobReg2ErrorHelper;
		}

		//validating the user name using regular expression
		if (preg_match($ptnUserName, $_POST['userName'])) {
			$userName = datEscaping($_POST['userName']);
		} else {
			$regErrors['userName'] = $userNameRegErrorHelper;
		}

		//validating the email 
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$email = datEscaping($_POST['email']);
		} else {
			$regErrors['email'] = $emailRegErrorHelper;
		}

		//validating the password 
		if (preg_match($pntPassword, $_POST['pass1'])) {
			if ($_POST['pass2'] == $_POST['pass1']) {
				$pass = $_POST['pass1'];
			} else {
				$regErrors['pass2'] = $passReg2ErrorHelper;
			}
		} else {
			$regErrors['pass1'] = $passReg1ErrorHelper;
		}
		
		//validating the agreement to terms and conditions
		if (isset($_POST['agreedToTerms']) && $_POST['agreedToTerms'] === 'agreed') {
			$agreedToTerms = 'yes';
		} else {
			$regErrors['agreedToTerms'] = $agreedRegErrorHelper;
		}
		
		$dateCreation = date("Y-m-d H:i:s");
		
		//checking the availibity of username and email, and then inserting it into database
		if (empty($regErrors)) {
			$qC = "SELECT users.user_name, users.email FROM users WHERE users.user_name='$userName' OR users.email='$email'";
			$rC = datFetching($qC);

			if (count($rC) === 0) {
				$qI = "INSERT INTO users (user_name, first_name, last_name, date_of_birth, registered_date, reputation, agree_to_terms_and_conditions, email, password) VALUES ('$userName', '$firstName', '$lastName', '$dob', '$dateCreation', 'citizen', '$agreedToTerms', '$email', '".password_hash($pass, PASSWORD_BCRYPT)."')";
				mysqli_query($dbc, $qI);
				if (mysqli_affected_rows($dbc)===1) {
					echo $regSuc;
					//mail($_POST['email'], 'Confirmation', 'Thank you for registering at our website', 'From:');
					include(FOOTER);
					exit();
				} else {
					echo $sysErrorHelp;
					include(FOOTER);
					exit();
				}
			}

			if (count($rC) === 1) {
				if ($rC['user_name'] == $userName  && $rC['email'] == $email){
					$regErrors['userName'] = $userRegisteredReg1ErrorHelp;
					$regErrors['email'] = $emailRegisteredRegErrorHelp;
				} else if ($rC['user_name'] == $userName) {
					$regErrors['userName'] = $userRegisteredReg2ErrorHelp;
				} else if ($rC['email'] == $email) {
					$regErrors['email'] = $emailRegisteredRegErrorHelp;
				}
			}

			if (count($rC) === 2) {
				$regErrors['userName'] = $userRegisteredReg2ErrorHelp;
				$regErrors['email'] = $emailRegisteredRegErrorHelp;
			}
		}
	}

	$inputSeries = '';
	$inputSeries .= creatingInput('', 'firstName', 'firstName', 'text', $label='First Name', $regErrors);
	$inputSeries .= creatingInput('', 'lastName', 'lastName', 'text', $label='Last Name', $regErrors);
	$inputSeries .= creatingInput('', 'dob', 'dob', 'text', $label='Date of Birth', $regErrors);
	$inputSeries .= creatingInput('', 'userName', 'userName', 'text', $label='User Name', $regErrors);
	$inputSeries .= creatingInput('', 'email', 'email', 'email', $label='Email', $regErrors);
	$inputSeries .= creatingInput('', 'pass1', 'pass1', 'password', $label='Password', $regErrors);
	$inputSeries .= creatingInput('', 'pass2', 'pass2', 'password', $label='Password Confirmation', $regErrors);
	$inputSeries .= creatingInput('', 'agreedToTerms', 'agreedToTerms', 'checkbox', $label='I agree to <a href="'.OURPOLICY.'">the terms and conditions of this website</a>', $regErrors, array('value' =>'agreed'));
	$inputSeries .= creatingSubmit('', 'registerBtn', 'registerBtn', 'Register Now');
	$registerForm = creatingFormP('', 'register.php', $inputSeries);

	echo $registerForm;

	include(FOOTER);
?>
