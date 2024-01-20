const {src, dest, watch, parallel, series} = require('gulp');

const scss = require('gulp-sass')(require('sass'));
const concat = require('gulp-concat');
const uglify = require('gulp-uglify-es').default;
const browserSync = require('browser-sync').create();
const autoprefixer = require('gulp-autoprefixer');
const clean = require('gulp-clean');
const imagemin = require('gulp-imagemin');

function scripts() {
	return src([
		'node_modules/bootstrap/dist/js/bootstrap.js',
		'node_modules/swiper/swiper-bundle.js',
		'wp-content/themes/awax_beta/assets/js/main.js'
		])
	.pipe(concat('main.min.js'))
	.pipe(uglify())
	.pipe(dest('wp-content/themes/awax_beta/assets/js'))
	.pipe(browserSync.stream())
}

function styles() {
	return src('wp-content/themes/awax_beta/assets/scss/style.scss')
	.pipe(autoprefixer({overrideBrowserslist: ['last 10 version']}))
	.pipe (concat('style.min.css'))
	.pipe(scss({outputStyle: 'compressed'}))
	.pipe(dest('wp-content/themes/awax_beta/assets/css'))
	.pipe(browserSync.stream())
}

function watching() {
	watch(['wp-content/themes/awax_beta/assets/scss/style.scss'], styles)
	watch(['wp-content/themes/awax_beta/assets/js/main.js'], scripts)
	watch(['wp-content/themes/awax_beta/*.php']).on('change', browserSync.reload)
}

function browsersync() {
	browserSync.init({
		proxy:"awax-dv.dev",
		notify: false
	});
}

function cleanDist() {
	return src('wp-content/themes/awax')
	.pipe(clean())
}

function building() {
	return src([
		'wp-content/themes/awax_beta/assets/css/style.min.css',
		'wp-content/themes/awax_beta/assets/js/main.min.js',
		'wp-content/themes/awax_beta/*.php',
		'wp-content/themes/awax_beta/assets/img/**/*',
		'wp-content/themes/awax_beta/assets/fonts/**/*',
		'wp-content/themes/awax_beta/*.png'
	], {base : 'wp-content/themes/awax_beta'})
	.pipe(dest('wp-content/themes/awax'))
}

function img() {
	return src('wp-content/themes/awax_beta/assets/img/*')
	.pipe(imagemin({
		progressive: true
	}))
	.pipe(dest('wp-content/themes/awax_beta/assets/img'))
}

exports.styles = styles;
exports.scripts = scripts;
exports.watching = watching;
exports.browsersync = browsersync;
exports.build = series(cleanDist, building);
exports.img = img;


exports.default = parallel(styles, scripts, img, browsersync, watching);
