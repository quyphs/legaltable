<?php
	require('includes/config.inc.php');
	redirectInvalidUser();
	require(MYSQL);
	require(HTML_PROTO);
	require(HTML_FUNCTIONS);
	require_once(FORMS_FUNCTIONS);
	
	$taggingErrors = array();
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		
		if (isset($_POST['tagName']) && !empty($_POST['tagName'])) {
			$tagsString = $_POST['tagName'];
			$tagsString = datGettingRawText($tagsString);
			if (strstr($tagsString, '@')) {
				$tagsStack = explode("@", $tagsString);
				$tags = array();
				foreach ($tagsStack as $t) {
					if ($t != '') {
						$tags[] = trim($t);
					}
				}
			} else {
				$tags[] = $tagsString;
			}
		} else {
			$taggingErrors['tagName'] = 'Please dont leave this empty';
		}
		
		if (isset($_POST['topicId']) && is_numeric($_POST['topicId'])) {
			$topicId = $_POST['topicId'];
		} else {
			$taggingErrors['topicId'] = 'Dont have topic ID';
		}
		
		if (empty($taggingErrors)) {
			foreach ($tags as $tagName) {
				$tagId = datFetchingTagID($tagName);
				if ($tagId != false) {
					$tagId = $tagId[0]['tag_id'];
					if(datFetchingUserHasTaggedThisYet($topicId, $tagId)) {
						echo "You have tagged this before";
					} else {
						if (datPostingTag($topicId, $tagId)) {
							echo "Thank you";
							include(FOOTER);
							exit();
						}
					}
				} else {
					$checkCode = randomCode();
					if (datPostingTagLabel($tagName, $checkCode)) {
						$tagId = datFechingTagIdByCode($checkCode);
						if (datPostingTag($topicId, $tagId)) {
							datClearingTagCode($checkCode);
							echo "Thank you";
							include(FOOTER);
							exit();
						}
					}
				}			
			}
		}
	}
	
	$inputSeries = '';
	$inputSeries .= creatingInput('', 'tagName', 'tagName', 'text', $label='Tag name', $errors=$taggingErrors, array());
	$inputSeries .= creatingSubmit('', 'tagSubmit', '', 'Tag it');
	$inputForm = creatingFormP('taggingForm', 'tagging.php', $inputSeries);
	echo $inputForm;

?>
