<?php
	require('includes/config.inc.php');
	require(MYSQL);
	require(HTML_PROTO);
	require(HTML_FUNCTIONS);
	require_once(FORMS_FUNCTIONS);
	
	$pageTitle = 'Home';
	include(HEADER);
	
	$pageErrors = array();
	$addTopicErrors = array();
	
	$p = 1;
	$qC = "SELECT COUNT(topics.topic_id) AS numTopics FROM topics";
	$rsC = datFetching($qC, 'numTopics');
	$numTopics = (int)$rsC;
	$numPages = ceil($numTopics/$topicsPerPage);

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		if (isset($_GET['prevPage'])) {
			$p = $_GET['prevPage'];
		}
		
		if (isset($_GET['nextPage'])) {
			$p = $_GET['nextPage'];
		}
		
		if (isset($_GET['toPage'])) {
			if (1 <= $_GET['toPage'] && $_GET['toPage'] <= $numPages) {
				$p = $_GET['toPage'];
			} else {
				$pageErrors['toPage'] = "Please give the page number within range!";
			}
		}
		
		if (!is_numeric($p)) {
			$p = (int)$p;
		}
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (!empty($_POST['heading'])) {
			$heading = datEscaping(strip_tags($_POST['heading']));
		} else {
			$addTopicErrors['heading'] = "Please give a title!";
		}
		
		if (!empty($_POST['question'])) {
			$question = datEscaping(strip_tags($_POST['question'], $tagsAccepted));
		} else {
			$addTopicErrors['question'] = "Please give your question!";
		}
		
		if (empty($addTopicErrors)) {
			$checkCode = randomCode();
			$qT = "INSERT INTO topics (user_id, date_of_topic, heading, check_code) VALUES ('{$_SESSION['userId']}', '$dateCreation', '$heading', '$checkCode')";
			mysqli_query($dbc, $qT);
			if (datPostingTopic($heading, $checkCode)) {
				$topicId = datFetchingTopicId($checkCode);
				if (datPostingArt($topicId, $question, $checkCode)) {
					$artId = datFetchingArtId($checkCode);
					if (datPostingArtArtType($artId, $questionArtTypeId)) {
						datClearingTopicCode($checkCode);
						datClearingArtCode($checkCode);
						echo 'Thank you';
						include(FOOTER);
						exit();
					} else {
						echo $sysErrorHelp;
						include(FOOTER);
						exit();
					}
				} else {
					echo $sysErrorHelp;
					include(FOOTER);
					exit();
				}
			} else {
				echo $sysErrorHelp;
				include(FOOTER);
				exit();
			}
		}
	}

	$pP = $p - 1;
	$nP = $p + 1;
	
	if (1 <= $p && $p <= $numPages) {
		echo publishingTopicSet($p);
	}
	
	if ($pP >= 1) {
		$pPBtn = '';
		$pPBtn .= creatingInput('', 'prevPage', 'prevPage', 'hidden', '', $pageErrors, array('value'=>$pP));
		$pPBtn .= creatingSubmit('', 'goPrevPage', 'goPrevPage', 'Previous');
		
		$pPForm = creatingFormG('', 'main_page.php', $pPBtn);
		echo $pPForm;
	}
	
	if ($nP <= $numPages) {
		$nPBtn = '';
		$nPBtn .= creatingInput('', 'nextPage', 'nextPage', 'hidden', '', $pageErrors, array('value'=>$nP));
		$nPBtn .= creatingSubmit('', 'goNextPage', 'goNextPage', 'Next');
		
		$nPForm = creatingFormG('', 'main_page.php', $nPBtn);
		echo $nPForm;
	}
	
	echo sprintf("<p class='pageNumIndicator'> Page: %d / %d</p>", $p, $numPages);
	$toPBtn = '';
	$toPBtn .= creatingInput('', 'toPage','toPage', 'text', 'Page: ', $pageErrors);
	$toPBtn .= creatingSubmit('', 'goToPage', 'goToPage', 'Go');
	$toPForm = creatingFormG('goToPageForm', 'main_page.php', $toPBtn);
	echo $toPForm;
	
	if (isset($_SESSION['userId'])) {
		$editor = '';
		$editor .= '<p>[TOPIC POSTING]: So, what is your problem? Please tell us!</p>';
		$editor .= creatingInput('',  'heading', 'heading', 'text', 'Your heading', $addTopicErrors);
		$editor .= creatingInput('', 'question', 'question', 'textarea', 'Your question', $addTopicErrors);
		$editor .= creatingSubmit('', 'post', 'post', 'Post');
		$editor .= creatingEditor('#question');
		$editorForm = creatingFormP('', 'main_page.php', $editor);
		echo $editorForm;
	}
	
	include(FOOTER);
?>
