var gulp = require('gulp');
var plumber = require('gulp-plumber');
var gulpif = require('gulp-if');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var maps = require('gulp-sourcemaps');
var argv = require('yargs').argv;
var del = require('del');
var include = require('gulp-include');
var babel = require('gulp-babel');

var config = require('../config');

gulp.task('js', function() {
  del(config.js.dest + '/*.*', { force : true });

  return gulp.src(config.js.src)
    .pipe(plumber())
    .pipe(maps.init())
    .pipe(include())
    .pipe(babel(config.babel))
    .pipe(gulpif(!argv.debug, uglify()))
    .pipe(concat('compiled.js'))
    .pipe(maps.write('.'))
    .pipe(gulp.dest(config.js.dest));
});
