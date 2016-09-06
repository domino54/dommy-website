loadSocialMedia([{
	name: "Facebook",
	color: "#369",
	image: "facebook.jpg",
	url: "https://www.facebook.com/dominotitles/"
}, {
	name: "YouTube",
	color: "#B55",
	image: "youtube.png",
	url: "https://www.youtube.com/user/dommy54x/"
}, {
	name: "Twitch",
	color: "#658",
	image: "twitch.png",
	url: "https://www.twitch.tv/domino54tmuf/"
}, {
	name: "GitHub",
	color: "#444",
	image: "github.png",
	url: "https://github.com/domino54/"
}, {
	name: "Steam",
	color: "#558",
	image: "steam.png",
	url: "https://steamcommunity.com/id/domino54/"
}])

function loadSocialMedia(websites) {
	socialMediaContainer = document.getElementById("social-media-container");
	for (i = 0; i < websites.length; i++) {
		curWebsite = websites[i];
		socialMediaContainer.innerHTML +=
		'<div class="media-card">' +
			'<a class="media-card-avatar" href="'+curWebsite.url+'" style="background-image: url(\'./assets/social-media-icons/'+curWebsite.image+'\');"></a>' +
			'<a class="media-card-name" href="'+curWebsite.url+'" style="background-color: '+curWebsite.color+';">'+curWebsite.name+'</a>' +
		'</div>';
	}
}