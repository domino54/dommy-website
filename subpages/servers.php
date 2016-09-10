<?php

/**
 *	Initialize servers data
 */
const CACHE_ENTRY_NAME = 'servers-data';
const CACHE_SERVERS_LIFETIME = 60;
$ManiaPlanet = new \Maniaplanet\WebServices\Servers(MP_WS_USERNAME, MP_WS_PASSWORD);
$serversLogins = ['miss_pursuit', 'miss_pursuit2'];
$savedServersData = array();
$exceptionMessage = '';

/**
 *	Add a server to the servers array
 *
 *	@param	object	$server		The server to add
 */
function loadServer($server) {
	if (!is_object($server) || $server->isOnline != 1) return;
	global $savedServersData;
	
	$savedServersData[] = array(
		'login' => $server->login,
		'name' => $server->serverName,
		'titleUid' => $server->title->idString,
		'mode' => $server->scriptName,
		'playersCount' => $server->playerCount,
		'playersCountMax' => $server->maxPlayerCount,
	);
}

/**
 *	Compare two servers for sorting
 *
 *	@param	array	$a		First server
 *	@param	array	$b		Second server
 */
function compareServers($a, $b) {
	if ($a['playersCount'] < $b['playersCount']) return 1;
	if ($a['playersCount'] > $b['playersCount']) return -1;
	return 0;
}

/**
 *	Start new requests if cached data is too old or if there is no cache file
 */
if ($dommyCache->hasExpired(CACHE_ENTRY_NAME, CACHE_SERVERS_LIFETIME)) {
	/**
	 *	Get all servers with logins specified in the array
	 */
	foreach ($serversLogins as $login) {
		try {
			loadServer($ManiaPlanet->get($login));
		}
		catch (Exception $e) {
			$exceptionMessage .= MP_WS_EXCEPTION_PREFIX.$e->getMessage();
		}
	}
	
	/**
	 *	Get all servers in GalaxyTitles@domino54
	 */
	try {
		foreach ($ManiaPlanet->getFilteredList(array('titleUids' => 'GalaxyTitles@domino54', 'length' => 100)) as $server) loadServer($server);
	}
	catch (Exception $e) {
		$exceptionMessage .= MP_WS_EXCEPTION_PREFIX.$e->getMessage();
	}
	
	// Sort servers by amout of players on them
	usort($savedServersData, 'compareServers');
	
	// Save servers data in cache
	$dommyCache->setEntry(CACHE_ENTRY_NAME, $savedServersData);
}
/**
 *	Load previous data from cache
 */
else $savedServersData = $dommyCache->getContent(CACHE_ENTRY_NAME);

/**
 *	Create servers cards
 */
$serversListText = '';
$displayedServers = 0;

foreach ($savedServersData as $curServer) {
	if (!$curServer) continue;
	$displayedServers += 1;
	
	$serversListText .= '
		<div class="server-card">
			<span class="server-card-name maniaplanet-format">'.htmlspecialchars($curServer["name"]).'</span>
			<div id="server-card-details">
				<div class="server-card-gamemode">'.htmlspecialchars($curServer["mode"]).'</div>
				<div class="server-card-players">'.$curServer["playersCount"].' / '.$curServer["playersCountMax"].'</div>
				<a class="server-card-buttton" href="maniaplanet://#join='.htmlspecialchars($curServer["login"]).'@'.htmlspecialchars($curServer["titleUid"]).'" >Join</a>
			</div>
		</div>
	';
}

/**
 *	Display message when there are no servers online or when there is an error
 */
$errorMessage = '';
if ($exceptionMessage != '') $errorMessage = $exceptionMessage;
else if (sizeof($displayedServers) <= 0) $errorMessage = 'Looks like there are no servers online at the moment!';
if ($errorMessage != '') $serversListText = '<div id="servers-empty-text">'.htmlspecialchars($errorMessage).'</div>';

/**
 *	Generate content of the current page
 */
$pageConstructor->showNavigation();
$pageConstructor->showFooter();

$pageConstructor->setPageTitle('Dommy - Servers list');
$pageConstructor->setDescription('Here you can find all ManiaPlanet servers running game modes created by me');
$pageConstructor->setHeaderText('Servers list');

$pageConstructor->setPageContents('
<div style="margin: 8px;">
	<div id="servers-list-container">
		'.$serversListText.'
	</div>
</div>
<div id="servers-update-time">Latest update '.timeDifferenceToText($dommyCache->getLifeDuration(CACHE_ENTRY_NAME)).'.</div>
');

echo $pageConstructor->composePage(PAGE_BASE_FILE);

?>