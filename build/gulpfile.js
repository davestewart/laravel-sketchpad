// ---------------------------------------------------------------------------------
// libs

	var gulp			= require('gulp'),
		gutil			= require('gulp-util'),
		argv            = require('yargs').argv,
		path			= require('path'),
		elixir			= require('laravel-elixir'),
		es2015			= require('babel-preset-es2015'),
		txRuntime		= require('babel-plugin-transform-runtime');

		// elixir extensions
		require('laravel-elixir-vueify');


// ---------------------------------------------------------------------------------
// config

	// paths
	var rootPath					= path.normalize(__dirname + '/../');
	var assetsPath					= rootPath + 'package/assets/';

	// elixir input
	elixir.config.assetsPath		= '../' + elixir.config.assetsPath;
	//elixir.config.sourcemaps		= true;

	// elixir output
	elixir.config.publicPath		= '../package/assets';
	elixir.config.css.outputFolder	= '';
	elixir.config.js.outputFolder	= '';

	// production
	if (argv.production)
	{
		console.log('Setting NODE_ENV to "production"')
		process.env.NODE_ENV = 'production';
	}


// ---------------------------------------------------------------------------------
// vueify hot reloading

	var bConfig = elixir.config.js.browserify;

	bConfig.plugins.push({
		name: 'vueify-extract-css',
		options: {
			out: assetsPath + 'css/vue.css'
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

		var options = {
			debug:true,
			paths:[
				'../resources/assets/js',
				'./node_modules',
				'./build'
			]
		}

		// variables
		var resources   = 'resources/';
		var assets      = '../package/assets/';
		var file        = argv.setup ? 'setup' : 'app';

		// build
		// call --setup to run setup
		mix

			// lib scripts
			.combine(
			[
				rootPath + 'resources/lib/jquery-1.12.3.min.js',
				rootPath + 'resources/lib/**/*.js'
			], assets + 'js/lib.js')

			// lib styles
			.combine(rootPath + 'resources/lib/**/*.css', assets + 'css/lib.css')

			// app styles
			.sass(file + '.scss',   assets + 'css/' +file+ '.css')

			// app scripts
			.browserify(
				'resources/assets/js/' +file+ '.js',
				assets + 'js/' +file+ '.js',
				'../',
				options)
	});