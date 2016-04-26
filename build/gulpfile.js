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
			.pipe(sourcemaps.init())
			.pipe(sass().on('error', sass.logError))
			.pipe(sourcemaps.write())
			.pipe(gulp.dest(trg.css));
	}

	function scripts()
	{
		return gulp
			.src(src.js)
			.pipe(sourcemaps.init())
			.pipe(uglify({compress: true, mangle: false}))
			.pipe(concat(files.js))
			.pipe(sourcemaps.write())
			.pipe(gulp.dest(trg.js));
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
		gulp.watch(src.css, ['styles']);
		gulp.watch(src.js, ['scripts']);
	}
	

	gulp.task('styles', styles);
	gulp.task('scripts', scripts);
	gulp.task('build', build);
	gulp.task('default', watch);

