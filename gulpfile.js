// PLUGINS
const gulp = require("gulp");
const sass = require("gulp-sass");
const autoprefixer = require("gulp-autoprefixer");
const cleanCSS = require("gulp-clean-css");
const del = require("del");
const pump = require("pump");
const rename = require("gulp-rename");
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
            cleanCSS(),
            rename({ extname: '.min.css' }),
            gulp.dest(cssFilesDestination)
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
        proxy: "http://mediatool.localhost/",
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
