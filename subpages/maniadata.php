<?php

const AVAILABLE_SEARCH_TYPES = ['login', 'title', 'manialink', 'team'];
$search = queryGet('search');
$type = strtolower(queryGet('type'));
$generatedPageContent = '';

// Type should always be 'title' if search contains '@'
if (strpos($search, '@') > 0) $type = 'title';

if (!in_array($type, AVAILABLE_SEARCH_TYPES)) $type = 'login';

/**
 *	Start new search
 */
if (is_string($search) && $search != '') {
	switch ($type) {
		/**
		 *	Title pack
		 */
		case 'title' : {
			$ManiaPlanet = new \ManiaPlanet\WebServices\Titles(MP_WS_USERNAME, MP_WS_PASSWORD);
			break;
		}
		/**
		 *	Title pack
		 */
		case 'manialink' : {
			$ManiaPlanet = new \ManiaPlanet\WebServices\Manialinks(MP_WS_USERNAME, MP_WS_PASSWORD);
			break;
		}
		/**
		 *	Teams
		 */
		case 'team' : {
			$ManiaPlanet = new \ManiaPlanet\WebServices\Teams(MP_WS_USERNAME, MP_WS_PASSWORD);
			break;
		}
		/**
		 *	Player or server
		 */
		default : {
			$ManiaPlanet = new \ManiaPlanet\WebServices\Servers(MP_WS_USERNAME, MP_WS_PASSWORD);
		}
	}

	/**
	 *	Download and display the data of the target object
	 */
	try {
		$returnedObject = $ManiaPlanet->get($search);
		if (!$returnedObject) continue;
		$objectInformation = array();

		switch ($type) {
			/**
			 *	Title pack
			 */
			case 'title' : {
				$objectInformation = [
					'Name' => '<span class="maniaplanet-format">'.htmlspecialchars($returnedObject->name).'</span>',
					'Title UID' => htmlspecialchars($returnedObject->uid),
					'Title ID number' => '#'.$returnedObject->id
				];

				// Show base environments
				$environments = $returnedObject->dependencies;
				if (count($environments) > 0) {
					$formattedEnvironments = array();
					foreach ($environments as $environment) $formattedEnvironments[] = '<a href="?maniadata&search='.urlencode($environment).'&type=title">'.htmlspecialchars($environment).'</a>';
					$objectInformation['Base environments'] = join(", ", $formattedEnvironments);
				}
				
				// Show author login
				$authorLogin = $returnedObject->author;
				if ($authorLogin != '') $objectInformation['Author login'] = '<a href="?maniadata&search='.urlencode($authorLogin).'">'.htmlspecialchars($authorLogin).'</a>';

				// Show price in Planets
				$activationPrice = $returnedObject->cost;
				if ($activationPrice < 1000000000) $objectInformation['Activation price'] = $activationPrice.' Planets';

				// Show the web link
				$webLink = $returnedObject->web;
				if ($webLink != '') $objectInformation['Website link'] = '<a href="http://'.$webLink.'">'.$webLink.'</a>';

				break;
			}
			/**
			 *	Manialink
			 */
			case 'manialink' : {
				$objectInformation = [
					'Manialink code' => '<a href="maniaplanet:///:'.htmlspecialchars($returnedObject->code).'">'.htmlspecialchars($returnedObject->code).'</a>',
					'Source file' => '<a href="'.htmlspecialchars($returnedObject->url).'">'.htmlspecialchars($returnedObject->url).'</a>',
					'Author login' => '<a href="?maniadata&search='.urlencode($returnedObject->login).'">'.htmlspecialchars($returnedObject->login).'</a>',
					'Access cost' => $returnedObject->planetCost.' Planets'
				];
				break;
			}
			/**
			 *	Team
			 */
			case 'team' : {
				$titleUid = $returnedObject->title->uid;

				$objectInformation = [
					'Name (with format)' => '<span class="maniaplanet-format">'.htmlspecialchars($returnedObject->name).'</span>',
					'Name (raw)' => htmlspecialchars($returnedObject->name),
					'Short tag' => htmlspecialchars($returnedObject->tag),
					'Primary color' => '<b><span style="text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5); color: #'.$returnedObject->primaryColor.';">#'.$returnedObject->primaryColor.'</span></b>',
				];

				// Team emblem
				$teamEmblem = $returnedObject->emblemWeb;
				if ($teamEmblem != '') $objectInformation['Emblem image'] = '<div class="mania-data-emblem" style="background-image: url(\''.htmlspecialchars($teamEmblem).'\');"></div>';

				$objectInformation['Creator login'] = '<a href="?maniadata&search='.urlencode($returnedObject->creatorLogin).'">'.htmlspecialchars($returnedObject->creatorLogin).'</a>';
				$objectInformation['Creation time'] = htmlspecialchars($returnedObject->creationDate->date);
				$objectInformation['Total members'] = '<b>'.$returnedObject->teamSize.' / '.$returnedObject->maxTeamSize.'</b>';
				$objectInformation['Title UID'] = '<a href="?maniadata&search='.urlencode($titleUid).'&type=title">'.htmlspecialchars($titleUid).'</a>';
				
				// Get the team location
				$objectInformation['Location'] = '
					<span class="mania-data-zone" style="background-image: url(\''.htmlspecialchars($returnedObject->zone->iconJPGURL).'\');">
						'.htmlspecialchars(str_replace('|', " / ", $returnedObject->zone->path)).'
					</span>
				';

				// Team description
				$description = $returnedObject->description;
				if ($description != '') $objectInformation['Description'] = '<span class="maniaplanet-format">'.htmlspecialchars($description).'</span>';

				// Average members ladder points
				$averageLadderPoints = $returnedObject->ladderPoints;
				if ($averageLadderPoints > 0) $objectInformation['Average ladder points'] = '<span class="mania-data-lp">'.floor($averageLadderPoints).'</span>';

				break;
			}
			/**
			 *	Player or server
			 */
			default : {
				$login = $returnedObject->login;

				$objectInformation = [
					'Name (with format)' => '<span class="maniaplanet-format">'.htmlspecialchars($returnedObject->serverName).'</span>',
					'Name (raw)' => htmlspecialchars($returnedObject->serverName),
					'Login' => htmlspecialchars($login)
				];

				// Show the server owner
				$serverOwner = $returnedObject->owner;
				if ($serverOwner != $login) $objectInformation['Server creator'] = '<a href="?maniadata&search='.urlencode($serverOwner).'">'.htmlspecialchars($serverOwner).'</a>';

				// ID of the account
				$objectInformation['Account ID'] = '#'.$returnedObject->id;

				// Get the player location
				$objectInformation['Location'] = '
					<span class="mania-data-zone" style="background-image: url(\''.htmlspecialchars($returnedObject->zone->iconJPGURL).'\');">
						'.htmlspecialchars(str_replace('|', " / ", $returnedObject->zone->path)).'
					</span>
				';
				$objectInformation['Zone ID'] = '#'.$returnedObject->zone->id;

				// Display following information only if the server is online
				if ($returnedObject->isOnline) {
					$titleUid = $returnedObject->title->uid;

					$objectInformation['Title UID'] = '<a href="?maniadata&search='.urlencode($titleUid).'&type=title">'.htmlspecialchars($titleUid).'</a>';

					// Server description
					$description = $returnedObject->description;
					if ($description != '') $objectInformation['Description'] = '<span class="maniaplanet-format">'.htmlspecialchars($description).'</span>';

					$objectInformation['Players count'] = '<b>'.$returnedObject->playerCount.' / '.$returnedObject->maxPlayerCount.'</b>';
					$objectInformation['Game mode name'] = htmlspecialchars($returnedObject->scriptName);

					// Show the game mode version
					$scriptVersion = $returnedObject->scriptVersion;
					if ($scriptVersion != '') $objectInformation['Game mode version'] = htmlspecialchars($scriptVersion);

					// Ladder poitns interval (K servers)
					$objectInformation['Ladder points interval'] = '<span class="mania-data-lp">'.floor($returnedObject->ladderLimitMin).' - '.floor($returnedObject->ladderLimitMax).'</span>';

					// Players average ladder points
					$averageLadderPoints = $returnedObject->ladderPointsAvg;
					if ($averageLadderPoints > 0) $objectInformation['Average ladder points'] = '<span class="mania-data-lp">'.floor($averageLadderPoints).'</span>';

					$objectInformation['Server exe version'] = htmlspecialchars($returnedObject->buildVersion);
					$objectInformation['Upcoming maps'] = join("<br>", $returnedObject->mapsList);
				}

				// Get ManiaStars amount
				try {
					$players = new \ManiaPlanet\WebServices\Players(MP_WS_USERNAME, MP_WS_PASSWORD);
					$maniaStars = $players->getManiaStars($login);
					if ($maniaStars > 0) $objectInformation['ManiaStars'] = '<div class="mania-data-stars" style="width: '.($maniaStars * 18).'px;"></div>';
				}
				catch (Exception $e) { }

				break;
			}
		}

		if (count($objectInformation) > 0) {
			$objectInfoTable = '';

			foreach ($objectInformation as $attribute => $value) {
				$objectInfoTable .= '
					<div class="mania-data-card">
						<div class="mania-data-property">'.$attribute.'</div>
						<div class="mania-data-value">'.$value.'</div>
					</div>
				';
			}

			$generatedPageContent = '<div style="margin: 8px;"><div id="mania-data-container">'.$objectInfoTable.'</div></div>';
		}
	}
	catch (Exception $e) {
		$generatedPageContent = '<div id="subpage-description">'.htmlspecialchars($e->getMessage()).'</div>';
	}
}

/**
 *	Automatically focus input when search is null
 */
$inputAutofocus = '';
if ($search == '') $inputAutofocus = 'autofocus';

/**
 *	Generate content of the current page
 */
$pageConstructor->showNavigation();
$pageConstructor->showFooter();

$titleText = 'ManiaPlanet data browser';
if ($search != '') $titleText = $search;

$pageConstructor->setPageTitle('Dommy - '.$titleText);
$pageConstructor->setDescription('Search for detailed information about various things in ManiaPlanet');
$pageConstructor->setHeaderText('ManiaPlanet data browser');

$pageConstructor->setPageContents('
<div id="mania-data-input">
	<div style="margin: 8px;">
		<input id="search" type="text" name="search" value="'.htmlspecialchars($search).'" '.$inputAutofocus.' onkeypress="inputSubmit(event)"/>
		<span id="mania-search-desc">Enter login, title UID, manialink code or team ID and click one of the buttons below</span>
	</div>
</div>
<script type="text/javascript">
searchInput = document.getElementById("search");

function maniaSearch(searchType) {
	if (searchInput.value == "") return;
	URL = "?maniadata&search="+searchInput.value;
	if (searchType != "") URL += "&type="+searchType;
	window.open(encodeURI(URL), "_self");
}

function inputSubmit(event) {
	if (event.keyCode != 13 || searchInput.value == "") return;
	searchType = "'.htmlspecialchars($type).'";
	if (searchType != "manialink" && searchType != "team") searchType = "";
	maniaSearch(searchType);
}
</script>
<div id="mania-type-selector">
	<a onclick="maniaSearch(\'\')">Login</a>
	<a onclick="maniaSearch(\'title\')">Title</a>
	<a onclick="maniaSearch(\'manialink\')">Manialink</a>
	<a onclick="maniaSearch(\'team\')">Team</a>
</div>
'.$generatedPageContent.'
');

echo $pageConstructor->composePage(PAGE_BASE_FILE);

?>