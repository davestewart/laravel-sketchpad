// ---------------------------------------------------------------------------------
// libs

	var gulp			= require('gulp'),

		// tools
		sass			= require('gulp-sass'),
		uglify			= require('gulp-uglify'),
		sourcemaps		= require('gulp-sourcemaps'),

		// utilities
		rename			= require('gulp-rename'),
		concat			= require('gulp-concat');


// ---------------------------------------------------------------------------------
// config

	// paths
	var jsFolder = '../resources/assets/js/';
	var js =
	{
		input:
		{
			all 	:jsFolder + '**/*.js',
			files 	:
			[
				jsFolder + '*/**/*.js',
				jsFolder + 'main.js',
			]
		},
		output:
		{
			folder	:'../publish/assets/',
			file	:'sketchpad.js',
		}
	};

	var css =
	{
		input	: '../resources/assets/sass/**/*.scss',
		output	: '../publish/assets/',
	};


// ---------------------------------------------------------------------------------
// functions

	function styles()
	{
		return gulp
			.src(css.input)
			//.pipe(sourcemaps.init())
			.pipe(sass().on('error', sass.logError))
			//.pipe(sourcemaps.write())
			.pipe(gulp.dest(css.output));
	}

	function scripts()
	{
		return gulp
			.src(js.input.files)
			//.pipe(sourcemaps.init())
			//.pipe(uglify({compress: true, mangle: false}))
			.pipe(concat(js.output.file))
			//.pipe(sourcemaps.write())
			.pipe(gulp.dest(js.output.folder));
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
	}

	/**
	 * Monitor styles and scripts, and live-reload when saved
	 */
	function watch()
	{
		build();
		gulp.watch(css.input, ['styles']);
		gulp.watch(js.input.all, ['scripts']);
	}
	

	gulp.task('styles', styles);
	gulp.task('scripts', scripts);
	gulp.task('build', build);
	gulp.task('default', watch);

