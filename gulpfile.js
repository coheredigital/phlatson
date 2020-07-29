// PLUGINS
const gulp = require("gulp");
const sass = require("gulp-sass");
const autoprefixer = require("gulp-autoprefixer");
const pump = require("pump");
var browserSync = require('browser-sync').create();

// FILES
var scssFiles = "site/views/styles/**/*.scss";
var scssIncludePaths = ["site/views/styles/"];
var cssFilesDestination = "site/views/styles/";
var phpFiles = "site/views/**/*.php";


gulp.task("build:styles", function (callback) {
    pump(
        [
            gulp.src(scssFiles),
            sass({
                errLogToConsole: false,
                includePaths: scssIncludePaths
            }),
            autoprefixer(),
            gulp.dest(cssFilesDestination),
        ],
        callback
    );
});

gulp.task(
    "build",
    gulp.series(
        "build:styles"
    )
);

gulp.task("sync", function () {

    // sync in browser
    browserSync.init({
        proxy: "http://phlatson.localhost/",
        open: false,
        notify: false
    });

    // auto build
    gulp.watch(scssFiles, gulp.series("build:styles"));
    gulp.watch(phpFiles).on('change', browserSync.reload);
});

class FrameworkExtractor {
    static extract(content) {
        return content.match(/[A-Za-z0-9-_:\/]+/g) || [];
    }
}
