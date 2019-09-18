<?php
$sysErrorHelp = "Sorry. We will check it back soon!";

// header -------------------------------------------------
$navProto = 
	"
	<li class='menuItem'><a class='@active' href='@dest'>@name</a>@subMenuItem</li>
	";

$subNavProto = 
	"
	<ul class='subMenuItem'>
		@sameAsNavProto	
	</ul>
	";

$navBarProto = 
	"
	<div id='navBar'>
 		<ul id ='menuBar'>
		@listPageNavigation
	 	</ul> 	
	</div>
	";

// main page -------------------------------------------------
$topicTagProto = 
	"<button class='topicTagBtn'>@topicTag</button>";

$projectNameProto = 
	"
	<button class='addingToProjectFunc curProjectBtn' value='@projectId'>@projectName</button>
	";

$newProjectNameProto = 
	"
	<button class='addingToProjectFunc newProjectBtn' value='0'>New project</button>
	";

$newProjectInputSeries = 
"
	<input class='addingToProjectFunc newProjectFunc inputingProjectBtn' type='text' placeholder='naming it' style='width:130'>
	<button class='addingToProjectFunc newProjectFunc cancellingProjectBtn' value='1'>Cancel</button>
	<button class='addingToProjectFunc newProjectFunc acceptingProjectBtn' value='1'>OK</button>
";

$topicProto = 
	"<div class='topicContainer'>
		<input class='topicIdRef' type='hidden' value='@topicId'>
		<p class='topicHeading'>
			<a class='linkToTopic' href='topic_step_in.php?topicId=@topicId/'>@heading</a>
			<a class='editProjectFunc changingProjectPositionFunc savingProjectPositionFunc upTopicBtn' href='#' style='display:none'>U</a>
			<a class='editProjectFunc changingProjectPositionFunc savingProjectPositionFunc downTopicBtn' href='#' style='display:none'>D</a>
		</p>
		<p class='topicDetails'>@userName questioned on @topicDate</p>
		<div class='topicTagsContainer'>
			@topicTag
			".((isset($_SESSION['userId']))? 
			   "<button class='addingToProjectBtn'>+P</button>":"").((datGettingRawText($_SERVER['PHP_SELF']) === $projectPage)? "<button class='deletingProjectFunc deletingTopic' style='display:none'>-</button>":"")."
		</div>
		<input class='addTagInput' name='addTagInput' type='text' style='display:none;'>
	</div>";

$topicSetProto = 
	"<div class='topicsListingContainer'>
		@topicsSet
	</div>";		

// project page -------------------------------------------------
$projectSetProto = 
	"
	<div id='mainContainer'>
		<div id='editorProjectContainer'>
			<div id='editProjectBar'>
				<a href='#' class='editProjectFunc addingNewProjectBtn' id ='addingNewProjectBtn'>New</a>
				<a href='#' class='editProjectFunc deletingProjectBtn' id ='deletingProjectBtn'>Delete</a>
				<a href='#' class='editProjectFunc changingProjectPositionBtn' id ='changingProjectPositionBtn'>Sort</a>
			</div>
			<div id='subEditProjectBar'>
				<div class='editProjectFunc changingProjectPositionFunc' style='display:none;'><a href='#' class='editProjectFunc changingProjectPositionFunc' id='savingProjectPositionBtn'>OK</a></div>
				<div class='editProjectFunc addingNewProjectFunc' style='display:none;'>
					<p><input class='inputtingNewProjectFunc' id='' name='' type='text'></p>
					<p><button class='acceptingNewProjectBtn'>OK</button></p>
				</div>
				
			</div>
		</div>
		<div id='projectSetContainer'>@projectSet
		</div>
	</div>
	";

$projectProto = 
	"
	<div class='projectContainer'>
		<input class='projectId' type='hidden' value='@projectId'>
		<p class='projectHeader'>
			@header
			<a class='editProjectFunc changingProjectPositionFunc savingProjectPositionFunc upProjectBtn' href='#' style='display:none'>U</a>
			<a class='editProjectFunc changingProjectPositionFunc savingProjectPositionFunc downProjectBtn' href='#' style='display:none'>D</a>
			<button class='deletingProjectFunc deletingProject' style='display:none'>-</button>
		</p>
		@topicSet
	</div>
	";


// topic step in page -------------------------------------------------
$topicInProto = 
	"<div class='topicContainer'>
		@ID
		<p class='topicHeading'>@heading</p>
		<p class='topicDetails'>@userName questioned on @topicDate</p>
		<div class='topicTagsContainer'>
			@topicTag
		</div>
		<div class='taggingFuncsContainer'>
			@tagsFuncs
			<div class='taggingFormsContainer' display='none'>
				
			</div>	
		</div>

	</div>";

$easyView =
	"<div id='filterBtns'>
		<button id='filterGroupBtn'>Filter</button>
		<button class='filterBtn' id='artAllOnOffBtn'>Show 'em all</button>
		<button class='filterBtn' id='artLatestBtn'>Latest</button>
		<button class='filterBtn' id='artHighVotesBtn'>High Votes</button>
		<button class='filterBtn' id='artMostActiveBtn'>Active</button>
		<button class='filterBtn' id='artTypeCourtOnBtn'>Court</button>
		<button class='filterBtn' id='artTypeAgencyOnBtn'>Agency</button>
		<button class='filterBtn' id='artTypeLawOnBtn'>Law</button>
		<button class='filterBtn' id='artTypeReportOnBtn'>Report</button>
		<button class='filterBtn' id='artTypeRefererOnBtn'>Referer</button>
		<button class='filterBtn' id='artTypeThinkerOnBtn'>Thinker</button>
		<button class='filterBtn' id='artTypeAdviserOnBtn'>Adviser</button>
		<button class='filterBtn' id='artTypeHelperOnBtn'>Personal</button>
	</div>";

$voteBarProto = 
	"
	<p><span class='goodVotes'> @numberGoodVote <img src='../images/icon_yes.png' alt='Helpful' class='image image-assess image-yes'> </span> | <span class='badVotes'>  @numberBadVote <img src='../images/icon_no.png' alt='Helpful' class='image image-assess image-no'></span> <span class='voteBtns'> @twovotingbuttons</span></p>
	";

$articleProto =
	"<div class='articleContainer'>
		@artId4Vote
		<div class ='voteBar'>
			@voteBar
		</div>
		<div class='articleHeading'>
			<p class='articleDetails'><button class='articleType'>@articleType</button>  @userName presented this on <span class='articleDate'> @artDate </span>: <span class='articleIntroWords'>@artIntro</span></p>
			<p><button class='readingArt'>Read this</button></p>
		</div>
		<div class='articleContent'>
			@content
		</div>
		<div class='reviewsContainer'>
			@reviews
		</div>
	</div>";

$reviewProto = 
	"<div class='reviewContainer'>
		<p>@userName commented on @reviewDate: @reviewContent</p>
	</div>";

// form functions page -------------------------------------------------
$inputLabelProto = 
	"
	<label class='lbl' for='@name'>@label</label>
	";

$inputHelpProto = 
	"
	<span class='block-help'>@indicator</span>
	";

$inputProto = 
	"
	<div class='form-group @errorAlert' >
		@label
		<input class='form-control @class' id='@id' name='@name' type='@type' @otheroptions value='@value'> @errorHelp
	</div>
	";

$inputLongProto = 
	"
	<div class='form-group @errorAlert' >
		@label
		@errorHelp
		<textarea class='form-control @class' id='@id' name='@name' @otheroptions>@value</textarea>
	</div>
	";

$buttonProto = 
	"
	@label
	<input class='form-control @class' id='@id' name='@name' type='button' value='@value'>
	";

$submitProto = 
	"
	<input class='btn @className' id='@id' name='@name' type='submit' value='@value'/>
	";

$formPProto = 
	"
	<div class='form-group'>
		<form class='@furtherclass' action='@file' method='POST' accept-charset='utf-8'>
			@inputSeries
		</form>
	</div>
	";

$formGProto = 
	"
	<div class='form-group'>
		<form class='@furtherclass' action='@file' method='GET' accept-charset='utf-8'>
			@inputSeries
		</form>
	</div>
	";


// register page -------------------------------------------------
$nameRegErrorHelper = 'please check this field again';

$dobReg1ErrorHelper = 'please make sure this follows YYYY-MM-DD format';

$dobReg2ErrorHelper = 'please fill this and make sure it follows YYYY-MM-DD format';

$userNameRegErrorHelper = 'please check this field again, only letters and number allowed, ranging from 6 to 20 chars';

$emailRegErrorHelper = 'please check this email again to make sure it valid';

$passReg2ErrorHelper = 'please check this again to make sure its consistency with the password above';
	
$passReg1ErrorHelper = 'please check this field again, at least one upper letter, one digit, and one special character such as #?!@$%^&*-';

$userRegisteredReg1ErrorHelp = 'This user name has been used with this email. Please try another!.';

$userRegisteredReg2ErrorHelp = 'This user name has been used. Please try another!.';

$emailRegisteredRegErrorHelp = 'This email has been used. Please try another!. If you forget your pass, click here<a>link</a>...';

$agreedRegErrorHelper = 'You shall agree to the terms and conditions of our website';

$regSuc = '<p>You have successfully registered! Thank you so much for your time</p><p>You might take a look at your email to confirm and complete the process</p>';

// log in page -------------------------------------------------
$emailLogInErrorHelp = 'This is not a valid email. Please check it again';

$passLogInErrorHelp = 'This cannot be empty. Please check it again';

$logInErrorHelp = 'The email and password do not match together. PLease check again!';

// log out page -------------------------------------------------

$logOutSuc = 'Hope to see you soon. Thank you so much!';

// reset pass page -------------------------------------------------
$email1ResetErrorHelp = 'This is not a registered email. Please check it again';

$email2ResetErrorHelp = 'This is not a valid email. Please check it again';

// change pass page -------------------------------------------------
$pass1ChangeErrorHelp = 'Please enter your current password';

$pass2ChangeErrorHelp = "The current password has not been matched. Please check it again";

$passChangeSuc = 'Successfully changed';

// article page -------------------------------------------------
$artOptionProto = "<option value='@val'>@htmlText</option>";
$artSelectHelpProto = "<span class='block-help'>@indicator</span>";
$artSelectProto = 
"	@label
	<select class='input select @class @errorAlertIndicator' id='@id' name='@name' size='1' required @option>
		<option value='' selected disabled hidden>Choose here</option>
		@artOption
	</select>
	@errorHelp
";

// editor proto -------------------------------------------------
$editorProto = 
"
<script type='text/javascript' src='js/tinymce/tinymce.min.js'></script>
<script type='text/javascript'>
	tinyMCE.init({
		selector: '@selector',
		width: 350,
		height: 200,
		plugins: [
			'advlist autolink lists link image charmap print preview anchor textcolor',
			'searchreplace visualblocks code fullscreen',
			'insertdatetime media table paste code help wordcount'
		],
		menubar: false,
		toolbar_items_size : 'small',
		toolbar: 'undo redo | blockquote | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
		content_css: '../css/main_page.css'
	});
</script>		
 ";

