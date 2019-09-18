$(document).ready(function(){
	displayingchangingProjectPositionBtn();
	movingProjects();
	movingTopics();
	makingProjectAndTopicsSortable();
	savingPosition();
	displayingDeletingBtns();
	deletingProject();
	deletingTopic();
	displayingNewProjectBtns();
	acceptingNewProject();
});

var changingProjectPositionBtnStatus = false;
function displayingchangingProjectPositionBtn() {
	$("#changingProjectPositionBtn").on("click", function() {
		if (changingProjectPositionBtnStatus === false) {
			$(".changingProjectPositionFunc").show();
			changingProjectPositionBtnStatus = true;
		} else {
			$(".changingProjectPositionFunc").hide();
			changingProjectPositionBtnStatus = false;
		}
	})
}

function makingProjectAndTopicsSortable() {
	$("#projectSetContainer").sortable({
		item: ".projectContainer",
		placeholder: "ui-state-highlight",
		start: function(e, ui){
        	ui.placeholder.height(ui.item.height()/2);
		}

	});
	$(".topicsListingContainer").sortable({
		item: ".topicContainer",
		connectWith: $(".topicsListingContainer"),
		placeholder: "ui-state-highlight",
		start: function(e, ui){
        	ui.placeholder.height(ui.item.height()/2);
			if (typeof ui.item.data('originalProject') == 'undefined') {
				ui.item.data('originalProject', ui.item.parents(".projectContainer").find(".projectId").val());
				}
		}
	});
}

function movingProjects() {
	$(".projectHeader").on("click", ".upProjectBtn", function() {
		var wrapper = $(this).parents(".projectContainer");
		$(wrapper).insertBefore($(wrapper).prev());
	})
	$(".projectHeader").on("click", ".downProjectBtn", function() {
		var wrapper = $(this).parents(".projectContainer");
		$(wrapper).insertAfter($(wrapper).next());
	})   
}

function movingTopics() {
	$(".topicHeading").on("click", ".upTopicBtn", function() {
		var wrapper = $(this).parents(".topicContainer");
		$(wrapper).insertBefore($(wrapper).prev());
	})
	$(".topicHeading").on("click", ".downTopicBtn", function() {
		var wrapper = $(this).parents(".topicContainer");
		$(wrapper).insertAfter($(wrapper).next());
	})
}

function savingPosition() {  
	$("#editorProjectContainer").on("click", "#savingProjectPositionBtn",function() {
		var projectList = $(".projectContainer");
		var projectPosList = [];
		$(projectList).each(function(i){
			var topicPosList = [];
			$(this).find(".topicContainer").each(function(j){
				var originalProject = false;
				if ($(this).data('originalProject') !== 'undefined') {
					var originalProject = $(this).data('originalProject');
					}
				var topic = {
					topicId: $(this).find(".topicIdRef").val(),
					relPosT: j,
					originalProject: originalProject
				};
				topicPosList.push(topic);
			});
			
			var project = {
				projectId: $(this).find(".projectId").val(),
				relPosP: i,
				childTopics: topicPosList
			};
			projectPosList.push(project);
		})
		var url = "project_service.php";
		var data = {
			cmd: '5',
			projectPosList: projectPosList
		};
		$.post(url, data, function(data){
			$(".topicContainer").removeData('originalProject');
		});
	});
};

var removingBtnStatus = false;
function displayingDeletingBtns() {
	$("#deletingProjectBtn").on("click", function(){
		if (removingBtnStatus === false) {
			$(".deletingProjectFunc").show();
			removingBtnStatus = true;
		} else {
			$(".deletingProjectFunc").hide();
			removingBtnStatus = false;
		}
	})
}

function deletingProject() {
	$(".deletingProject").on("click", function(){
		var btn = $(this);
		var projectId = $(btn).parents(".projectContainer").find(".projectId").val();
		var url = "project_service.php";
		var data = {
			cmd: '6',
			projectId: projectId
		};
		$.post(url, data, function(data){
			$(btn).parents(".projectContainer").remove();
		});
	})
}

function deletingTopic() {
	$(".deletingTopic").on("click", function(){
		var btn = $(this);
		$(btn).prop("disabled", true);
		var projectId = $(btn).parents(".projectContainer").find(".projectId").val();
		var topicId = $(btn).parents(".topicContainer").find(".topicIdRef").val();
		var url = "project_service.php";
		var data = {
			cmd: '7',
			projectId: projectId,
			topicId: topicId
		};
		$.post(url, data, function(data){
			$(btn).parents(".topicContainer").remove();
		});

	})
}

var newProjectBtnStatus = false;
function displayingNewProjectBtns() {
	$("#addingNewProjectBtn").on("click", function(){
		if (newProjectBtnStatus === false) {
			$(".addingNewProjectFunc").show();
			newProjectBtnStatus = true;
		} else {
			$(".addingNewProjectFunc").hide();
			newProjectBtnStatus = false;
		}
	})
}

function acceptingNewProject() {
	$(".acceptingNewProjectBtn").on("click", function(){
		var btn = $(this);
		var container = $(btn).parents(".addingNewProjectFunc");
		var input = $(container).find(".inputtingNewProjectFunc");
		var projectName = $(input).val();
		var url = "project_service.php";
		var data = {
			cmd: '8',
			projectName: projectName,
		};
		$.post(url, data, function(data){
			window.location.reload();
		});
	})
}





