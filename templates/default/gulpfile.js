var gulp = require("gulp");
var concat = require("gulp-concat");
var uglify = require('gulp-uglify');
var pump = require('pump');
var cleanCSS = require('gulp-clean-css');
var awspublish = require('gulp-awspublish');
var rename = require('gulp-rename');

var publisher = awspublish.create({
  region: 'us-east-1',
  params: {
    Bucket: 'storage2.discotecheitalia.it'
  },
  credentials: {
    accessKeyId: process.env.aws_access_key_id,
    secretAccessKey: process.env.aws_secret_access_key
  }
}); 
  
var css_sources = [
  './vendor/css/normalize.css',
  './vendor/css/foundation.css',
  './vendor/css/foundation-datepicker.min.css',
  './vendor/css/slick.css',
  './vendor/css/slick-theme.css',
  './css/foundation_override.css',
  './css/front-custom.css'
];

var js_sources_header = [
  './vendor/js/jquery.js',
  './vendor/js/jquery-ui.min.js',
  './vendor/js/foundation.min.js',
  './vendor/js/foundation-datepicker.min.js',
  './vendor/js/slick.min.js',
  './vendor/js/masonry.pkgd.min.js',
  './vendor/js/imagesloaded.pkgd.min.js',
  './vendor/js/jquery.validate.min.js'
];

var js_sources_footer = [
  './js/front_custom.js',
  './js/modules/ajaxLoadItems.js',
  './js/modules/formvalidators.js'
];

gulp.task('css', function(cb) {
  pump([
    gulp.src(css_sources),
    concat('all.css'),
    cleanCSS(),
    gulp.dest('./assets/css/')
  ], cb);
});

gulp.task('js_header', function(cb) {
  pump([
    gulp.src(js_sources_header),
    concat('header.js'),
    uglify(),
    gulp.dest('./assets/js/')
  ], cb);
});

gulp.task('js_footer', function(cb) {
  pump([
    gulp.src(js_sources_footer),
    concat('footer.js'),
    uglify(),
    gulp.dest('./assets/js/')
  ], cb);
});

gulp.task('publish_css', function(cb) { 
  
  pump([
    gulp.src([
      './css/**/*'
    ]),
    rename(function(path) {
      path.dirname = 'css/' + path.dirname;
    }),
    publisher.publish(),
    awspublish.reporter()
  ], cb);
});  

gulp.task('publish_js', function(cb) { 
  pump([
    gulp.src([
      './js/**/*'
    ]),
    rename(function(path) {
      path.dirname = 'js/' + path.dirname;
    }),
    publisher.publish(),
    awspublish.reporter()
  ], cb);
});

gulp.task('publish_vendor', function(cb) {   
  pump([
    gulp.src([
      './vendor/**/*'
    ]),
    rename(function(path) {
      path.dirname = 'vendor/' + path.dirname;
    }),
    publisher.publish(),
    awspublish.reporter()
  ], cb);
});

gulp.task('publish_images', function(cb) { 
  pump([
    gulp.src([
      './images/**/*'
    ]),
    rename(function(path) {
      path.dirname = 'images/' + path.dirname;
    }),
    publisher.publish(),
    awspublish.reporter()
  ], cb);
});

//gulp.task('default', ['css', 'js_header', 'js_footer', 'publish']);
gulp.task('default', ['publish_css', 'publish_js', 'publish_vendor', 'publish_images']);


