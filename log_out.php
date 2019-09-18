<?php
	require('includes/config.inc.php');
	redirectInvalidUser();
	require(HTML_PROTO);
	require(HTML_FUNCTIONS);

	$_SESSION[] = array();
	session_destroy();
	setcookie(session_name(), '', time()-300);

	$pageTitle = 'Log Out';
	include(HEADER);
	
	echo $logOutSuc;

	include(FOOTER);
?>
