<!-- Stacker logo includes -->
<link rel="apple-touch-icon" sizes="180x180" href="favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16x16.png">
<link rel="manifest" href="favicons/manifest.json">
<link rel="mask-icon" href="favicons/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">


<!-- Bootcards CSS files for desktop -->
<?php   
	if(isset($_GET["colors"])){
			$colors=$_GET["colors"];
	}
	if(isset($colors) && $colors == "light"){
		echo '<link href="css/bootcards-desktop-light.min.css" rel="stylesheet">
			  <link href="css/bootstrap-light.min.css" rel="stylesheet">';
	} else {
		echo '<link href="css/bootcards-desktop.min.css" rel="stylesheet">
			  <link href="css/bootstrap.min.css" rel="stylesheet">';
	}
?>
<!-- Font Awesome, the iconic font and CSS framework. --> 
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

<!-- Bootstrap Toggle includes -->
<link href="css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="js/bootstrap-toggle.min.js"></script>

<!-- Google Font for Open Sans -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
