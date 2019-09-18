<?php
	require('includes/config.inc.php');
	require(MYSQL);
	require(HTML_PROTO);
	require(HTML_FUNCTIONS);
	require_once(FORMS_FUNCTIONS);
	
	$url ='http://seri.com/path/';
	$q = parse_url($url, PHP_URL_PATH);
	echo $q;
?>