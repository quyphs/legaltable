<?php
	require('includes/config.inc.php');
	redirectInvalidUser();
	require(MYSQL);
	require(HTML_PROTO);
	require(HTML_FUNCTIONS);
	require_once(FORMS_FUNCTIONS);
	
	$voteErrors = array();
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		
		if(isset($_POST['artId']) && is_numeric($_POST['artId'])) {
			$artId = $_POST['artId'];
		} else {
			$voteErrors['artId'] = 'must a number!';
		}
		
		if(isset($_POST['type'])) {
			$type = $_POST['type'];
			if ($type === 'Good') {
				$type = 'UP';
			} else if ($type === 'Bad') {
				$type = 'DOWN';
			} else if ($type === 'Unvoting') {
				$type = 'UNSET';
			} else {
				$voteErrors['type'] = 'type not correct! only good or bad!';
			}
		} else {
			$voteErrors['type'] = 'must have a number!';
		}
		
		if (empty($voteErrors)) {
			$voted = datFetchingUserVotedThisArt($artId);
			if ($type === 'UNSET') {
				if (datUnVoting($artId)) {
					echo publishingVoteBar($artId);
				}
			} else if ($voted === false) {
				if (datPostingVote($artId, $type)) {
					echo publishingVoteBar($artId);
				}
			} else if ($voted != $type) {
				if(datUpdatingVote($artId, $type)) {
					echo publishingVoteBar($artId);
				};
			} else if ($voted === $type) {
				echo publishingVoteBar($artId);
				echo "You voted this before!";
			} 
		} else {
			echo publishingVoteBar($artId);
			foreach ($voteErrors as $error => $detail) {
				echo $error.": ".$detail;
			};
		}
	}
?>