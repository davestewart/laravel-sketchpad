<?php return
[
	// sketchpad route
	'route'         => 'sketchpad/',

	// processed folders
	'paths'         =>
	[
		'project'   => 'app/Http/Sketchpad/',
		'examples'  => 'vendor/davestewart/sketchpad/src/examples/'
	],

	// public assets folder for sketchpad resources
	'assets'        => 'vendor/sketchpad/',

	// any middleware you want to run on sketchpad routes
	'middleware'    => null,
];