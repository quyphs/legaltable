<?php
	require('includes/config.inc.php');
	redirectInvalidUser();
	require(MYSQL);
	require(HTML_PROTO);
	require(HTML_FUNCTIONS);
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		
		if (isset($_POST['cmd']) && $_POST['cmd'] === '1') {
			$rs = datFetchingProjectNames4ThisUser();
			$projectNames = structuringProjectNames($rs);
			echo $projectNames;
		}
		
		if (isset($_POST['cmd']) && $_POST['cmd'] === '2') {
			if (isset($_POST['topicId']) && isset($_POST['projectId'])) {
				$projectId = $_POST['projectId'];
				$topicId = $_POST['topicId'];
				if (datRegisteringTopic2Project($projectId, $topicId)) {
					echo 1;
				}
			}
		}
		
		if (isset($_POST['cmd']) && $_POST['cmd'] === '3') {
			echo $newProjectInputSeries;
		}
		
		if (isset($_POST['cmd']) && $_POST['cmd'] === '4') {
			if (isset($_POST['topicId']) && isset($_POST['projectName'])) {
				$projectName = datGettingRawText($_POST['projectName']);
				$topicId = $_POST['topicId'];
				$checkCode = randomCode();
				if (datRegisteringProject2User($projectName, $checkCode)) {
					$projectId = datFetchingProjectIDByCode($checkCode);
					if (datRegisteringTopic2Project($projectId, $topicId)) {
						if (datClearingProjectCode($checkCode)) {
							echo 1;
						}
					}
				}
			}
		}
		
		if (isset($_POST['cmd']) && $_POST['cmd'] === '5') {
			$projectPosList = $_POST['projectPosList'];
			foreach ($projectPosList as $p) {
				$projectId = $p['projectId'];
				$projectPos = $p['relPosP'];
				
				$childTopics = array();
				if (isset($p['childTopics'])) {
					$childTopics = $p['childTopics'];
				}
				
				if (datUpdatingProjectPos($projectId, $projectPos)) {
					if (count($childTopics) != 0) {
						foreach ($childTopics as $t) {
							if (isset($t['topicId'])) {
								$topicId = $t['topicId'];
								$topicPos = $t['relPosT'];
								if (isset($t['originalProject'])) {
									if (datRemovingTopicOutProject($t['originalProject'], $topicId)) {
										if (datUpdatingTopicPos($projectId, $topicId, $topicPos)) {
											echo 1;
										}
									}
								} else {
									if (datUpdatingTopicPos($projectId, $topicId, $topicPos)) {
										echo 1;
									}
								}								
							}
						}	
					}
				}
			}
		}
		
		if (isset($_POST['cmd']) && $_POST['cmd'] === '6') {
			$projectId = datGettingRawText($_POST['projectId']);
			$projectId = (int)$projectId;
			datRemovingProject($projectId);
		}
		
		if (isset($_POST['cmd']) && $_POST['cmd'] === '7') {
			$projectId = datGettingRawText($_POST['projectId']);
			$projectId = (int)$projectId;
			$topicId = datGettingRawText($_POST['topicId']);
			$topicId = (int)$topicId;
			datRemovingTopicOutProject($projectId, $topicId);
		}
		
		if (isset($_POST['cmd']) && $_POST['cmd'] === '8') {
			$projectName = datGettingRawText($_POST['projectName']);
			$checkCode = randomCode();
			datRegisteringProject2User($projectName, $checkCode);
			datClearingProjectCode($checkCode);
		}
	} 
?>
