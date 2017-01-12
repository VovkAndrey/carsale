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
// More info about Wiredep config: https://github.com/taptapship/wiredep
var wiredep     = require('wiredep')({
    directory: './assets/components',
    exclude: [ /jquery/, /less/ ]
});

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

// File paths to various assets are defined here.
var PATHS = wiredep;
// Add custom JS
PATHS.js.push(
    'assets/src/javascript/plugins/*.js',
    'assets/src/javascript/scripts.js'
);

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
    .pipe($.sass({
      includePaths: PATHS.scss
    }))
    .on('error', $.notify.onError({
        message: "<%= error.message %>",
        title: "Sass Error"
    }))
    .pipe($.autoprefixer({
      browsers: COMPATIBILITY
    }))
    .pipe(cleanCSS())
    .pipe($.if(!isProduction, $.sourcemaps.write('.'))
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

  return gulp.src(PATHS.js)
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

  // Glyphicons
  var glyphicons = gulp.src('assets/components/bootstrap-sass/assets/fonts/bootstrap/**/*.*')
      .pipe(gulp.dest('assets/dist/fonts/bootstrap'));

  return merge(whatInput, glyphicons);
});


// Build task
// Runs copy then runs sass & javascript in parallel
gulp.task('build', ['clean'], function(done) {
  sequence('copy',
          ['sass', 'javascript', 'lint'],
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
  gulp.watch(['assets/src/**/*.js'], ['clean:javascript', 'javascript', 'lint'])
    .on('change', function(event) {
      logFileChange(event);
    });
});
