<?php
include_once(MYSQL);
include_once(FORMS_FUNCTIONS);
	
function replacingHoldersInProto($proto, $replacements) {
	$r = preg_replace_callback("/@(\w+)/", function($matches) use (&$replacements) {return array_shift($replacements);}, $proto);;
	return $r;
}

function publishingTopicSet($page) {
	global $topicSetProto;
	$topics = '';
	foreach (collectingPagedTopicSet($page) as $t) {
		$topics .= structuringTopic($t);
	}
	return structuringTopicSet($topics);
}

function collectingPagedTopicSet($page) {
	global $topicsPerPage;
	if ($page == 1) {
		$offSet = 0;
	} else {
		$offSet = (($page-1)*$topicsPerPage);
	}
	$rs = datFetchingTopicSet('', $offSet, $topicsPerPage);
	return $rs;
}

function structuringTopicTags($tagString) {
	global $topicTagProto;
	$tags = '';
	$tagsSlices = explode(", ", $tagString);
	foreach ($tagsSlices as $t) {
		$replaces = array(trim($t));
		$tags .= replacingHoldersInProto($topicTagProto, $replaces);
	}
	return $tags;
}

function structuringTopic($topicR) {
	global $topicProto;
	if (isset($topicR["tagsName"])) {
		$tags = structuringTopicTags($topicR["tagsName"]);
	} else {
		$tags = structuringTopicTags("Not tagged");
	}
	$replaces = array($topicR['topic_id'], $topicR['topic_id'], $topicR['heading'], $topicR['user_name'], $topicR['date_of_topic'], $tags);
	$topicF = replacingHoldersInProto($topicProto, $replaces);
	return $topicF;
}

function structuringTopicSet($topicsR) {
	global $topicSetProto;
	$replaces = array($topicsR);
	$topicSet = replacingHoldersInProto($topicSetProto, $replaces);
	return $topicSet;
}

function publishingOneReview($r) {
	global $reviewProto;
	$replaces = array($r[0]['user_name'], $r[0]['date_of_review'], $r[0]['content']);
	$review = replacingHoldersInProto($reviewProto, $replaces);
	return $review;
}

function publishingArticles($topicId) {	
	$topicStepIn = '';
	
	$topic = publishingTopicBoard($topicId);
	$topicStepIn .= $topic;
	$articles = publishingArticle4ThisTopic($topicId);
	$topicStepIn .= $articles;
	
	echo $topicStepIn;
}

function publishingTopicBoard($topicId) {
	global $topicInProto;
	$row = datFetchingTopicSet($topicId);
	
	if (isset($row["tagsName"])) {
		$tags = structuringTopicTags($row["tagsName"]);
	} else {
		$tags = '';
	}
 	
	$topicHiddenID = creatingInput('', 'topicID', '', 'hidden', '', array(), array('value'=>$row["topic_id"]));	
	
	$tagsFuncsBtn = '';
	if (isset($_SESSION['userId'])) {
		$tagsFuncsBtn .= creatingButton('tags', 'addingTag', '', '', 'Tag');	
	}
	
	$replaces = array($topicHiddenID, $row['heading'], $row['user_name'], $row['date_of_topic'], $tags, $tagsFuncsBtn);
	$set = replacingHoldersInProto($topicInProto, $replaces);
	return $set;
}

function publishingArticle4ThisTopic($topicId) {
	global $articleProto;
	
	$wholeSet = '';
	$rows = datFetchingArticles4ThisTopic($topicId);
	if ($rows != false) {
		foreach ($rows as $row) {
			$artId = $row['article_id'];
			$ID = publishingArtID($artId);
			$bar = publishingVoteBar($artId);
			$artIntro = publishingArticleIntro($row['content']);
			$reviewSet = structuringReviewSet4Article($artId);

			$replaces = array();
			$replaces = array($ID, $bar, $row['article_type_name'], $row['user_name'], $row['date_of_article'], $artIntro, $row['content'], $reviewSet);
			$wholeSet .= replacingHoldersInProto($articleProto, $replaces);
		}
	}
	return $wholeSet;
}

function publishingArtID($artId) {
	$set = creatingInput('artId4Vote', '', 'artId4Vote', 'hidden', '', array(), array('value'=>$artId));
	return $set;
}

function publishingVoteBar($artId) {
	global $voteBarProto;
	
	$goodVote = datFetchingGoodVoteNum($artId);
	if ($goodVote === false) {
		$goodVote = 0;
	} else {
		$goodVote = $goodVote['num'];
	}
	$badVote = datFetchingBadVoteNum($artId);
	if ($badVote === false) {
		$badVote = 0;
	} else {
		$badVote = $badVote['num'];
	}

	$voteButtons = '';
	if (isset($_SESSION['userId'])) {
		$voted = datFetchingUserVotedThisArt($artId);
		if ($voted === false) {
			$voteButtons .= creatingButton('goodVoting', '', '', '', 'Good');
			$voteButtons .= creatingButton('badVoting', '', '', '', 'Bad');
		} else if ($voted === 'DOWN') {
			$voteButtons .= creatingButton('goodVoting', '', '', '', 'Good');
			$voteButtons .= creatingButton('unVoting', '', '', '', 'Unvoting');
		} else if ($voted === 'UP') {
			$voteButtons .= creatingButton('badVoting', '', '', '', 'Bad');
			$voteButtons .= creatingButton('unVoting', '', '', '', 'Unvoting');
		}
	}
	
	$replaces = array();
	$replaces = array($goodVote, $badVote, $voteButtons);
	$bar = replacingHoldersInProto($voteBarProto, $replaces);
	
	return $bar;
}

function publishingArticleIntro($content) {
	$set = str_replace('<',' <', $content);
	$set = strip_tags($set);
	$set = substr($set, 0, 80);
	return $set;
}

function structuringReviewSet4Article($artId) {
	global $reviewProto;
	$set = '';
	$rows = datFetchingReviews4ThisArt($artId);
	if ($rows !== false) {
		if (!empty($rows['user_name'])) {
				$replaces = array();
				$replaces = array($rows['user_name'], $rows['date_of_review'], $rows['content']);
				$set .= replacingHoldersInProto($reviewProto, $replaces);
		} else {
			foreach ($rows as $row) {
				$replaces = array();
				$replaces = array($row['user_name'], $row['date_of_review'], $row['content']);
				$set .= replacingHoldersInProto($reviewProto, $replaces);
			}
		}
	}
	if (isset($_SESSION['userId'])) {
		$inputSeries = '';
		$inputSeries .= creatingInput('', '', 'articleId', 'hidden', '', array(), array('value'=>$artId));
		$inputSeries .= creatingInput('', '', 'review', 'text', 'Your comment:');
		$inputSeries .= creatingSubmit('', 'reviewPost', 'reviewPost', 'Post');
		$inputForm = creatingFormP('reviewPostForm', 'reviews.php', $inputSeries);
		$set .= $inputForm;
	}
	return $set;
}

function creatingEditor($selector) {
	global $editorProto;
	$replaces = array($selector);
	$editor = replacingHoldersInProto($editorProto, $replaces);
	return $editor;
}

function publishingProjects() {
	global $projectProto;
	global $projectSetProto;
	
	$rs = datFetchingProjects();
	$projectF1 = structuringProject($rs);
	$projectF2 = structuringProjectSet($projectF1);
	
	return $projectF2;
}

function structuringProject($rawSet) {
	global $projectProto;
	$project = "";
	if ($rawSet != false) {
		foreach ($rawSet as $r) {
			$projectHeader = $r['project_name'];
			$projectId =  $r['project_id'];
			$topicRs = datFetchingTopics4ThisProject($projectId);
			$finedTopics = '';
			if ($topicRs != false) {
				foreach ($topicRs as $t) {
				$finedTopics .= structuringTopic($t);
				}
			}
			$topicSet = structuringTopicSet($finedTopics);
			$replaces = array($projectId, $projectHeader, $topicSet);			
			$project .= replacingHoldersInProto($projectProto, $replaces);
		}
	}
	return $project;
}

function structuringProjectSet($set) {
	global $projectSetProto;
	$replaces = array($set);
	$projectSet = replacingHoldersInProto($projectSetProto, $replaces);
	return $projectSet;	
}

function structuringProjectNames($rs) {
	global $projectNameProto, $newProjectNameProto;
	$projectNames = '';
	if ($rs != false) {
		foreach ($rs as $r) {
		$projectId = $r['project_id'];
		$projectName = $r['project_name'];
		$replaces = array($projectId, $projectName);
		$projectNames .= replacingHoldersInProto($projectNameProto, $replaces);
		}	
	}
	$projectNames .= $newProjectNameProto;
	return $projectNames;
}

function structuringNavigationBar() {
	global $navProto, $subNavProto, $navBarProto, $pages, $thisPageUrl;
	$navs = '';
	
	foreach ($pages as $title => $url) {
		if (is_array($url)) {

			$activeClass = "";
			if ($title == $thisPageUrl) {
				$activeClass = "active";
			}

			$subNavs = "";
			foreach ($url as $title1 => $url1) {
				$activeClass1 = "";
				$subMenuItems1 = "";
				$replaces = array($activeClass, $url1, $title1, $subMenuItems1);
				$subNavs .= replacingHoldersInProto($navProto, $replaces);
			}

			$replaces = array();
			$replaces = array($subNavs);
			$subMenuItems = replacingHoldersInProto($subNavProto, $replaces);

			$replaces = array();
			$replaces = array($activeClass, "#", $title, $subMenuItems);
			$navs .= replacingHoldersInProto($navProto, $replaces);
	   } else {
			$activeClass = "";
			if ($url == $thisPageUrl) {
				$activeClass = "active";
			}
			$subMenuItems = "";
			$replaces = array();
			$replaces = array($activeClass, $url, $title, $subMenuItems);
			$navs .= replacingHoldersInProto($navProto, $replaces);
	   }
	}
	
	$replaces = array($navs);
	$navBar = replacingHoldersInProto($navBarProto, $replaces);
	return $navBar;
}

function publishingNavigationBar() {
	return structuringNavigationBar();
}