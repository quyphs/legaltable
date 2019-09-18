$(document).ready(function(){
	startingUp();
});

function startingUp() {
	establishingNavBar();
	showingProjectNames();
	acceptingThisCurrentProject();
	creatingNewProject();
	cancellingNewProjectInput();
	acceptingThisNewProject();
}

function establishingNavBar() {
	$("#menuBar").menu({
		position: {
			my: 'center top',
			at: 'center bottom'
		}
	});
}

function showingProjectNames() {
	$(".topicContainer").on("click", ".addingToProjectBtn", function() {
		var btn = $(this);
		$(btn).prop("disabled", true);
		var tagsContainer = $(btn).parents(".topicTagsContainer");
		var url = "project_service.php";
		var data = {
			cmd: '1'
		};
		if ($(tagsContainer).find(".addingToProjectFunc").length !== 0) {
			$(tagsContainer).find(".addingToProjectFunc").remove();
		} else {
			$.post(url, data, function(data) {
				$(tagsContainer).append(data);
				
			});
		};
		setTimeout(function(){ $(btn).prop("disabled", false); }, 1000);
	});
};

function acceptingThisCurrentProject() {
	$(".topicContainer").on("click", ".curProjectBtn", function(){
		var btn = $(this);
		$(btn).prop("disabled", true);
		var tagsContainer = $(btn).parents(".topicTagsContainer");
		var topicId = $(btn).parents(".topicContainer").find(".topicIdRef").val();
		var projectId = $(btn).val();
		var url = "project_service.php";
		var data = {
			cmd: '2',
			topicId: topicId,
			projectId: projectId
		}
		$.post(url, data, function(data) {
			if (data == 1) {
				var firstLabel = $(btn).text();
				$(btn).text("...adding!");
				setTimeout(function(){ 
					$(btn).text(firstLabel);
					$(btn).prop("disabled", false);
				}, 2000);
			}
		});
		
	})
}

function creatingNewProject() {
	$(".topicContainer").on("click", ".newProjectBtn", function(){
		var btn = $(this);
		$(btn).prop("disabled", true);
		var tagsContainer = $(btn).parents(".topicTagsContainer");
		var url = "project_service.php";
		var data = {
			cmd: '3',
			newProjectInputSeries: true
		};
		$.post(url, data, function(data) {
			var newProjectInput = data;
			if ($(tagsContainer).find(".newProjectFunc").length === 0) {
				$(btn).after($(newProjectInput));
				setTimeout(function(){ $(btn).prop("disabled", false); }, 2000);
			}
		});
		
	});
}

function cancellingNewProjectInput() {
	$(".topicContainer").on("click", ".cancellingProjectBtn", function(){
		var btn = $(this);
		$(btn).prop("disabled", true);
		var tagsContainer = $(btn).parents(".topicTagsContainer");
		$(tagsContainer).find(".addingToProjectFunc").remove();
		setTimeout(function(){ $(btn).prop("disabled", false); }, 2000);
	});
}

function acceptingThisNewProject() {
	$(".topicContainer").on("click", ".acceptingProjectBtn", function(){
		var btn = $(this);
		var parentBtn = $(btn).siblings(".newProjectBtn");
		$(parentBtn).prop("disabled", true);
		var tagsContainer = $(btn).parents(".topicTagsContainer");
		var topicId = $(btn).parents(".topicContainer").find(".topicIdRef").val();
		var projectName = $(btn).siblings(".inputingProjectBtn").val();
		var url = "project_service.php";
		var data = {
			cmd: '4',
			topicId: topicId,
			projectName: projectName
		};
		$.post(url, data, function(data) {
			if (data == 1) {
				$(tagsContainer).find(".newProjectFunc").remove();
				var firstLabel = $(parentBtn).text();
				$(parentBtn).text("...adding!");
				setTimeout(function(){ 
					$(parentBtn).text(firstLabel);
					$(parentBtn).prop("disabled", false);
					$(".addingToProjectFunc").remove();
				}, 2000);
				
			}
		});
	});
};




