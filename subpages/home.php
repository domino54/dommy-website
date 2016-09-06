<?php
	$useNavigation = true;
	$useFooterInfo = true;
	
	$pageTitleMeta = 'Dommy';
	$pageDescriptionMeta = 'Some lazy dude writing some scripts';
	$headerTitleText = 'Dommy';
	
	/**
	 *	Get the current name
	 */
	$ManiaPlanet = new \Maniaplanet\WebServices\Players(MP_WS_USERNAME, MP_WS_PASSWORD);
	$nameUpdateTimeCacheFile = CACHE_DIRECTORY.'nickname-update-time.txt';
	$nameLastKnownCacheFile = CACHE_DIRECTORY.'nickname-last-known.txt';
	$lastNameUpdateTime = -1;
	$lastKnownNickName = '';
	
	if (file_exists($nameUpdateTimeCacheFile))
		$lastNameUpdateTime = intval(file_get_contents($nameUpdateTimeCacheFile));
	
	/**
	 *	Start new requests if cached data is too old or if there is no cache file
	 */
	if (time() >= $lastNameUpdateTime + CACHE_INTERVAL || !file_exists($nameLastKnownCacheFile)) {
		$lastNameUpdateTime = time();
		file_put_contents($nameUpdateTimeCacheFile, $lastNameUpdateTime);
		
		/**
		 *	Get the nickname
		 */
		try {
			$player = $ManiaPlanet->get(DOMINO_MP_LOGIN);
			if ($player) $lastKnownNickName = $player->nickname;
		}
		catch (\Maniaplanet\WebServices\Exception $e) { }
		
		// Save nickname in cache
		file_put_contents($nameLastKnownCacheFile, $lastKnownNickName);
	}
	/**
	 *	Load previous data from cache
	 */
	else $lastKnownNickName = file_get_contents($nameLastKnownCacheFile);
	
	/**
	 *	Setup the obtained nickname
	 */
	if ($lastKnownNickName != '')
		$headerTitleText = '<span class="maniaplanet-format" style="display: none">'.$lastKnownNickName.'</span>';
	
	/**
	 *	Print page contents
	 */
	$textPageContent = '
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
<div id="social-media-container"></div>
<script src="./assets/socialmedia.js"></script>
	';
?>