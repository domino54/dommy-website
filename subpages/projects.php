<?php

/**
 *	Get the data of the projects
 */
const PROJECTS_DATA = './assets/projects.json';
$projectsData = array();
if (file_exists(PROJECTS_DATA))	$projectsData = json_decode(file_get_contents(PROJECTS_DATA), true);

/**
 *	Print the projects cards
 */
$projectsDocument = '';

if (is_array($projectsData) && count($projectsData) > 0) {
	foreach ($projectsData as $project) {
		if (!is_array($project)) continue;

		$projectsDocument .= '
			<div class="project-card-frame">
				<div class="project-image" style="background-image: url(\'./assets/projects/'.$project["image"].'\');"></div>
				<div class="project-title">'.$project["name"].'</div>
				<div class="project-description">'.$project["description"].'</div>
				<a class="project-hyperlink" href="'.$project["link"].'"">'.$project["button"].'</a>
			</div>
		';
	}
}

/**
 *	Generate content of the current page
 */
$pageConstructor->showNavigation();
$pageConstructor->showFooter();

$pageConstructor->setPageTitle('Dommy - My projects');
$pageConstructor->setDescription('This page contains a list of all my ongoing and discontinued projects in ManiaPlanet');
$pageConstructor->setHeaderText('My projects');

$pageConstructor->setPageContents('
<div id="projects-cards-container">
	'.$projectsDocument.'
</div>
');

echo $pageConstructor->composePage(PAGE_BASE_FILE);

?>