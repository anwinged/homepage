const gulp = require('gulp');
const concat = require('gulp-concat');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');

const ENV = process.env.BUILD_ENV || 'dev';

const SASS_SOURCE_PATH = './source/_assets/**/*.scss';
const SASS_DEST_PATH = `./output_${ENV}`;
const SASS_FILE_NAME = 'app.css';

const SASS_OPTIONS = {
  outputStyle: ENV === 'prod' ? 'compressed' : 'expanded',
};

const AUTOPREFIX_OPTIONS = {
  browsers: ['last 2 versions'],
};

gulp.task('build', function () {
  gulp.src(SASS_SOURCE_PATH)
    .pipe(sass(SASS_OPTIONS).on('error', sass.logError))
    .pipe(autoprefixer(AUTOPREFIX_OPTIONS))
    .pipe(concat(SASS_FILE_NAME))
    .pipe(gulp.dest(SASS_DEST_PATH))
  ;
});

// gulp.task('build:watch', function () {
//   gulp.watch(SASS_SOURCE_PATH, ['build']);
// });
