<?php

/**
 *	Get the current name
 */
const CACHE_ENTRY_NAME = 'domino-nickname';
const CACHE_NAME_LIFETIME = 3600;
const DOMINO_MP_LOGIN = 'domino54';
$ManiaPlanet = new \Maniaplanet\WebServices\Players(MP_WS_USERNAME, MP_WS_PASSWORD);
$dominoNickName = 'Dommy';

/**
 *	Start new requests if cached data is too old or if there is no cache file
 */
if ($dommyCache->hasExpired(CACHE_ENTRY_NAME, CACHE_NAME_LIFETIME)) {
	/**
	 *	Get the nickname
	 */
	try {
		$player = $ManiaPlanet->get(DOMINO_MP_LOGIN);
		if ($player) $dominoNickName = $player->nickname;
	}
	catch (Exception $e) { }
	
	// Save nickname in cache
	$dommyCache->setEntry(CACHE_ENTRY_NAME, $dominoNickName);
}
/**
 *	Load previous data from cache
 */
else $dominoNickName = $dommyCache->getContent(CACHE_ENTRY_NAME);

/**
 *	Get the data of the social networks
 */
const NETWORKS_DATA = './assets/social-networks.json';
$networksData = array();
if (file_exists(NETWORKS_DATA))	$networksData = json_decode(file_get_contents(NETWORKS_DATA), true);

/**
 *	Print the projects cards
 */
$networksDocument = '';

if (is_array($networksData) && count($networksData) > 0) {
	foreach ($networksData as $network) {
		if (!is_array($network)) continue;

		$networksDocument .= '
			<div class="media-card">
				<a class="media-card-avatar" href="'.$network['url'].'" style="background-image: url(\'./assets/social-media-icons/'.$network['image'].'\');"></a>
				<a class="media-card-name" href="'.$network['url'].'" style="background-color: '.$network['color'].';">'.$network['name'].'</a>
			</div>
		';
	}
}

/**
*	Generate content of the current page
*/
$pageConstructor->showNavigation();
$pageConstructor->showFooter();

$pageConstructor->setPageTitle('Dommy');
$pageConstructor->setHeaderText('<span class="maniaplanet-format" style="display: none">'.htmlspecialchars($dominoNickName).'</span>');

$pageConstructor->setPageContents('
<div id="home-container">
	<div id="home-content-main">
		<div class="project-image" style="background-image: url(\'./assets/about-screen.jpg\'); height: 360px;"></div>
		<div class="project-title">Something about me</div>
		<div class="project-description">
			So, basically you might know me as Dommy, the scripting dude from Mania games by Nadeo. I\'m Dominik from Poland (which cannot into space yet), a guy that likes playing games, but also playing <i>with</i> games (whatever does that mean). As a creative content creator, I like making useful (useless) and well-designed tools/mods for both the racing game TrackMania and the ShootMania shooter (you wouldn\'t guess it, would you?). Paying attention to final look of my creations is important for me - a well-looking product instantly represents its high quality (in most cases). Apart from making useful and entertaining things, I like to go crazy with the stuff I\'m working on (better don\'t ask me what exactly). If you are new or need help with programming, I\'ll maybe help you. Maybe. Or well, no. I\'m too lazy for this. Anyway, you can also find me on Facebook, Steam, YouTube, Discord or some other shit, which I\'ll probably link under social media page on this site.
		</div>
	</div>
	<div id="sidebar-container">
		<div class="home-sidebar-item">
			<div class="project-title">Contact</div>
			<div class="project-description">
				In case there is something important you want to tell me, you can easily contact me by sending an e-mail under this fancy address:<br>
				<a class="home-generic-button" id="button-show-adres" style="margin-top: 8px; margin-bottom: 8px;">Enable JavaScript!</a>
				<script type="text/javascript">
					function secAddress(elemId, dom2, dom1, name) {
						element = document.getElementById(elemId);
						element.innerHTML = name + "&#64;" + dom1 + "&#46;" + dom2;
						element.href = "mailto:" + element.innerHTML;
					}
					secAddress("button-show-adres", "com", "gmail", "racingpark54");
				</script>
				If you think e-mails are most archaic communication method available out there, you can send me a direct message through my Facebook page, by scanning this weird Messenger code or tapping a button below:
				<div id="messenger-code"></div>
				<a class="home-generic-button" id="home-messenger-button" href="http://m.me/dominotitles/">Open in Messenger</a>
			</div>
		</div>
		<div class="home-sidebar-item" style="margin-top: 16px; border-radius: 0px;">
			<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fdominotitles%2F&width=304&small_header=true" style="width: 304px" frameborder="0" scrolling="0"></iframe>
		</div>
	</div>
</div>
<div id="social-media-container">
	'.$networksDocument.'
</div>
');

echo $pageConstructor->composePage(PAGE_BASE_FILE);

?>