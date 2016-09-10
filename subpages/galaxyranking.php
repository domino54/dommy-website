<?php

/**
 *	Initialize rankings data
 */
const CACHE_ENTRY_NAME = 'galaxy-ranking-data';
const CACHE_RANKING_LIFETIME = 3600;
$ManiaPlanet = new \Maniaplanet\WebServices\Rankings(MP_WS_USERNAME, MP_WS_PASSWORD);
$savedPlayersData = array();
$exceptionMessage = '';

/**
 *	Add a player to the players array
 *
 *	@param	object	$object		The player to add
 */
function loadPlayer($object) {
	if (!is_object($object)) return;
	global $savedPlayersData;
	
	$savedPlayersData[] = array(
		'rank' => $object->rank,
		'points' => $object->points,
		'id' => $object->player->id,
		'login' => $object->player->login,
		'name' => $object->player->nickname,
		'path' => $object->player->path
	);
}

/**
 *	Start new requests if cached data is too old or if there is no cache file
 */
if ($dommyCache->hasExpired(CACHE_ENTRY_NAME, CACHE_RANKING_LIFETIME)) {
	/**
	 *	Get all players ranking in GalaxyTitles@domino54
	 */
	try {
		$zone = $ManiaPlanet->getMultiplayerWorld('GalaxyTitles@domino54');
		foreach ($zone->players as $player) loadPlayer($player);
	}
	catch (Exception $e) {
		$exceptionMessage .= MP_WS_EXCEPTION_PREFIX.$e->getMessage();
	}

	// Save ranking data in cache
	$dommyCache->setEntry(CACHE_ENTRY_NAME, $savedPlayersData);
}
/**
 *	Load previous data from cache
 */
else $savedPlayersData = $dommyCache->getContent(CACHE_ENTRY_NAME);

/**
 *	Create players cards
 */
$playersRankingText = '';
$displayedPlayersNb = 0;

foreach ($savedPlayersData as $player) {
	if (!$player) continue;
	
	$playersRankingText .= '
		<div class="ranking-card" style="min-height: 28px; line-height: 28px;">
			<div style="flex: 1;">
				<span class="ranking-card-position">'.$player["rank"].'.</span>
				<span class="ranking-card-name maniaplanet-format">'.htmlspecialchars($player["name"]).'</span>
			</div>
			<div>
				<span class="ranking-card-zone">'.htmlspecialchars(explode('|', $player["path"])[1]).'</span>
				<span class="ranking-card-points">'.htmlspecialchars($player["points"]).'</span>
			</div>
		</div>
	';
}

/**
 *	Display message when there are no servers online or when there is an error
 */
$errorMessage = '';
if ($exceptionMessage != '') $errorMessage = $exceptionMessage;
else if (sizeof($savedPlayersData) <= 0) $errorMessage = 'Looks like there are no players in the rankings (or something went wrong)!';
if ($errorMessage != '') $playersRankingText = '<div id="servers-empty-text">'.htmlspecialchars($errorMessage).'</div>';

/**
 *	Generate content of the current page
 */
$pageConstructor->showNavigation();
$pageConstructor->showFooter();

$pageConstructor->setPageTitle('Dommy - Galaxy ranking');
$pageConstructor->setDescription('Multiplayer ranking of the top 100 players in ShootMania Galaxy title pack');
$pageConstructor->setHeaderText('ShootMania Galaxy ranking');

$pageConstructor->setPageContents('
<div style="margin: 8px;">
	<div id="ranking-list-container">
		'.$playersRankingText.'
	</div>
</div>
<div id="servers-update-time">Latest update '.timeDifferenceToText($dommyCache->getLifeDuration(CACHE_ENTRY_NAME)).'.</div>
');

echo $pageConstructor->composePage(PAGE_BASE_FILE);

?>