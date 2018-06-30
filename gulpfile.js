var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer');

var browserSync = require('browser-sync').create();

var sassFiles = '**/*.scss';

// browser sync proxy server
gulp.task('serve', function() {
    browserSync.init({
        proxy: "localhost:8080",
        open: false,
        notify: false
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
    .pipe(gulp.dest('.'))
    .pipe(browserSync.stream());
});



gulp.task('default', ['serve']);
