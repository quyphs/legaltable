// JavaScript Document
$(document).ready(function(){
	startingUp();
});

function startingUp() {
	establishingNavBar();
	readingArt();
	postingReview();
	goodVoting();
	badVoting();
	unVoting();
	showingTaggingForm();
	taggingTopic();
};

function establishingNavBar() {
	$("#menuBar").menu({
		position: {
			my: 'center top',
			at: 'center bottom'
		}
	});
}

var artDisplayStatus = false;
function readingArt() {
	$(".readingArt").on("click", function(){
		var btn = $(this);
		$(btn).prop("disabled", true);
		var articleContainer = $(btn).parents(".articleContainer");
		var intro = $(articleContainer).find(".articleIntroWords");
		var content = $(articleContainer).find(".articleContent");
		var reviews = $(articleContainer).find(".reviewsContainer");
		if (artDisplayStatus === false) {
			$(intro).hide(1000);
			$(content).show(1000);
			$(reviews).show(1000);
			$(btn).text("Less");
			artDisplayStatus = true;
		} else {
			$(intro).show(1000);
			$(content).hide(1000);
			$(reviews).hide(1000);
			$(btn).text("Read");
			artDisplayStatus = false;
		}
		setTimeout(function(){ $(btn).prop("disabled", false); }, 1000);
	});
};

function postingReview() {
	$(".reviewPostForm").submit("click", function(evt) {
		var form = $(this);
		var lastReview = $(form).parent().prev();
		var reviewsContainer = $(form).parents(".reviewsContainer");

		var url = "reviews.php";
		var data = $(form).serialize();
		
		$.post(url, data, function(data) {
			if (lastReview.length == 0) {
				$(reviewsContainer).prepend(data);
			} else {
				$(lastReview).after(data);
			}			
			$(form).find("input[name='review']").val("");
		});
		return false;
	});
};

function goodVoting() {
	$(".voteBar").on("click", ".goodVoting", function() {
		var btn = $(this);
		var container = $(btn).parents(".voteBar");
		
		var url = "votes.php";
		var artIdJS = $(btn).parents(".articleContainer").find(".artId4Vote").val();
		var data = ({
			type: $(btn).val(),
			artId: artIdJS
		});
		
		$.post(url, data, function(data) {
			$(container).html(data);
		});
	});
};
					  
function badVoting() {
	$(".voteBar").on("click", ".badVoting", function() {
		var btn = $(this);
		var container = $(btn).parents(".voteBar");
		
		var url = "votes.php";
		var artIdJS = $(btn).parents(".articleContainer").find(".artId4Vote").val();
		var data = ({
			type: $(btn).val(),
			artId: artIdJS
		});
		
		$.post(url, data, function(data) {
			$(container).html(data);
		});
	});
};

function unVoting() {
	$(".voteBar").on("click", ".unVoting", function() {
		var btn = $(this);
		var container = $(btn).parents(".voteBar");
		
		var url = "votes.php";
		var artIdJS = $(btn).parents(".articleContainer").find(".artId4Vote").val();
		var data = ({
			type: $(btn).val(),
			artId: artIdJS
		});
		
		$.post(url, data, function(data) {
			$(container).html(data);
		});
	});
};

var taggingBtnStatus = false;
function showingTaggingForm() {
	$("#addingTag").on("click", function(){
		var btn = $(this);
		var formsContainer = $(btn).siblings(".taggingFormsContainer");
		var url = "tagging.php";
		if (taggingBtnStatus === false) {
			$.get(url, data='', function(data) {
				$(formsContainer).html(data);
			});
			$(btn).val("Return");
			taggingBtnStatus = true;
		} else {
			$(formsContainer).html("");
			$(formsContainer).children(".form-group").remove();
			$(btn).val("Tag");
			taggingBtnStatus = false;
		}
	});
};

function taggingTopic() {
	$(".taggingFormsContainer").on("submit", ".taggingForm", function(){
		var form = $(this);
		var formsContainer = $(form).parents(".taggingFormsContainer");
		var url = "tagging.php";
		var data = $(form).serialize();
		var topicId = $(".topicContainer").find("#topicID").val();
		data += "&topicId="+topicId;
		alert(data);
		$.post(url, data, function(data) {
			$(formsContainer).html(data);
		});
		return false;
	})
}












