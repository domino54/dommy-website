<?php

/**
 *	Generate content of the current page
 */
$pageConstructor->showNavigation();
$pageConstructor->showFooter();

$pageConstructor->setPageTitle('Dommy - Page not found');
$pageConstructor->setDescription('There is no such page on this website');
$pageConstructor->setHeaderText('Page not found');

echo $pageConstructor->composePage(PAGE_BASE_FILE);

?>