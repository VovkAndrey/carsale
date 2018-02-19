"use strict";

const $           = require('gulp-load-plugins')();
const argv        = require('yargs').argv;
const gulp        = require('gulp');
const path        = require('path');
const browserSync = require('browser-sync').create();
const eslint      = require('gulp-eslint');
const merge       = require('merge-stream');
const sequence    = require('run-sequence');
const colors      = require('colors');
const del         = require('del');
const cleanCSS    = require('gulp-clean-css');
const uglify      = require('gulp-uglify');
const notify      = require('gulp-notify');
// More info about Wiredep config: https://github.com/taptapship/wiredep
const wiredep     = require('wiredep')({
  directory: './assets/components',
  exclude: [ /jquery/, /less/ ]
});

// Enter URL of your local server here
// Example: 'http://localwebsite.dev'
const URL = 'localhost/wp';

// Check for --production flag
const isProduction = !!(argv.production);

// Browsers to target when prefixing CSS.
const COMPATIBILITY = [
  'last 2 versions',
  'ie >= 9',
  'Android >= 2.3'
];

// File paths to various assets are defined here.
const PATHS = wiredep;
// Add custom JS
PATHS.js.push(
  'assets/src/javascript/plugins/*.js',
  'assets/src/javascript/scripts.js'
);

// Browsersync task
gulp.task('browser-sync', ['build'], function() {

  const files = [
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
    .pipe($.if(!isProduction, $.sourcemaps.write('.')))
    .pipe(gulp.dest('assets/dist/css'))
    .pipe(browserSync.stream({match: '**/*.css'}))
    .pipe(notify('Compiled: SCSS'));
});

// Combine JavaScript into one file
// In production, the file is minified
gulp.task('javascript', function() {
  const uglify = $.uglify()
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
  const whatInput = gulp.src('assets/components/what-input/what-input.js')
    .pipe($.flatten())
    .pipe(gulp.dest('assets/src/javascript/plugins/'));

  // Glyphicons
  const glyphicons = gulp.src('assets/components/bootstrap-sass/assets/fonts/bootstrap/**/*.*')
    .pipe(gulp.dest('assets/dist/fonts/bootstrap'));

  return merge(whatInput, glyphicons);
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
  sequence(['clean:javascript', 'clean:css'], done);
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

// ESLint task
gulp.task('lint', function() {
  return gulp.src(['assets/src/javascript/scripts.js'])
    .pipe(eslint({
      useEslintrc: true
    }))
    .pipe(eslint.result(function(result) {
      console.log(`ESLint result: ${result.filePath}`);
      console.log(`# Messages: ${result.messages.length}`);
      console.log(`# Warnings: ${result.warningCount}`);
      console.log(`# Errors: ${result.errorCount}`);
    }))
    .pipe(eslint.format())
    .pipe(eslint.failAfterError());
});

// Default gulp task
// Run build task and watch for file changes
gulp.task('default', ['lint', 'build', 'browser-sync'], function() {
  // Log file changes to console
  function logFileChange(event) {
    const fileName = path.relative(__dirname, event.path);
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
