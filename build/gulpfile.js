// ---------------------------------------------------------------------------------
// libs

	var gulp			= require('gulp'),

		// tools
		sass			= require('gulp-sass'),
		uglify			= require('gulp-uglify'),
		sourcemaps		= require('gulp-sourcemaps'),

		// utilities
		argv			= require('yargs').argv,
		gulpif			= require('gulp-if'),
		rename			= require('gulp-rename'),
		concat			= require('gulp-concat');


// ---------------------------------------------------------------------------------
// config

	// paths
	var src =
	{
		css: '../resources/assets/sass/**/*.scss',
		js : '../resources/assets/js/**/*.js'
	};

	var trg =
	{
		css: '../public/',
		js : '../public/'
	};

	var files =
	{
		js : 'doodle.js'
	};


// ---------------------------------------------------------------------------------
// functions

	function styles()
	{
		return gulp
			.src(src.css)

			// source maps for local and staging
			.pipe(gulpif(!argv.live, sourcemaps.init()))

			// compress only when live
			.pipe(sass(argv.live ? {outputStyle: 'compressed'} : null).on('error', sass.logError))

			// source maps for local and staging
			.pipe(gulpif(!argv.live, sourcemaps.write()))

			// save
			.pipe(gulp.dest(trg.css));
	}

	function scripts()
	{
		return gulp
			.src(src.js)

			// source maps for local and staging
			.pipe(gulpif(!argv.live, sourcemaps.init()))

			// always concat + uglify, but mangle only on live
			//.pipe(uglify({compress: true, mangle: argv.live}))
			.pipe(concat(files.js))

			// source maps only if not live
			.pipe(gulpif(!argv.live, sourcemaps.write()))

			// save
			//.pipe(rename({extname: '.min.js'}))
			.pipe(gulp.dest(trg.js));
	}

	function build()
	{
		gulp.start('styles', 'scripts');
	}

	function run()
	{
		build();
		gulp.watch(src.css, ['styles']);
		gulp.watch(src.js, ['scripts']);
	}


// ---------------------------------------------------------------------------------
// tasks

	gulp.task('styles', styles);

	gulp.task('scripts', scripts);

	/**
	 * Build styles and scripts
	 *
	 * Call "gulp build --live" to mangle/compress and skip source maps
	 */
	gulp.task('build', build);

	/**
	 * Monitor styles and scripts, and live-reload when saved
	 */
	gulp.task('default', run);

