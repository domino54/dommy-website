elements = document.getElementsByClassName("maniaplanet-format");
for (i = 0; i < elements.length; i++) {
	curElement = elements[i];
	curElement.innerHTML = MPStyle.Parser.toHTML(curElement.innerHTML);
	curElement.style.display = "inline";
	curElement.style.textShadow = '1px 1px 1px rgba(0, 0, 0, 0.5)';
}