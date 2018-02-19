/*jslint node: true */
"use strict";

var $           = require('gulp-load-plugins')();
var argv        = require('yargs').argv;
var gulp        = require('gulp');
var path        = require('path');
var browserSync = require('browser-sync').create();
var merge       = require('merge-stream');
var sequence    = require('run-sequence');
var colors      = require('colors');
var del         = require('del');
var cleanCSS    = require('gulp-clean-css');
var uglify      = require('gulp-uglify');
var notify      = require('gulp-notify');

// Enter URL of your local server here
// Example: 'http://localwebsite.dev'
var URL = 'localhost/wp';

// Check for --production flag
var isProduction = !!(argv.production);

// Browsers to target when prefixing CSS.
var COMPATIBILITY = [
    'last 2 versions',
    'ie >= 9',
    'Android >= 2.3'
];

// Browsersync task
gulp.task('browser-sync', ['build'], function() {

    var files = [
        '**/*.php',
        'assets/dist/images/**/*.{png,jpg,gif}'
    ];

    browserSync.init(files, {
        // Proxy address
        proxy: URL
    });
});

// Compile Sass into CSS
// In production, the CSS is compressed
gulp.task('sass', function() {
    return gulp.src('assets/src/scss/style.scss')
        .pipe($.sourcemaps.init())
        .pipe($.sass())
        .on('error', $.notify.onError({
            message: "<%= error.message %>",
            title: "Sass Error"
        }))
        .pipe($.autoprefixer({
            browsers: COMPATIBILITY
        }))
        .pipe(cleanCSS())
        .pipe($.if(!isProduction, $.sourcemaps.write('.')))
        .pipe(gulp.dest('assets/dist/css'))
        .pipe(browserSync.stream({match: '**/*.css'}))
        .pipe(notify('Compiled: SCSS'));
});

// Combine JavaScript into one file
// In production, the file is minified
gulp.task('javascript', function() {
    var uglify = $.uglify()
        .on('error', $.notify.onError({
            message: "<%= error.message %>",
            title: "Uglify JS Error"
        }));

    return gulp.src([
        'node_modules/foundation-sites/dist/js/plugins/foundation.core.js',
        'node_modules/foundation-sites/dist/js/plugins/foundation.util.*',
        'node_modules/foundation-sites/dist/js/plugins/foundation.drilldown.js',
        'node_modules/foundation-sites/dist/js/plugins/foundation.dropdownMenu.js',
        'node_modules/foundation-sites/dist/js/plugins/foundation.responsiveMenu.js',
        'node_modules/foundation-sites/dist/js/plugins/foundation.responsiveToggle.js',
        'assets/src/javascript/plugins/*.js',
        'assets/src/javascript/scripts.js'])
        .pipe($.sourcemaps.init())
        .pipe($.babel())
        .pipe($.concat('global.js', {
            newLine:'\n;'
        }))
        .pipe($.if(isProduction, uglify))
        .pipe($.if(!isProduction, $.sourcemaps.write()))
        .pipe(gulp.dest('assets/dist/javascript'))
        .pipe(browserSync.stream())
        .pipe(notify('Compiled: Javascript'));
});

// Copy task
gulp.task('copy', function() {
    // What Input
    var whatInput = gulp.src('assets/components/what-input/what-input.js')
        .pipe($.flatten())
        .pipe(gulp.dest('assets/src/javascript/plugins/'));

    return merge(whatInput);
});

// Build task
// Runs copy then runs sass & javascript in parallel
gulp.task('build', ['clean'], function(done) {
    sequence('copy',
        ['sass', 'javascript'],
        done);
});

// Clean task
gulp.task('clean', function(done) {
    sequence(['clean:javascript', 'clean:css'],
        done);
});

// Clean JS
gulp.task('clean:javascript', function() {
    return del([
        'assets/dist/javascript/global.js'
    ]);
});

// Clean CSS
gulp.task('clean:css', function() {
    return del([
        'assets/dist/css/style.css',
        'assets/dist/css/style.css.map'
    ]);
});

// Default gulp task
// Run build task and watch for file changes
gulp.task('default', ['build', 'browser-sync'], function() {
    // Log file changes to console
    function logFileChange(event) {
        var fileName = path.relative(__dirname, event.path);
        console.log('[' + 'WATCH'.green + '] ' + fileName.magenta + ' was ' + event.type + ', running tasks...');
    }

    // Sass Watch
    gulp.watch(['assets/src/scss/**/*.scss'], ['clean:css', 'sass'])
        .on('change', function(event) {
            logFileChange(event);
        });

    // JS Watch
    gulp.watch(['assets/src/**/*.js'], ['clean:javascript', 'javascript'])
        .on('change', function(event) {
            logFileChange(event);
        });
});
