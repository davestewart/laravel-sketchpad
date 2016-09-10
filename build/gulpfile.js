// ---------------------------------------------------------------------------------
// libs

	var gulp			= require('gulp'),
		gutil			= require('gulp-util'),
		log 			= require('gulp-util').log,
		path			= require('path'),
		elixir			= require('laravel-elixir'),
		es2015			= require('babel-preset-es2015'),
		txRuntime		= require('babel-plugin-transform-runtime');

		// elixir extensions
		require('laravel-elixir-vueify');


// ---------------------------------------------------------------------------------
// config

	// a portion of vanilla elixir config; not used, just here for reference
	var elixirConfig =
	{
		tasks			: [],
		production		: false,
		sourcemaps		: true,
		appPath			: 'app',
		assetsPath		: 'resources/assets',
		viewPath		: 'resources/views',
		publicPath		: 'public',
		css:
		{
			folder			: 'css',
			outputFolder	: 'css',
			autoprefix		: { enabled: true, options: '[Object]' },
			cssnano			: { pluginOptions: '[Object]' },
			sass			: { folder: 'sass', pluginOptions: '[Object]' },
			less			: { folder: 'less', pluginOptions: {} }
		},
		js:
		{
			folder			: 'js',
			outputFolder	: 'js',
			babel			: { options: '[Object]' },
			browserify:
			{
				options		: {},
				plugins		: [],
				externals	: [],
				transformers: '[Object]',
				watchify	: '[Object]'
			}
		},
		browserSync:
		{
			proxy: 'homestead.app',
			reloadOnRestart: true,
			notify: true
		}
	};

	// paths
	var rootPath					= path.normalize(__dirname + '/../');
	var assetsPath					= rootPath + 'publish/assets/';

	// elixir input
	elixir.config.assetsPath		= '../' + elixir.config.assetsPath;
	elixir.config.sourcemaps		= false;

	// elixir output
	elixir.config.publicPath		= '../publish/assets';
	elixir.config.css.outputFolder	= '';
	elixir.config.js.outputFolder	= '';


// ---------------------------------------------------------------------------------
// vueify hot reloading

	var bConfig = elixir.config.js.browserify;

	bConfig.plugins.push({
		name: 'vueify-extract-css',
		options: {
			out: assetsPath + 'components.css'
		}
	});

	if(gutil.env._.indexOf('watch') > -1)
	{
		bConfig.plugins.push({
			name: "browserify-hmr",
			options : {}
		});
	}


// ---------------------------------------------------------------------------------
// browserify

	// https://github.com/babel/babelify
	elixir.config.js.browserify.transformers =
	[
		{ name: 'babelify', options: { presets: [es2015] }, plugins:[ txRuntime ] },
		{ name: 'partialify' },
		{ name: 'vueify' }
	];

	//bConfig.watchify.enabled = true;
	bConfig.options.debug = true;


// ---------------------------------------------------------------------------------
// build

	elixir(function(mix){

		mix

			// lib scripts
			.combine(
			[
				rootPath + 'resources/lib/jquery-1.12.3.min.js',
				rootPath + 'resources/lib/**/*.js'
			], '../publish/assets/lib.js')

			// lib styles
			.combine(rootPath + 'resources/lib/**/*.css', '../publish/assets/lib.css')

			// app styles
			.sass('app.scss')

			// app scripts
			.browserify(
				'resources/assets/js/main.js',
				'../publish/assets/app.js',
				'../',
				{
					debug:true,
					paths:[
						'../resources/assets/js',
						'./node_modules',
						'./build'
					]
				});
	});

