<?php
	require_once '/libraries/autoload.php';
	
	const SUBPAGES_DIRECTORY = 'subpages/';
	const CACHE_DIRECTORY = 'mp-ws-cache/';
	const CACHE_INTERVAL = 60;
	const MP_WS_EXCEPTION_PREFIX = 'ManiaPlanet WS error: ';
	const DOMINO_MP_LOGIN = 'domino54';
	
	$currentPageId = explode("&", $_SERVER["QUERY_STRING"])[0];
	include 'mp-ws-auth.php';
	
	/**
	 *	Initialize page display settings
	 */
	$useNavigation = false;
	$useFooterInfo = false;
	
	/**
	 *	Initialize page default content
	 */
	$pageTitleMeta = '';
	$pageDescriptionMeta = '';
	$pageStylesheetMeta = '';
	$headerTitleText = '';
	$textPageContent = '';

	/**
	 *	Website content depending on query string
	 */
	switch ($currentPageId) {
		/**
		 *	Display home page (About) when query is empty
		 */
		case '' : {
			include SUBPAGES_DIRECTORY.'home.php';
			break;
		}
		/**
		 *	Projects list page
		 */
		case 'projects' : {
			include SUBPAGES_DIRECTORY.'projects.php';
			break;
		}
		/**
		 *	Servers list page
		 */
		case 'servers' : {
			include SUBPAGES_DIRECTORY.'servers.php';
			break;
		}
		/**
		 *	Social media page
		 */
		case 'socialmedia' : {
			include SUBPAGES_DIRECTORY.'socialmedia.php';
			break;
		}
		/**
		 *	Display fake 404 message when there is no matching subpage
		 */
		default : {
			include SUBPAGES_DIRECTORY.'404.php';
			break;
		}
	}

	/**
	 *	Display navigation bar
	 */
	$textNavigation = '';
	if ($useNavigation) $textNavigation = '
<div id="navigation-bar">
	<div style="margin: auto; max-width: 960px;">
		<a id="nav-logo-domino" href="?"></a>
		<div id="nav-buttons-container"></div>
		<div id="nav-drop-container"></div>
	</div>
	<script src="./assets/navigation.js"></script>
</div>
<div id="navigation-filler"></div>
	';

	/**
	 *	Display page header when its text is specified
	 */
	$textHeaderImage = '';
	if ($headerTitleText != '') $textHeaderImage = '<div id="header-image"><div style="margin: auto;">'.$headerTitleText.'</div></div>';

	/**
	 *	Display footer information text
	 */
	$textFooterInfo = '';
	if ($useFooterInfo) $textFooterInfo = '
<div id="footer-text">
	This site won\'t work correctly, if you\'ve disabled JavaScript in your web browser settings.<br>
	This website has been made by domino54. All graphics and quotes belong to their respective owners.<br>
	Names and logos of Nadeo, ManiaPlanet, TrackMania and ShootMania are trademarks of Ubisoft Enertainment.<br>
	© domino54 2016
</div>
	';
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<title><?=$pageTitleMeta?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="description" content="<?=$pageDescriptionMeta?>">
	<meta name="keywords" content="domino'54, Dommy'54, ManiaPlanet, TrackMania, ShootMania, Invasion">
	<meta name="theme-color" content="#222">
	<link rel="stylesheet" href="./assets/styles.css" type="text/css"/>
	<link rel="icon" href="./assets/icon.ico">
	<script src="./assets/mp-style-parser.js"></script>
	<style type="text/css"><?=$pageStylesheetMeta?></style>
</head>
<body onresize="adjustNavigation()">
<?=$textNavigation?>
<?=$textHeaderImage?>
<?=$textPageContent?>
<?=$textFooterInfo?>
<script type="text/javascript">
elements = document.getElementsByClassName('maniaplanet-format');
for (i = 0; i < elements.length; i++) {
	curElement = elements[i];
	curElement.innerHTML = MPStyle.Parser.toHTML(curElement.innerHTML);
	curElement.style.display = 'inline';
}
</script>
</body>
</html>