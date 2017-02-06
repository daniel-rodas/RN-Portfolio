<?php

Autoloader::add_core_namespace('Portfolio', true);

Autoloader::add_classes([
	'Portfolio\\Portfolio' => __DIR__ . '/classes/portfolio.php',
	'Portfolio\\Validation'                => __DIR__.'/classes/validation.php',
	'Portfolio\\PortfolioException' => __DIR__ . '/classes/portfolio.php',
	'Portfolio\\Model\Portfolio' => __DIR__ . '/classes/model/portfolio.php',
	'Portfolio\\Model\Access' => __DIR__ . '/classes/model/access.php',
	'Portfolio\\Model\Exhibit' => __DIR__ . '/classes/model/exhibit.php',
	'Portfolio\\Model\Asset' => __DIR__ . '/classes/model/asset.php',
	'Portfolio\\Access' => __DIR__ . '/classes/access.php',

]);
