// Load plugins
var gulp = require('gulp'),
	autoprefixer = require('gulp-autoprefixer'),
	minifycss = require('gulp-uglifycss'),
	filter = require('gulp-filter'),
	rename = require('gulp-rename'),
	notify = require('gulp-notify'),
	sass = require('gulp-sass')(require('sass')),
	ignore = require('gulp-ignore'),
	plumber = require('gulp-plumber'),
	concat = require('gulp-concat'),
	sourcemaps = require('gulp-sourcemaps');

// styles
gulp.task('styles', function () {
	return gulp.src([
		'./assets/sass/style.scss',
	])
		.pipe(plumber())
		.pipe(sourcemaps.init({loadMaps: true}))
		.pipe(sass({
			errLogToConsole: true,
			outputStyle: 'expanded',
			precision: 10
		}))
		.pipe(autoprefixer('last 2 version', '> 1%', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
		.pipe(plumber.stop())
		.pipe(concat('style.css'))
		.pipe(sourcemaps.write('.', {includeContent: false, sourceRoot: '_/scss'}))
		.pipe(gulp.dest('./'))
		.pipe(filter('**/*.css')) // Filtering stream to only css files
		.pipe(rename({suffix: '.min'}))
		.pipe(minifycss({
			uglyComments: true
		}))
		.pipe(gulp.dest('./'))
});

// styles dev
gulp.task('styles-dev', function () {
	return gulp.src('./assets/sass/style.scss')
		.pipe(sourcemaps.init({loadMaps: true}))
		.pipe(sass())
		.pipe(concat('style.css'))
		.pipe(sourcemaps.write('.', {includeContent: false, sourceRoot: '_/scss'}))
		.pipe(gulp.dest('./'))
		.pipe(filter('**/*.css')) // Filtering stream to only css files
		.pipe(gulp.dest('./'))
});

// Watch Task
gulp.task('default', function () {
	gulp.watch([
		'./assets/sass/**/*.scss',
	], gulp.series('styles'));
});
