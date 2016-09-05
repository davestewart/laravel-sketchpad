<?php return
[
	// sketchpad route
	'route'         => 'sketchpad/',

	// processed folders
	'paths'         =>
	[
		'project'   => 'sketchpad/controllers/',
		'demo'      => 'vendor/davestewart/sketchpad/src/demo/'
	],

	// public assets folder for sketchpad resources
	'assets'        => 'vendor/sketchpad/',

	// optional path to a sketchpad views folder, accessible via 'sketch::view.path'
	'views'         => 'sketchpad/views/',

	// any middleware you want to run on sketchpad routes
	'middleware'    => null,
];