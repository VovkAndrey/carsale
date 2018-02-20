const $           = require('gulp-load-plugins')();
const argv        = require('yargs').argv;
const gulp        = require('gulp');
const path        = require('path');
const browserSync = require('browser-sync').create();
const eslint      = require('gulp-eslint');
const merge       = require('merge-stream');
const sequence    = require('run-sequence');
const del         = require('del');
const cleanCSS    = require('gulp-clean-css');
const notify      = require('gulp-notify');

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

// Declare Paths
const jsPath = [
    'node_modules/foundation-sites/dist/js/plugins/foundation.core.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.util.*',
    'node_modules/foundation-sites/dist/js/plugins/foundation.drilldown.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.dropdownMenu.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.responsiveMenu.js',
    'node_modules/foundation-sites/dist/js/plugins/foundation.responsiveToggle.js',
    'assets/src/javascript/plugins/*.js',
    'assets/src/javascript/scripts.js'
];

// Browsersync task
gulp.task('browser-sync', ['build'], () => {
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
gulp.task('sass', () => {
    return gulp.src('assets/src/scss/style.scss')
        .pipe($.sourcemaps.init())
        .pipe($.sass())
        .on('error', $.notify.onError({
            message: '<%= error.message %>',
            title: 'Sass Error'
        }))
        .pipe($.autoprefixer({
            browsers: COMPATIBILITY
        }))
        .pipe(cleanCSS())
        .pipe($.if(!isProduction, $.sourcemaps.write('.')))
        .pipe(gulp.dest('assets/dist/css'))
        .pipe(browserSync.stream({ match: '**/*.css' }))
        .pipe(notify('Compiled: SCSS'));
});

// Combine JavaScript into one file
// In production, the file is minified
gulp.task('javascript', ['eslint'], () => {
    const uglify = $.uglify()
        .on('error', $.notify.onError({
            message: '<%= error.message %>',
            title: 'Uglify JS Error'
        }));

    return gulp.src(jsPath)
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
gulp.task('copy', () => {
    // Slick
    const slick = gulp.src('node_modules/slick-carousel/slick/slick.min.js')
        .pipe($.flatten())
        .pipe(gulp.dest('assets/src/javascript/plugins/'));

    return merge(slick);
});

// Build task
// Runs copy then runs sass & javascript in parallel
gulp.task('build', ['clean'], (done) => {
    sequence('copy',
        ['sass', 'javascript'],
        done);
});

// Clean task
gulp.task('clean', (done) => {
    sequence(['clean:javascript', 'clean:css'], done);
});

// Clean JS
gulp.task('clean:javascript', () => {
    return del([
        'assets/dist/javascript/global.js'
    ]);
});

// Clean CSS
gulp.task('clean:css', () => {
    return del([
        'assets/dist/css/style.css',
        'assets/dist/css/style.css.map'
    ]);
});

// ESLint task
gulp.task('eslint', () => {
    return gulp.src(['assets/src/javascript/scripts.js'])
        .pipe(eslint({
            useEslintrc: true
        }))
        .pipe(eslint.format())
        .pipe(eslint.failOnError());
});

// Default gulp task
// Run build task and watch for file changes
gulp.task('default', ['build', 'browser-sync'], () => {
    // Log file changes to console
    function logFileChange(event) {
        const fileName = path.relative(__dirname, event.path);

        console.log('[' + 'WATCH'.green + '] ' + fileName.magenta + ' was ' + event.type + ', running tasks...');
    }

    // Sass Watch
    gulp.watch(['assets/src/scss/**/*.scss'], ['clean:css', 'sass'])
        .on('change', (event) => {
            logFileChange(event);
        });

    // JS Watch
    gulp.watch(['assets/src/**/*.js'], ['clean:javascript', 'javascript'])
        .on('change', (event) => {
            logFileChange(event);
        });
});

