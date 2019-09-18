<?php
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DATABASE', 'legalweb');

$dbc = mysqli_connect(HOST, USER, PASS, DATABASE);
mysqli_set_charset($dbc, 'UTF8');

if (isset($_SESSION['userId'])) {
	$userId = $_SESSION['userId'];
}

$newDate =  date("Y-m-d H:i:s");
$tagsAccepted = '<div><p><span><br><a><img><h1><h2><h3><h4><ul><ol><li><blockquote>';
$questionArtTypeId = 9;

function datEscaping($string) {
	global $dbc; 
	if (get_magic_quotes_gpc()) {
		$string = stripslashes($string);
	}
	$string = trim($string);
	$string = mysqli_real_escape_string($dbc, $string);
	return $string;
}

function datGettingRawText($string) {
	$string = strip_tags($string);
	$string = str_replace('/', '', $string);
	$string = datEscaping($string);
	return $string;
}

function datFetchingUnclean($query) {
	global $dbc; 
	$rows = [];
	$r = mysqli_query($dbc, $query);
	while ($row = mysqli_fetch_assoc($r)) {
		$rows[] = $row;
	}
	if (count($rows) === 0) {
		return false;
	} else {
		return $rows;
	}
}

function datFetching($query, $specialField = '') {
	global $dbc; 
	$rows = [];
	$r = mysqli_query($dbc, $query);
	while ($row = mysqli_fetch_assoc($r)) {
		$rows[] = $row;
	}
	if (count($rows) === 0) {
		return false;
	} else if (count($rows) === 1) {
		if (empty($specialField)) {
			return $rows[0];
		} else {
			return $rows[0][$specialField];
		}
	} else if (count($rows) > 1) {
		return $rows;
	}
}

function datReturningCompletionStatus() {
	global $dbc;
	if (mysqli_affected_rows($dbc) === 1) {
		return true;
	} else {
		return false;
	}
}

function datReturningExistenceChecking($counter) {
	if ($counter >= 1) {
		return true;
	} else {
		return false;
	}	
}

function datPostingTopic($heading, $checkCode) {
	global $dbc, $userId, $newDate; 	
	$qT = "INSERT INTO topics (user_id, date_of_topic, heading, check_code) VALUES ('$userId', '$newDate', '$heading', '$checkCode')";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datFetchingTopicId($checkCode) {
	$q = "SELECT topics.topic_id FROM topics WHERE topics.check_code = '$checkCode'";
	$r = datFetching($q, 'topic_id');
	return $r;
}

function datClearingTopicCode($code) {
	global $dbc; 
	$q = "UPDATE topics SET topics.check_code = 'null' WHERE topics.check_code ='$code'";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datFetchingArticles4ThisTopic($topicId) {
	$q = "SELECT articles.article_id, articles.of_topic, articles.date_of_article, articles.content, users.user_name,  article_types.article_type_name FROM articles INNER JOIN users ON articles.user_id = users.user_id INNER JOIN articles_article_types ON articles.article_id = articles_article_types.article_id INNER JOIN article_types ON articles_article_types.article_type_id = article_types.article_type_id WHERE articles.of_topic='".$topicId."' ORDER BY FIELD(article_types.article_type_name, 'QUESTION','LAW', 'COURT RULLING', 'Administrative ruling', 'Report', 'Referer', 'Adviser', 'Thinker', 'Personal Helper')";
	$rs = datFetching($q);
	return $rs;
}

function datFetchingArtTypesInArray() {
	$artTypes = array();
	$q = "SELECT article_types.article_type_id, article_types.article_type_name FROM article_types WHERE article_types.article_type_name<>'Question'";
	$rs = datFetching($q);
	if ($rs === false) {
		return false;
	} else {
		foreach ($rs as $r) {
			$artTypes[$r['article_type_id']] = $r['article_type_name'];
		}
		return $artTypes;
	}
}

function datPostingArt($topicId, $content, $checkCode) {
	global $dbc, $userId, $newDate; 
	$q = "INSERT INTO articles (user_id, of_topic, date_of_article, content, check_code) VALUES ($userId, $topicId, '$newDate', '$content', '$checkCode')";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datFetchingArtId($code) {
	global $dbc; 
	$q = "SELECT articles.article_id FROM articles WHERE articles.check_code = '$code'";
	$rs = datFetching($q, 'article_id');
	return $rs;
}

function datPostingArtArtType($artId, $typeId) {
	global $dbc; 
	$q = "INSERT INTO articles_article_types (article_id, article_type_id) VALUES ($artId, $typeId)";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datClearingArtCode($code) {
	global $dbc; 
	$q = "UPDATE articles SET articles.check_code = 'null' WHERE articles.check_code ='$code'";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datFetchingReviews4ThisArt($artId) {
	$q = "SELECT reviews.review_id, reviews.of_article, reviews.date_of_review, reviews.content, users.user_name FROM reviews INNER JOIN users ON reviews.user_id = users.user_id WHERE reviews.of_article = $artId";
	$rs = datFetching($q);
	return $rs;
}

function datPostingReview($articleId, $content, $checkCode) {
	global $dbc, $userId, $newDate; 
	$q = "INSERT INTO reviews (user_id, of_article, date_of_review, content, check_code) VALUES ('$userId', '$articleId', '$newDate', '$content', '$checkCode')";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datFetchingOneRawReview($checkCode) {
	global $dbc; 
	$q = "SELECT reviews.review_id, reviews.date_of_review, reviews.content, users.user_name FROM reviews INNER JOIN users ON reviews.user_id = users.user_id WHERE reviews.check_code = '$checkCode'";
	$rs = datFetching($q);
	return $rs;
}

function datClearingReviewCode($code) {
	global $dbc; 
	$q = "UPDATE reviews SET reviews.check_code = 'null' WHERE reviews.check_code ='$code'";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datFetchingGoodVoteNum($artId) {
	global $dbc; 
	$q = "SELECT COUNT(votes.value) as num FROM votes WHERE votes.of_article =$artId AND votes.value='UP'";
	$rs = datFetching($q);
	return $rs;
}

function datFetchingBadVoteNum($artId) {
	global $dbc; 
	$q = "SELECT COUNT(votes.value) as num FROM votes WHERE votes.of_article =$artId AND votes.value='DOWN'";
	$rs = datFetching($q);
	return $rs;
}

function datFetchingUserVotedThisArt($artId) {
	global $dbc, $userId; 
	$q = "SELECT votes.value FROM votes WHERE votes.user_id = $userId AND votes.of_article = $artId";
	$rs = datFetching($q, 'value');
	return $rs;
}

function datPostingVote($artId, $value) {
	global $dbc, $userId, $newDate; 
	$q = "INSERT INTO votes (user_id, of_article, date_of_vote, value) VALUES ($userId, $artId, '$newDate', '$value')";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datUpdatingVote($artId, $value) {
	global $dbc, $userId; 
	$q = "UPDATE votes SET votes.value= '$value' WHERE votes.user_id = $userId AND votes.of_article = $artId";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datUnVoting ($artId) {
	global $dbc, $userId, $newDate; 
	$q = "DELETE FROM votes WHERE votes.user_id = $userId AND votes.of_article = $artId";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datFetchingTopicSet($topicId='', $offSet='', $topicsPerPage='') {
	$components = array(
		"select" => "SELECT topics.topic_id, topics.heading, topics.date_of_topic, users.user_name, GROUP_CONCAT(' ', vtt.tag_name) AS tagsName",
		"from" => "FROM (SELECT topics_tags.topic_id, tags.tag_name FROM topics_tags JOIN tags ON tags.tag_id = topics_tags.tag_id GROUP BY topic_id ASC, tag_name HAVING COUNT(user_id) >= 2 ORDER BY COUNT(user_id)) AS vtt",
		"join1" => "RIGHT JOIN topics ON topics.topic_id = vtt.topic_id",
		"join2" => "JOIN users ON users.user_id = topics.user_id"
		);
	
	if (!empty($topicId)) { 
		$components["topicId"] = "WHERE topics.topic_id=$topicId";
	};
	$components["group"] = "GROUP BY topic_id";
	$components["order"] = "ORDER BY date_of_topic DESC";
	
	if (!empty($offSet) && !empty($topicsPerPage)) {
		$components["limit"] = "LIMIT $offSet, $topicsPerPage";
	};
	
	$components["end"] = ";";
	
	$q = implode(" ", $components);
	$rs = datFetching($q);
	return $rs;
}

function datFetchingTagID($tagname) {
	$q = "SELECT tags.tag_id FROM tags WHERE tags.tag_name = '$tagname'";
	$rs = datFetching($q);
	return $rs;
}

function datFetchingUserHasTaggedThisYet($topicId, $tagId) {
	global $userId;
	$q = "SELECT topics_tags.user_id, topics_tags.topic_id, topics_tags.tag_id  FROM topics_tags WHERE topics_tags.user_id = $userId AND topics_tags.topic_id = $topicId AND topics_tags.tag_id = $tagId";
	$rs = datFetching($q);
	return $rs;
}

function  datPostingTag($topicId, $tagId) {
	global $dbc;
	global $userId;
	$q = "INSERT INTO topics_tags (user_id, topic_id, tag_id) VALUES ($userId, $topicId, $tagId)";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datPostingTagLabel($tag, $checkCode) {
	global $dbc;
	$q = "INSERT INTO tags (tag_name, check_code) VALUES ('$tag', '$checkCode')";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datFechingTagIdByCode($checkCode) {
	$q = "SELECT tags.tag_id FROM tags WHERE tags.check_code = '$checkCode'";
	$rs = datFetching($q, 'tag_id');
	return $rs;
}

function datClearingTagCode($checkCode) {
	global $dbc;
	$q = "UPDATE tags SET tags.check_code WHERE tags.check_code = '$checkCode'";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datFetchingProjects() {
	global $userId;
	$q = "SELECT users.user_id, users.user_name, projects.project_id, projects.project_name, projects.project_date, users_projects.rel_pos_of_same_user FROM users_projects JOIN users ON users.user_id = users_projects.user_id JOIN projects ON users_projects.project_id = projects.project_id WHERE users.user_id = $userId ORDER BY rel_pos_of_same_user ASC";
	$rs = datFetchingUnclean($q);
	return $rs;
}

function datFetchingTopics4ThisProject($pID) {
	global $userId;
	$q = "SELECT topics.topic_id, topics.date_of_topic, topics.heading, users.user_name FROM projects_topics JOIN topics ON topics.topic_id = projects_topics.topic_id JOIN users ON topics.user_id = users.user_id WHERE projects_topics.project_id = $pID ORDER BY projects_topics.rel_pos_of_same_project ASC";
	$rs = datFetching($q);
	return $rs;
}

function datFetchingProjectNames4ThisUser() {
	global $userId;
	$q = "SELECT users_projects.project_id, projects.project_name FROM users_projects JOIN projects ON projects.project_id = users_projects.project_id WHERE users_projects.user_id = $userId ORDER BY users_projects.rel_pos_of_same_user ASC";
	$rs = datFetching($q);
	return $rs;
}

function datFetchingUserHasThisProjectYet($projectId) {
	global $dbc, $userId;
	$q = "SELECT COUNT(users_projects.involved_id) as num FROM users_projects WHERE users_projects.user_id = $userId AND users_projects.project_id = $projectId";
	$rs = datFetching($q, 'num');
	datReturningExistenceChecking($rs);
}

function datFetchingProjectHasThisTopicYet($projectId, $topicId) {
	global $dbc, $userId;
	$q = "SELECT COUNT(projects_topics.involved_id) as num FROM projects_topics WHERE projects_topics.project_id = $projectId AND projects_topics.topic_id = $topicId";
	$rs = datFetching($q, 'num');
	datReturningExistenceChecking($rs);
}

function datFetchingProjectHasAnyTopicYet($projectId) {
	global $dbc, $userId;
	$q = "SELECT COUNT(projects_topics.involved_id) as num FROM projects_topics WHERE projects_topics.project_id = $projectId";
	$rs = datFetching($q, 'num');
	datReturningExistenceChecking($rs);
}

function datFetchingMaxPosTopicInProject($projectId) {
	$q = "SELECT MAX(projects_topics.rel_pos_of_same_project) AS num FROM projects_topics WHERE projects_topics.project_id = $projectId";
	$rs = datFetching($q, 'num');
	return $rs + 1;
}

function datPostingTopicToProject($projectId, $topicId, $relPos) {
	global $dbc, $userId, $newDate;
	$q = "INSERT INTO projects_topics (project_id, topic_id, date_of_taking, rel_pos_of_same_project) VALUES ($projectId, $topicId, '$newDate', $relPos)";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datFetchingProjectNameExisted($projectName) {
	global $dbc;
	$q = "SELECT projects.project_id FROM projects WHERE projects.project_name = '$projectName'";
	mysqli_query($dbc, $q);
	if (mysqli_affected_rows($dbc) > 0) {
		return true;
	} else {
		return false;
	}
}

function datPostingProject($projectName, $checkCode) {
	global $dbc, $newDate;
	$q = "INSERT INTO projects (project_name, project_date, check_code) VALUES ('$projectName', '$newDate', '$checkCode')";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datFetchingProjectIDByCode($checkCode) {
	$q = "SELECT projects.project_id FROM projects WHERE projects.check_code = '$checkCode'";
	$rs = datFetching($q, 'project_id');
	return $rs;
}

function datFetchingUserHasAnyProject() {
	global $dbc, $userId;
	$q = "SELECT COUNT(users_projects.involved_id) AS num FROM users_projects WHERE users_projects.user_id = $userId";
	$rs = datFetching($q, 'num');
	datReturningExistenceChecking($rs);
}

function datFetchingMaxPosProjectByUser() {
	global $dbc, $userId;
	$q = "SELECT MAX(users_projects.rel_pos_of_same_user) AS num FROM users_projects WHERE users_projects.user_id = $userId";
	$rs = datFetching($q, 'num');
	return $rs + 1;
}

function datPostingUserRegisterProject($projectId, $relPos) {
	global $dbc, $userId;
	$q = "INSERT INTO users_projects (user_id, project_id, rel_pos_of_same_user) VALUES ($userId, $projectId, $relPos)";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datClearingProjectCode($checkCode) {
	global $dbc;
	$q = "UPDATE projects SET projects.check_code = 'null' WHERE projects.check_code = '$checkCode'";
	mysqli_query($dbc, $q);
	return datReturningCompletionStatus();
}

function datFetchingPos4ThisProject($projectId) {
	$relPos = false;
	if (datFetchingUserHasAnyProject($projectId)) {
		$relPos = datFetchingMaxPosProjectByUser($projectId);
	} else {
		$relPos = 0;
	}
	if (is_numeric($relPos)) {
		return $relPos;
	} else {
		return false;
	}
}

function datFetchingPos4ThisTopic($projectId) {
	$relPos = false;
	if (datFetchingProjectHasAnyTopicYet($projectId)) {
		$relPos = datFetchingMaxPosTopicInProject($projectId);
	} else {
		$relPos = 0;
	}
	if (is_numeric($relPos)) {
		return $relPos;
	} else {
		return false;
	}
}

function datRegisteringProject2User($projectName, $checkCode) {
	if (datPostingProject($projectName, $checkCode)) {
		$projectId = datFetchingProjectIDByCode($checkCode);
		$relPos = datFetchingPos4ThisProject($projectId);
		if (datPostingUserRegisterProject($projectId, $relPos)) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function datRegisteringTopic2Project($projectId, $topicId) {
	if (datFetchingUserHasThisProjectYet($projectId) && !datFetchingProjectHasThisTopicYet($projectId, $topicId)) {
		$relPos = datFetchingPos4ThisTopic($projectId);
		if (datPostingTopicToProject($projectId, $topicId, $relPos)) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function datUpdatingProjectPos($projectId, $projectPos) {
	global $dbc, $userId;
	if (datFetchingUserHasThisProjectYet($projectId)) {
		$q = "UPDATE users_projects SET users_projects.rel_pos_of_same_user = $projectPos WHERE users_projects.user_id = $userId AND users_projects.project_id = $projectId";
		mysqli_query($dbc, $q);
		if (mysqli_affected_rows($dbc) === 0 || mysqli_affected_rows($dbc) === 1) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function datUpdatingTopicPos($projectId, $topicId, $topicPos) {
	global $dbc;
	if (datFetchingProjectHasThisTopicYet($projectId, $topicId)) {
		$q = "UPDATE projects_topics SET projects_topics.rel_pos_of_same_project = $topicPos WHERE projects_topics.project_id = $projectId AND projects_topics.topic_id = $topicId";
		mysqli_query($dbc, $q);
		return datReturningCompletionStatus();
	} else {
		if (datRegisteringTopic2Project($projectId, $topicId)) {
			$q = "UPDATE projects_topics SET projects_topics.rel_pos_of_same_project = $topicPos WHERE projects_topics.project_id = $projectId AND projects_topics.topic_id = $topicId";
			mysqli_query($dbc, $q);
			return datReturningCompletionStatus();
		}
	}
}

function datRemovingTopicOutProject($projectId, $topicId) {
	global $dbc, $userId;
	if (datFetchingUserHasThisProjectYet($projectId) && datFetchingProjectHasThisTopicYet($projectId, $topicId)) {
		$q = "DELETE FROM projects_topics WHERE projects_topics.project_id = $projectId AND projects_topics.topic_id = $topicId";
		mysqli_query($dbc, $q);
		return datReturningCompletionStatus();
	} else {
		return false;
	}
}

function datRemovingProject($projectId) {
	global $dbc, $userId;
	if (datFetchingUserHasThisProjectYet($projectId)) {
		$q = "DELETE FROM projects_topics WHERE projects_topics.project_id = $projectId";
		mysqli_query($dbc, $q);
		$firstCondition = datReturningCompletionStatus();
		$q = "DELETE FROM users_projects WHERE users_projects.project_id = $projectId";
		mysqli_query($dbc, $q);
		$secondCondition = datReturningCompletionStatus();
		$q = "DELETE FROM projects WHERE projects.project_id = $projectId";
		mysqli_query($dbc, $q);
		$thirdCondition = datReturningCompletionStatus();
		if ($firstCondition === true & $secondCondition === true && $thirdCondition === true) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}