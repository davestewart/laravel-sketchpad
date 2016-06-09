// ---------------------------------------------------------------------------------
// libs

	var gulp			= require('gulp'),
		sass			= require('gulp-sass'),
		cssmin 			= require('gulp-cssmin'),
		uglify			= require('gulp-uglify'),
		rename			= require('gulp-rename'),
		concat			= require('gulp-concat');
		concatCss		= require('gulp-concat-css'),
		sourcemaps		= require('gulp-sourcemaps')
		log 			= require('gulp-util').log;


// ---------------------------------------------------------------------------------
// config

	// paths
	var pubFolder      = '../publish/assets/';
	var resFolder      = '../resources/assets/';
	var libFolder      = '../resources/lib/';
	var jsFolder       = resFolder + 'js/';

	var app =
	{
		css:
		{
			input	: resFolder + 'sass/**/*.scss',
			output	: pubFolder,
		},
		js:
		{
			watch:jsFolder + '**/*.js',
			input:
			[
				jsFolder + 'classes/**/*.js',
				jsFolder + 'services/**/*.js',
				jsFolder + 'components/**/*.js',
				jsFolder + 'app.js',
			],
			output:
			{
				file	:'app.js',
				folder	:pubFolder,
			}
		}
	}

	var lib =
	{
		css:
		{
			input	:libFolder + '**/*.css',
			output	:
			{
				file	:'lib.min.css',
				folder	:pubFolder
			}
		},
		js:
		{
			watch:libFolder + '**/*.js',
			input:
			[
				libFolder + 'jquery-*.js',
				libFolder + 'vue.js',
				libFolder + '**/*.js',
			],
			output:
			{
				file	:'lib.min.js',
				folder	:pubFolder,
			}
		}
	}


// ---------------------------------------------------------------------------------
// functions

	function styles()
	{
		log('Rebuilding styles...');
		return gulp
			.src(app.css.input)
			.pipe(sass().on('error', sass.logError))
			.pipe(gulp.dest(app.css.output));
	}

	function scripts()
	{
		log('Rebuilding scripts...');
		return gulp
			.src(app.js.input)
			.pipe(concat(app.js.output.file))
			.pipe(gulp.dest(app.js.output.folder));
	}

	function stylesLib()
	{
		log('Rebuilding lib styles...');
		return gulp
			.src(lib.css.input)
			.pipe(concatCss(lib.css.output.file))
			.pipe(cssmin())
			.pipe(gulp.dest(lib.css.output.folder));
	}

	function scriptsLib()
	{
		log('Rebuilding lib scripts...');
		return gulp
			.src(lib.js.input)
			//.pipe(sourcemaps.init())
			.pipe(uglify({compress:true}))
			.pipe(concat(lib.js.output.file))
			//.pipe(sourcemaps.write('.'))
			.pipe(gulp.dest(lib.js.output.folder));
	}


// ---------------------------------------------------------------------------------
// tasks

	/**
	 * Build styles and scripts
	 *
	 * Call "gulp build --live" to mangle/compress and skip source maps
	 */
	function build()
	{
		styles();
		scripts();
		stylesLib();
		scriptsLib();
	}

	/**
	 * Monitor styles and scripts, and live-reload when saved
	 */
	function watch()
	{
		build();
		gulp.watch(app.css.input, styles);
		gulp.watch(app.js.watch, scripts);
		gulp.watch(lib.css.input, stylesLib);
		gulp.watch(lib.js.watch, scriptsLib);
	}

	gulp.task('build', build);
	gulp.task('default', watch);

