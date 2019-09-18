<?php
	require('includes/config.inc.php');
	redirectInvalidUser();
	require(MYSQL);
	require(HTML_PROTO);
	require(HTML_FUNCTIONS);
	require_once(FORMS_FUNCTIONS);
	
	$addReviewErrors = array();
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['articleId'])) {
			$articleId = $_POST['articleId'];
		} else {
			$addReviewErrors['articleId'] = "Please dont leave this empty!";
		}

		if (!empty($_POST['review'])) {
			$content = datEscaping(strip_tags($_POST['review']));
		} else {
			$addReviewErrors['review'] = "Please dont leave this empty!";
		}
		
		if (empty($addReviewErrors)) {
			$checkCode = randomCode();
			if (datPostingReview($articleId, $content, $checkCode)) {
				$r = datFetchingOneRawReview($checkCode);
				if ($r != false) {
					datClearingReviewCode($checkCode);
					$review = publishingOneReview($r);
					echo $review;
				}
			}
		}
	}	
?>