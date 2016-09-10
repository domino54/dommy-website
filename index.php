<?php

include __DIR__.'/libraries/autoload.php';
include __DIR__.'/libraries/dommycache.php';
include __DIR__.'/page-constructor.php';
include __DIR__.'/mp-ws-auth.php';

const SUBPAGES_DIRECTORY = './subpages/';
const MP_WS_EXCEPTION_PREFIX = 'ManiaPlanet WS error: ';
const PAGE_BASE_FILE = './base.html';

$pageConstructor = new \PageConstructor\PageConstructor();
$dommyCache = new \DommyCache\Cache();
$currentPageId = explode("&", $_SERVER["QUERY_STRING"])[0];

/**
 *	Get an attribute value from the query.
 *
 *	@param string $name The name of the attribute to get value.
 *	@return mixed The value of an attribute.
 */
function queryGet($name) {
	if (!is_string($name) || !isset($_GET[$name])) return;
	return $_GET[$name];
}

/**
 *	Convert time difference number into understandable text.
 *
 *	@param int $timeDifference The time difference to convert.
 *	@return string Time difference text.
 */
function timeDifferenceToText($timeDifference) {
	if (!is_int($timeDifference) || $timeDifference <= 0) return 'just now';
	$hours = floor($timeDifference / 3600);
	$minutes = floor($timeDifference / 60);

	if ($hours > 1) return $hours.' hours ago';
	if ($hours == 1) return '1 hour ago';
	if ($minutes > 1) return $minutes.' minutes ago';
	if ($minutes == 1) return '1 minute ago';
	if ($timeDifference > 1) return $timeDifference.' seconds ago';
	return '1 second ago';
	
}

/**
 *	Check if the website specified in query exists on the server and display it
 */
if ($currentPageId == '') include(SUBPAGES_DIRECTORY.'home.php');
else {
	$subpagePath = SUBPAGES_DIRECTORY.$currentPageId.'.php';
	if (file_exists($subpagePath)) include($subpagePath);
	else include(SUBPAGES_DIRECTORY.'404.php');
}

?>
