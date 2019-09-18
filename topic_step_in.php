<?php
	require('includes/config.inc.php');
	require(MYSQL);
	require(HTML_PROTO);
	require(HTML_FUNCTIONS);
	require_once(FORMS_FUNCTIONS);

	$titlePage = "Topic Step In";
	include(HEADER);
	
	$addArtErrors = array();
	
	if (isset($_GET["topicId"])) {
		$tId = str_replace('/', '', $_GET["topicId"]);
		publishingArticles($tId);
	};
		
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (!empty($_POST['type'])) {
			$typeId = datEscaping(strip_tags($_POST['type']));
		} else {
			$addArtErrors['type'] = "Please dont have it empty!";
		}
		
		if (!empty($_POST['content'])) {
			$content = datEscaping(strip_tags($_POST['content'], $tagsAccepted));
		} else {
			$addArtErrors['content'] = "Please give your sharing!";
		}
		
		if (empty($addArtErrors)) {
			$checkCode = randomCode();
			$topicId = weAreWatchingThisTopicId();
			if (datPostingArt($topicId, $content, $checkCode)) {
				$artId = datFetchingArtId($checkCode);
				if (datPostingArtArtType($artId, $typeId)) {
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
		}
		
	}

	$artTypes = datFetchingArtTypesInArray();
	if ($artTypes === false) {
		echo $sysErrorHelp;
		include(FOOTER);
		exit();
	}

	if (isset($_SESSION['userId'])) {
		$editor = '';
		$editor .= '<p>[ARTICLE POSTING]: So, what is your thinking? Please tell us!</p>';
		$editor .= creatingSelect('', 'type', 'type', 'Your kind of presenting', $artTypes, $addArtErrors);
		$editor .= creatingInput('', 'content', 'content', 'textarea', 'Your Presents', $addArtErrors);
		$editor .= creatingSubmit('', 'post', 'post', 'Post');
		$editor .= creatingEditor('#content');
		$editorForm = creatingFormP('', 'topic_step_in.php', $editor);
		echo $editorForm;
	}

	include(FOOTER);
?>
