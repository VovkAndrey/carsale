'use strict';
/*--------------------------------------------------------------
 # Gulp
 Tasks:
 * SFTP - Run: gulp sftp
 * FTP - Run: gulp ftp
 * Local - Run: gulp local

 # Learn more about Gulp task runner here: https://scotch.io/tutorials/automate-your-tasks-easily-with-gulp-js
 --------------------------------------------------------------*/

/* ---------------- Enable Plugins ---------------- */
var gulp = require('gulp'), // Core Gulp
    sass = require('gulp-sass'), // SCSS compiler
    autoprefixer = require('gulp-autoprefixer'), // CSS Autoprefix from Can I Use
    sourcemaps = require('gulp-sourcemaps'), // Sourcemaps
    concat = require('gulp-concat-util'), // Concatenate JS files
    imagemin = require('gulp-imagemin'), // Compress images
    pngquant = require('imagemin-pngquant'), // Run "npm i -D imagemin-pngquant" if npm install fails to install this dependency
    sftp = require('gulp-sftp'), // SFTP
    ftp = require('ftp'), // FTP (https://github.com/mscdex/node-ftp)
    watch = require('gulp-watch'), // Watcher
    browserSync = require("browser-sync"), // Browser Sync
    notify = require("gulp-notify"); // System notifications for tasks


/* ---------------- Setup Plugins ---------------- */

// Local files array for watcher
var localFilesGlob = [
    '**/*.php',
    'assets/src/scss/**/*.scss',
    'assets/src/javascript/**/*.js',
    'assets/dist/javascript/*.js',
    'assets/dist/javascript/*.map',
    'assets/dist/css/*.css',
    'assets/dist/css/*.map',
    'assets/dist/img/*'
];
// Manual BrowserSync reload
var reload = browserSync.reload;

/* ---------------- Setup Project ---------------- */

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
        'last 2 versions'
    ]
};



/* ---------------- Register Tasks ---------------- */

// # SCSS Compile
// Docs: https://github.com/sass/node-sass
gulp.task('sass', function() {
    watch('assets/src/**/*.scss', function() {
        return gulp.src('assets/src/scss/style.scss')
            .pipe(sourcemaps.init())
            .pipe(sass({
                errLogToConsole: true,
                outputStyle: 'compressed',
                precision: 10
            }).on('error', sass.logError))
            .pipe(autoprefixer(autoprefixerOptions))
            .pipe(sourcemaps.write('.'))
            .pipe(notify("SCSS Compiled"))
        .pipe(gulp.dest('assets/dist/css'));
    })
});

// # Transpile and concatenate JS files
gulp.task("javascript", function() {
    watch('assets/src/javascript/**/*.js', function() {
        return gulp.src(['assets/src/javascript/plugins/*.js', 'assets/src/javascript/scripts.js'])
            .pipe(concat("global.js", {
                newLine: ';\n'
            }))
            .pipe(notify("JS Compiled"))
        .pipe(gulp.dest("assets/dist/javascript"));
    })
});

// # Image Min
gulp.task('imgmin', function() {
    watch('assets/src/img/*', function() {
        return gulp.src('assets/src/img/*')
            .pipe(imagemin({
                progressive: true,
                svgoPlugins: [{
                    removeViewBox: false
                }],
                use: [pngquant()]
            }))
            .pipe(notify("Images Compressed"))
        .pipe(gulp.dest('assets/dist/img'))
    })
});

// # Browser Sync Server
gulp.task('browser-sync', function() {
    browserSync({
        notify: false,
        ui: false,
        proxy: syncUrl
    });
});


// # Watch and deploy tasks.
// Watch the local copy for changes and copies the new files to the server whenever an update is detected, reloads browser after each successfull transfer
// -- SFTP
gulp.task('sftp-deploy-watch', function() {
    gulp.watch(localFilesGlob)
        .on('change', function(event) {
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
                .pipe(notify("Files uploaded via SFTP"));
        });
});

// -- FTP
gulp.task('ftp-deploy-watch', function() {
    gulp.watch(localFilesGlob)
        .on('change', function(event) {
            process.stdout.write('File "' + event.path + '" changed, uploading to server\n');
            var srcFilePath = event.path;
            var destFilePath = uploadFolder + '/' + srcFilePath.replace(__dirname + '\\', '').replace(/\\/g, "/");
            var ftpConnection = new ftp();
            // After FTP connection established:
            ftpConnection.on('ready', function() {
                // Try to push file to server
                ftpConnection.put(srcFilePath, destFilePath, function(err) {
                    // Log error
                    process.stdout.write('Uploading to => ' + destFilePath + '\n');
                    if (err) {
                        // If error matches FTP 550 code (target folder(s) doesnt exist) try recursive MKDIR
                        if (err['code'] == 550) {
                            process.stdout.write('One of path dirs doesn\'t exist, attempting to mkdir..\n');
                            var getFolderStructure = destFilePath.substring(0, destFilePath.lastIndexOf("/") + 1);
                            ftpConnection.mkdir(getFolderStructure, true, function(err) {
                                if (err) {
                                    process.stdout.write(err);
                                }
                            });
                            // Then try to upload file again
                            ftpConnection.put(srcFilePath, destFilePath, function(err) {
                                process.stdout.write('Uploading to => ' + destFilePath + '\n');
                                if (err) {
                                    process.stdout.write(err);
                                }
                            });
                        }
                    }
                });
                ftpConnection.end();
            }).on('close', function() {
                process.stdout.write('Successfully uploaded!\n');
                notify("Files uploaded via FTP");
                reload();
            });
            // Init connection
            ftpConnection.connect({
                host: hostUrl,
                user: uploadUser,
                password: uploadPass
            })
        })
});

// -- Local
gulp.task('local-watch', function() {
    gulp.watch(localFilesGlob)
        .on('change', function(event) {
            process.stdout.write('File "' + event.path + '" changed\n');
            reload();
        })
});
// Run envirnoment (SFTP)
gulp.task('sftp', ['sass', 'imgmin', 'javascript', 'sftp-deploy-watch', 'browser-sync'], function() {
    process.stdout.write('\n ----------------------------------\n Ready. Waiting for changes... \n ----------------------------------\n');
});
// Run envirnoment (FTP)
gulp.task('ftp', ['sass', 'imgmin', 'javascript', 'ftp-deploy-watch', 'browser-sync'], function() {
    process.stdout.write('\n ----------------------------------\n Ready. Waiting for changes... \n ----------------------------------\n');
});
// Run envirnoment (Local)
gulp.task('local', ['sass', 'imgmin', 'javascript', 'local-watch', 'browser-sync'], function() {
    process.stdout.write('\n ----------------------------------\n Ready. Waiting for changes... \n ----------------------------------\n');
});
