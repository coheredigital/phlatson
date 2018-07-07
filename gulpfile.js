var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer');

var browserSync = require('browser-sync').create();

var sassFiles = 'site/**/*.scss';

// browser sync proxy server
gulp.task('serve', function() {
    browserSync.init({
        proxy: "flatbed.test:8888",
        open: false,
        notify: false
    });
    gulp.watch(sassFiles, ['sass']);
});


gulp.task('sass', function () {
  return gulp.src(sassFiles)
    .pipe(sass({
    		outputStyle: "compressed",
    		errLogToConsole: true,
    	}).on('error', sass.logError))
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(gulp.dest('site/.'))
    .pipe(browserSync.stream());
});



gulp.task('default', ['serve']);
