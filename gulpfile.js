'use strict';
/*--------------------------------------------------------------
 # Gulp

 # Learn more about Gulp task runner here: https://scotch.io/tutorials/automate-your-tasks-easily-with-gulp-js
 --------------------------------------------------------------*/

/* ---------------- Setup Project ---------------- */

/*
 # Environments:
 * SFTP - Run: gulp sftp
 * FTP - Run: gulp ftp
 * Local - Run: gulp local
 */

const syncUrl = 'localhost/wp'; // Livereload URL
const hostUrl = 'projects.beetroot.se'; // SFTP/FTP URL
const uploadFolder = '/wp-content/themes/beetroot'; // SFTP/FTP upload folder

// # SFTP/FTP credentials
const uploadUser = '#'; // Login
const uploadPass = '#'; // Password

/* Set browser support prefixes
 See more at: https://github.com/ai/browserslist#queries  */

const autoprefixerOptions = {
    browsers: [
        'ie >= 9',
        'ie_mob >= 10',
        'last 2 versions']
};

/* ---------------- Enable Plugins ---------------- */

var gulp = require('gulp'); // Core Gulp

var sass = require('gulp-sass'); // SCSS compiler
var autoprefixer = require('gulp-autoprefixer'); // CSS Autoprefix from Can I Use
var sourcemaps = require('gulp-sourcemaps'); // Sourcemaps

var concat = require('gulp-concat-util'); // Concatenate JS files

var imagemin = require('gulp-imagemin'); // Compress images
var pngquant = require('imagemin-pngquant'); // Run "npm i -D imagemin-pngquant" if npm install fails to install this dependency

var sftp = require('gulp-sftp'); // SFTP
var ftp = require('ftp'); // FTP (https://github.com/mscdex/node-ftp)

var watch = require('gulp-watch'); // Watcher
var browserSync = require("browser-sync"); // Browser Sync

/* ---------------- Plugins Configuration ---------------- */

// Local files array for watcher
var localFilesGlob = ['assets/src/scss/**/*.scss', 'assets/src/javascript/**/*.js', 'assets/dist/javascript/*.js','assets/dist/javascript/*.map', '**/*.php', 'assets/dist/css/*.css', 'assets/dist/css/*.map', 'assets/dist/img/*'];

var reload = browserSync.reload; // Manual BrowserSync reload

/* ---------------- Register Tasks ---------------- */

// # SCSS Compile
// Docs: https://github.com/sass/node-sass
gulp.task('sass', function () {
    watch('assets/src/**/*.scss', function () {
        return gulp.src('assets/src/scss/style.scss')
            .pipe(sourcemaps.init())
            .pipe(sass({
                errLogToConsole: true,
                style: 'compressed',
                precision: 10
            }).on('error', sass.logError))
            .pipe(autoprefixer(autoprefixerOptions))
            .pipe(sourcemaps.write('.'))
            .pipe(gulp.dest('assets/dist/css'));
    })
});

// # Transpile and concatenate JS files
gulp.task("javascript", function () {
    watch('assets/src/javascript/**/*.js', function () {
        return gulp.src(['assets/src/javascript/plugins/*.js', 'assets/src/javascript/scripts.js'])
            .pipe(concat("global.js", {
                newLine: ';\n'
            }))
            .pipe(gulp.dest("assets/dist/javascript"));
    })
});

// # Image Min
gulp.task('imgmin', function () {
    watch('assets/src/img/*', function () {
        return gulp.src('assets/src/img/*')
            .pipe(imagemin({
                progressive: true,
                svgoPlugins: [{
                    removeViewBox: false
                }],
                use: [pngquant()]
            }))
            .pipe(gulp.dest('assets/dist/img'))
    })
});

// # Browser Sync Server
gulp.task('browser-sync', function () {
    browserSync({
        notify: false,
        ui: false,
        proxy: syncUrl
    });
});


// # Watch and deploy tasks.
// Watch the local copy for changes and copies the new files to the server whenever an update is detected, reloads browser after each successfull transfer
// -- SFTP
gulp.task('sftp-deploy-watch', function () {
    gulp.watch(localFilesGlob)
        .on('change', function (event) {
            process.stdout.write('File "' + event.path + '" changed, uploading to server\n');

            return gulp.src([event.path], {
                base: '.',
                buffer: false
            })
                .pipe(sftp({
                    host: hostUrl,
                    remotePath: uploadFolder,
                    port: 2222,
                    callback: reload,
                    user: uploadUser,
                    pass: uploadPass
                }))
        });
});

// -- FTP
gulp.task('ftp-deploy-watch', function () {
    gulp.watch(localFilesGlob)
        .on('change', function (event) {
            process.stdout.write('File "' + event.path + '" changed, uploading to server\n');
            var srcFilePath = event.path;
            var destFilePath = uploadFolder + '/' + srcFilePath.replace(__dirname + '\\', '').replace(/\\/g, "/");
            var c = new ftp();
            // After FTP connection established:
            c.on('ready', function () {
                // Try to push file to server
                c.put(srcFilePath, destFilePath, function (err) {
                    // Log error
                    process.stdout.write('Uploading to => '+destFilePath + '\n');
                        if (err) {
                            // If error matches FTP 550 code (target folder(s) doesnt exist) try recursive MKDIR
                            if (err['code'] == 550) {
                                process.stdout.write('One of path dirs doesn\'t exist, attempting to mkdir..\n');
                                var getFolderStructure = destFilePath.substring(0, destFilePath.lastIndexOf("/") + 1);
                                c.mkdir(getFolderStructure, true, function (err) {
                                    if (err) {
                                        process.stdout.write(err);
                                    }
                                });
                                // Then try to upload file again
                                c.put(srcFilePath, destFilePath, function (err) {
                                    process.stdout.write('Uploading to => '+destFilePath + '\n');
                                    if (err) {
                                        process.stdout.write(err);
                                    }
                                });
                            }
                        }
                    }
                );
                c.end();
            }).on('close', function(){
                process.stdout.write('Successfully uploaded!\n');
                reload();
            } );
            // Init connection
            c.connect(
                {
                    host: hostUrl,
                    user: uploadUser,
                    password: uploadPass
                }
            )
        })
});

// -- Local
gulp.task('local-watch', function () {
    gulp.watch(localFilesGlob)
        .on('change', function (event) {
            process.stdout.write('File "' + event.path + '" changed\n');
            reload();
        })
});
// Run envirnoment (SFTP)
gulp.task('sftp', ['sass', 'imgmin', 'javascript', 'sftp-deploy-watch', 'browser-sync'], function () {
    process.stdout.write('\n ----------------------------------\n Ready. Waiting for changes... \n ----------------------------------\n');
});
// Run envirnoment (FTP)
gulp.task('ftp', ['sass', 'imgmin', 'javascript', 'ftp-deploy-watch', 'browser-sync'], function () {
    process.stdout.write('\n ----------------------------------\n Ready. Waiting for changes... \n ----------------------------------\n');
});
// Run envirnoment (Local)
gulp.task('local', ['sass', 'imgmin', 'javascript', 'local-watch', 'browser-sync'], function () {
    process.stdout.write('\n ----------------------------------\n Ready. Waiting for changes... \n ----------------------------------\n');
});