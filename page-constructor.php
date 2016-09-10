<?php
/**
 *	PageConstructor - used to easily generate pages on the website.
 */

namespace PageConstructor;

/**
 *	Page construction manager.
 */
class PageConstructor {
	const AVAILABLE_MODULES = ['navigation', 'header', 'description', 'content', 'footer'];
	private $selectedPageModules = array();
	private $metaPageTitle = '';
	private $customStyleSheet = '';

	/**
	 *	Add a module to the website.
	 *
	 *	@param string $name The name of the module.
	 *	@param string $content The contents of the module.
	 */
	private function addModule($name, $content) {
		if (!is_string($name) || !is_string($content)) return;
		global $selectedPageModules;
		$selectedPageModules[$name] = $content;
	}

	/**
	 *	Compose the whole page.
	 *
	 *	@param string $baseFilePath The path to the page base file.
	 *	@return string The complete page.
	 */
	function composePage($baseFilePath) {
		if (!is_string($baseFilePath) || !file_exists($baseFilePath)) return '';
		$baseFileContents = file_get_contents($baseFilePath);

		/**
		 *	Compose contents arranged within the supported modules.
		 */
		global $selectedPageModules;
		global $metaPageTitle;
		global $customStyleSheet;
		$pageGenratedContent = '';

		foreach (self::AVAILABLE_MODULES as $moduleName) {
			if (!array_key_exists($moduleName, $selectedPageModules)) continue;
			$pageGenratedContent .= $selectedPageModules[$moduleName];
		}

		// Create the page
		$baseFileContents = str_replace('{{title}}', $metaPageTitle, $baseFileContents);
		$baseFileContents = str_replace('{{styles}}', $customStyleSheet, $baseFileContents);
		$baseFileContents = str_replace('{{content}}', $pageGenratedContent, $baseFileContents);

		return $baseFileContents;
	}

	/**
	 *	Show the navigation bar over everything on the page.
	 */
	function showNavigation() {
		$this->addModule('navigation', '
<div id="navigation-bar">
	<div style="margin: auto; max-width: 960px;">
		<a id="nav-logo-domino" href="?"></a>
		<div id="nav-buttons-container"></div>
		<div id="nav-drop-container"></div>
	</div>
	<script src="/assets/navigation.js"></script>
</div>
<div id="navigation-filler"></div>
		');
	}

	/**
	 *	Show the footer text under page contents.
	 */
	function showFooter() {
		$this->addModule('footer', '
<div id="footer-text">
	This site won\'t work correctly, if you\'ve disabled JavaScript in your web browser settings.<br>
	This website has been made by domino54. All graphics and quotes belong to their respective owners.<br>
	Names and logos of Nadeo, ManiaPlanet, TrackMania and ShootMania are trademarks of Ubisoft Enertainment.<br>
	© domino54 2016
</div>
		');
	}

	/**
	 *	Set the page title text.
	 *
	 *	@param string $pageTitleText Title of the page.
	 */
	function setPageTitle($pageTitleText) {
		if (!is_string($pageTitleText) || $pageTitleText == '') return;
		global $metaPageTitle;
		$metaPageTitle = '<title>'.htmlspecialchars($pageTitleText).'</title>';
	}

	/**
	 *	Set the page description text.
	 *
	 *	@param string $descriptionText The text displayed in the description field.
	 */
	function setDescription($descriptionText) {
		if (!is_string($descriptionText) || $descriptionText == '') return;
		$this->addModule('description', '<div id="subpage-description">'.$descriptionText.'</div>');
	}

	/**
	 *	Set custom stylesheet.
	 *
	 *	@param string $stylesUrl Stylesheet address.
	 */
	function loadCustomStyleSheet($stylesUrl) {
		if (!is_string($stylesUrl) || $stylesUrl == '') return;
		global $customStyleSheet;
		$customStyleSheet = '<link rel="stylesheet" href="'.htmlspecialchars($stylesUrl).'" type="text/css"/>';
	}

	/**
	 *	Set the page header text.
	 *
	 *	@param string $headerText The text displayed in the header.
	 */
	function setHeaderText($headerText) {
		if (!is_string($headerText) || $headerText == '') return;
		$this->addModule('header', '<div id="header-image"><div style="margin: auto;">'.$headerText.'</div></div>');
	}

	/**
	 *	Set the page header text.
	 *
	 *	@param string $pageContents The main content of the page.
	 */
	function setPageContents($pageContents) {
		if (!is_string($pageContents) || $pageContents == '') return;
		$this->addModule('content', $pageContents);
	}
}

?>