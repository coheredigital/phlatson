var gulp = require('gulp'),
    sass = require('gulp-sass'),
    neat = require('node-neat').includePaths,
    // sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('gulp-autoprefixer');

var browserSync = require('browser-sync').create();

var sassFiles = './system/extensions/**/*.scss';

// browser sync proxy server
gulp.task('serve', function() {
    browserSync.init({
        proxy: "flatbed.dev"
    });
    gulp.watch(sassFiles, ['sass']);
});


gulp.task('sass', function () {
  return gulp.src(sassFiles)
    .pipe(sass({
    		outputStyle: "compressed",
    		includePaths: ['styles'].concat(neat),
    		errLogToConsole: true,
    	}).on('error', sass.logError))
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(gulp.dest('./site/templates/styles'))
    .pipe(browserSync.stream());
});



gulp.task('default', ['serve']);
