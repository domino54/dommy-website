myProjects([{
	name: "ShootMania Galaxy",
	image: "galaxy.jpg",
	description: "Galaxy is a pack that entirely changes the way you've played ShootMania. A three classic team-based game modes - Jailbreak, Golden Dunk and Confiirmed - have been completely redone, with new gameplay mechanics, live statistics and beautiful interface.",
	button: "Get on Store",
	link: "maniaplanet://#menustations=store?GalaxyTitles@domino54"
}, {
	name: "ShootMania Invasion",
	image: "invasion.jpg",
	description: "Invasion is about protecting your pole from infecting by Toads horde. These become stronger over time, and after some time there is just no hope. Your task is over when 20 Toads reach the pole. Available to enjoy in both solo campaign and on multiplayer servers.",
	button: "Get on Store",
	link: "maniaplanet://#menustations=store?Invasion@domino54"
}, {
	name: "ShootMania Hunger Games",
	image: "hunger-games.jpg",
	description: "Up to 24 players come into the games, but only one can get out alive. Test your skills in this novel-based last man standing game mode, where you have to use pick-up items to gather weapons and various abilities. One life, one chance, no place for mistakes.",
	button: "Get on Store",
	link: "maniaplanet://#menustations=store?HungerGames@domino54"
}, {
	name: "TrackMania² Dominis",
	image: "dominis.jpg",
	description: "My very first custom title pack for TrackMania, which contains 15, extremely easy to bet short, non-technical tracks I've made back in Nations Forever times. Maybe it's not a big challenge, but still something you could try out when you're completely bored.",
	button: "Get on Store",
	link: "maniaplanet://#menustations=store?Dominis@domino54"
}, {
	name: "TrackMania² Pursuit",
	image: "pursuit.jpg",
	description: "Long waited cops & robbers game mode is now available in TrackMania! Run and hide from the police, or catch all the thieves. Once you're caught, you join the police forces. You'll get as many points, as many thieves you have survived or caught.",
	button: "Play now!",
	link: "maniaplanet://#qjoin=miss_pursuit@TMStadium"
}, {
	name: "Mania Exchange manialink",
	image: "exchange.jpg",
	description: "Wouldn't you like to browse community-made maps and download them ingame, without wasting your time switching all the windows and copying files? Just type <b>exchange</b> in your address bar and download everything you need.",
	button: "Open manialink",
	link: "maniaplanet:///:exchange?ref=dominowebsite"
}, {
	name: "FindMe search",
	image: "findme.png",
	description: "FindMe is the universal search engine for ManiaPlanet. Discover multum of community-made manialinks, ManiaPlanet-related websites and also ShootMania game modes. It also lets you find Mania Exchange maps and servers for all environments!",
	button: "Open manialink",
	link: "maniaplanet:///:findme?ref=dominowebsite"
}, {
	name: "QuickJoin for ShootMania",
	image: "quickjoin.png",
	description: "With QuickJoin tool you can play your favourite game modes within one mouse click. Just choose the desired mode you want to play and it will automatically find a server for you. also, on this image you can see how dead this game really is!",
	button: "Open manialink",
	link: "maniaplanet:///:quickjoin"
}, {
	name: "Evidence chat",
	image: "evidence.jpg",
	description: "The Evidence is next generation of the ManiaPlanet chat you used to use. Among most important functions of the default chat, Evidence gives you many things you always wanted to have (or maybe not). New, clear, readable and very sexy chat GUI is finally coming to ManiaPlanet.",
	button: "Learn more",
	link: "https://forum.maniaplanet.com/viewtopic.php?t=31658"
}, {
	name: "TitlePack center",
	image: "titlecenter.png",
	description: "The TitlePack Center was my first big project in ManiaPlanet. On this site you could browse various title packs done by other community members. When needed, filtering by a specific environment was also possible. Now it's still available, but only as a read-only archive.",
	button: "Open manialink",
	link: "maniaplanet:///:titlepack-center"
}])

function myProjects(projects) {
	projectsCardsContainer = document.getElementById("projects-cards-container");
	for (i = 0; i < projects.length; i++) {
		curProject = projects[i];
		projectsCardsContainer.innerHTML +=
		'<div class="project-card-frame">' +
			'<div class="project-image" style="background-image: url(\'./assets/projects/'+curProject.image+'\');"></div>' +
			'<div class="project-title">'+curProject.name+'</div>' +
			'<div class="project-description">'+curProject.description+'</div>' +
			'<a class="project-hyperlink" href="'+curProject.link+'">'+curProject.button+'</a>' +
		'</div>';
	}
}