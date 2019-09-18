<?php
	require('includes/config.inc.php');
	redirectInvalidUser();
	require(MYSQL);
	require(HTML_PROTO);
	require(HTML_FUNCTIONS);
	
	$pageTitle = "Your Project";
	include(HEADER);

	echo publishingProjects();
	
?>
