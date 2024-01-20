const {src, dest, watch, parallel, series} = require('gulp');

const scss = require('gulp-sass')(require('sass'));
const concat = require('gulp-concat');
const browserSync = require('browser-sync').create();
const autoprefixer = require('gulp-autoprefixer');
const clean = require('gulp-clean');
const imagemin = require('gulp-imagemin');

function scripts() {
	return src([
		'node_modules/jquery/dist/jquery.min.js',
		'node_modules/jquery.maskedinput/src/jquery.maskedinput.js',
		'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
		'node_modules/@fancyapps/ui/dist/fancybox/fancybox.umd.js',
		'node_modules/wowjs/dist/wow.min.js',
		'app/js/main_beta.js'
		])
	.pipe(concat('main.js'))
	.pipe(dest('app/js'))
	.pipe(browserSync.stream())
}

function styles() {
	return src('app/scss/style.scss')
	.pipe(autoprefixer({overrideBrowserslist: ['last 10 version']}))
	.pipe (concat('style.css'))
	.pipe(scss())
	.pipe(dest('app/css'))
	.pipe(browserSync.stream())
}

function watching() {
	watch(['app/scss/style.scss'], styles)
	watch(['app/js/main_beta.js'], scripts)
	watch(['app/*.html']).on('change', browserSync.reload)
}

function browsersync() {
	browserSync.init({
		server: {
			baseDir:"app/"
		}
	});
}

function cleanDist() {
	return src('dist')
	.pipe(clean())
}

function building() {
	return src([
		'app/css/style.css',
		'app/js/main.js',
		'app/*.html',
		'app/img/**/*',
		'app/fonts/**/*'
	], {base : 'app'})
	.pipe(dest('dist'))
}

function img() {
	return src('app/img/*')
	.pipe(imagemin({
		progressive: true
	}))
	.pipe(dest('app/img'))
}

exports.styles = styles;
exports.scripts = scripts;
exports.watching = watching;
exports.browsersync = browsersync;
exports.build = series(cleanDist, building);
exports.img = img;

exports.default = parallel(styles, scripts, img, browsersync, watching);
