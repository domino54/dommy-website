var buttonsData = [{
	text: "About",
	link: "?",
	width: 88.53
}, {
	text: "Galaxy",
	link: "?galaxy",
	width: 97.42
}, {
	text: "Projects",
	link: "?projects",
	width: 116.59
}, {
	text: "Servers",
	link: "?servers",
	width: 106.11
}, {
	text: "Data",
	link: "?maniadata",
	width: 70.88
}];

var navButtonsContainer = document.getElementById("nav-buttons-container");
var navDropContainer = document.getElementById("nav-drop-container");
var navDropMenuButton = document.getElementById("nav-drop-menu-button");
var prevWidth = 0.;

// Adjust navigation buttons to the current window size
function adjustNavigation() {
	// Ignore if the navigation width hasn't changed
	if (prevWidth == navButtonsContainer.clientWidth) return;
	prevWidth = navButtonsContainer.clientWidth;
	
	// Remove all buttons
	navButtonsContainer.innerHTML = "<div id=\"nav-drop-menu-button\" onclick=\"toggleMenuVisibility()\"></div>";
	navDropContainer.innerHTML = "";
	
	// Reset variables
	var totalWidth = 0;
	var menuButtonWidth = 50;
	var maxWidth = prevWidth - 110 - menuButtonWidth;
	var displayedButtons = 0;
	
	// Display all buttons that fit on the navigation bar
	for (i = 0; i < buttonsData.length; i++) {
		var curButtonData = buttonsData[i];
		
		// Expand the maximum width when displaying last button
		if (i == buttonsData.length - 1) maxWidth += menuButtonWidth;
		
		// Get the width of the button and skip if doesn't fit
		totalWidth += curButtonData.width;
		if (totalWidth > maxWidth) break;
		displayedButtons += 1;
		
		// Create the button
		var button = document.createElement("a");
		button.innerHTML = curButtonData.text;
		button.href = curButtonData.link;
		navButtonsContainer.appendChild(button);
	}
	
	// Check if all buttons are displayed
	var displayedAllButtons = displayedButtons >= buttonsData.length;
	
	// Set menu button visibility
	var menuButton = document.getElementById("nav-drop-menu-button");
	if (!displayedAllButtons) menuButton.style.display = "block";
	else {
		menuButton.style.display = "none";
		return;
	}
	
	// Display remaining buttons as drop menu elements
	for (i = displayedButtons; i < buttonsData.length; i++) {
		var curButtonData = buttonsData[i];
		
		var button = document.createElement("a");
		button.innerHTML = curButtonData.text;
		button.href = curButtonData.link;
		navDropContainer.appendChild(button);
	}
}

// Print buttons for the first time
adjustNavigation();

// Toggle menu list visibility
function toggleMenuVisibility() {
	navDropContainer = document.getElementById("nav-drop-container");
	if (navDropContainer.style.display != "block") navDropContainer.style.display = "block";
	else navDropContainer.style.display = "none";
}