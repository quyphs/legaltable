<?php
	$thisPageUrl = basename($_SERVER['PHP_SELF']);
	$mainName = explode('.', $thisPageUrl)[0];
	$cssPage = "../css/".$mainName.".css";
	$jsPage = "../js/".$mainName.".js";
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset = "utf-8"/>
    <title>
		<?php
			if (isset($pageTitle)) { 
				echo $pageTitle; 
			} else { 
				echo MOTTO; 
			} 
		?>
	</title>
	<link rel="stylesheet" href="../css/jquery-ui.min.css"/>
	<link rel="stylesheet" href="<?php echo $cssPage ?>"/>
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo $jsPage ?>"></script>
  </head>
  <body>
	  <?php
	  echo publishingNavigationBar();
	  ?>