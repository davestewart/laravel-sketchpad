// ---------------------------------------------------------------------------------
// libs

	var gulp			= require('gulp'),

		// tools
		sass			= require('gulp-sass'),
		uglify			= require('gulp-uglify'),
		sourcemaps		= require('gulp-sourcemaps'),
		livereload		= require('gulp-livereload'),

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
		css: 'resources/assets/sass/**/*.scss',
		js : 'resources/assets/js/**/*.js'
	};

	var trg =
	{
		css: 'public/assets/css/',
		js : 'public/assets/js/'
	};


// ---------------------------------------------------------------------------------
// sub tasks

	gulp.task('styles', function ()
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
			.pipe(gulp.dest(trg.css))

			// livereload
			.pipe(livereload());
	});

	gulp.task('scripts', function ()
	{
		return gulp
			.src(src.js)

			// source maps for local and staging
			.pipe(gulpif(!argv.live, sourcemaps.init()))

			// always concat + uglify, but mangle only on live
			.pipe(uglify({compress: true, mangle: argv.live}))
			.pipe(concat('all.js'))

			// source maps only if not live
			.pipe(gulpif(!argv.live, sourcemaps.write()))

			// save
			.pipe(rename({extname: '.min.js'}))
			.pipe(gulp.dest(trg.js))

			// livereload
			.pipe(livereload());
	});


// ---------------------------------------------------------------------------------
// main tasks

	/**
	 * Build styles and scripts
	 *
	 * Call "gulp build --live" to mangle/compress and skip source maps
	 */
	gulp.task('build', function ()
	{
		gulp.start('styles', 'scripts');
	});

	/**
	 * Monitor styles and scripts, and live-reload when saved
	 */
	gulp.task('default', function ()
	{
		// live-reload
		livereload({start: true});
		livereload.listen();

		// watch
		gulp.watch(src.css, ['styles']);
		gulp.watch(src.js, ['scripts']);
	});

