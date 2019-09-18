<?php
	require('includes/config.inc.php');
	redirectInvalidUser();
	require(MYSQL);
	require(HTML_PROTO);
	require(HTML_FUNCTIONS);
	require_once(FORMS_FUNCTIONS);
	
	$pageTitle = 'Changing Your Password';
	include(HEADER);
	
	$passErrors = array();
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (!empty($_POST['pass0'])) {
			$passC = $_POST['pass0'];
		} else {
			$passErrors['pass0'] = $pass1ChangeErrorHelp;
		}
		
		if (preg_match($pntPassword, $_POST['pass1'])) {
			if ($_POST['pass2'] == $_POST['pass1']) {
				$passN = $_POST['pass1'];
			} else {
				$regErrors['pass2'] = $pass2ErrorHelper;
			}
		} else {
			$regErrors['pass1'] = $pass1ErrorHelper;
		}
		
		if (empty($passErrors)) {
			$qQ = "SELECT users.password FROM users WHERE users.user_id = {$_SESSION['userId']}";
			$rsQ = datFetching($qQ);
			if (count($rsQ) === 1) {
				$passD = $rsQ[0]['password'];
				if (password_verify($passC, $passD)) {
					$qI = "UPDATE users SET password ='".password_hash($passN, PASSWORD_BCRYPT)."' WHERE users.user_id = {$_SESSION['userId']}";
					mysqli_query($dbc, $qI);
					if (mysqli_affected_rows($dbc) === 1) {
						echo $passChangeSuc;
						include(FOOTER);
						exit();
					} else {
						$passErrors['pass'] = $sysErrorHelp;
					}
				} else {
					echo $pass2ChangeErrorHelp;
				}
			} else {
				$passErrors['pass'] = $sysErrorHelp;
			}
		}
	}

	
	$inputSeries = '';
	if (array_key_exists('pass', $passErrors)) {
		$inputSeries .= '<div class="block-help">'.$passErrors['pass'].'</div>';
	}
	$inputSeries .= creatingInput('', 'pass0', 'pass0', 'password', $label='Current Password', $passErrors);
	$inputSeries .= creatingInput('', 'pass1', 'pass1', 'password', $label='New Password', $passErrors);
	$inputSeries .= creatingInput('', 'pass2', 'pass2', 'password', $label='New Password Confirmation', $passErrors);
	$inputSeries .= creatingSubmit('', 'changeBtn', 'changeBtn', 'Change Now');
	$inputForm = creatingForm('', 'pass_change.php', $inputSeries);
	
	echo $inputForm;
	
	include(FOOTER);
?>

