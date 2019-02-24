var gulp = require("gulp");
//var concat = require("gulp-concat");
//var modernizr = require("gulp-modernizr");
//var uglify = require('gulp-uglify');
//var pump = require('pump');
var sass = require('gulp-sass');
var merge = require('merge-stream');

gulp.task('css', function() {
  return gulp.src('./css/front_custom.sass')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./css'));  
});

var vendorjs_sources = [
  './node_modules/jquery/dist/jquery.js',
  './node_modules/foundation-sites/js/foundation.min.js',
  './node_modules/foundation-datepicker/js/foundation-datepicker.min.js',
  './node_modules/slick-carousel/slick/slick.min.js',
  './node_modules/masonry-layout/dist/masonry.pkgd.min.js',
  './node_modules/imagesloaded/imagesloaded.pkgd.min.js',
  './node_modules/modernizr/modernizr.js',
  './node_modules/Jcrop/js/jquery.Jcrop.min.js',
  './node_modules/jqueryui/jquery-ui.min.js',
  './node_modules/jquery-validation/dist/jquery.validate.min.js'
];
var vendorcss_sources = [
  './node_modules/foundation-sites/css/normalize.css',
  './node_modules/foundation-sites/css/foundation.css',
  './node_modules/foundation-datepicker/css/foundation-datepicker.min.css',
  './node_modules/slick-carousel/slick/slick.css',
  './node_modules/Jcrop/css/jquery.Jcrop.min.css',
  './node_modules/slick-carousel/slick/slick-theme.css'
];

gulp.task('vendorjs', function() {
  return gulp.src(vendorjs_sources)
    .pipe(gulp.dest('./vendor/js'));
});

gulp.task('vendorcss', function() {
  return gulp.src(vendorcss_sources)
    .pipe(gulp.dest('./vendor/css'));
});

gulp.task('ckeditor', function() {
  return gulp.src('./node_modules/ckeditor/**')
    .pipe(gulp.dest('./vendor/ckeditor'));
});

gulp.task('vendor', ['vendorjs', 'ckeditor', 'vendorcss']);

gulp.task('default', ['css', 'vendor']);