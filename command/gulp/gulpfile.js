
/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

var gulp = require ('gulp'),
    livereload = require('gulp-livereload');

gulp.task ('default', function () {
  livereload.listen ();

  ['./root/application/**/*.+(css|js|html|php)'].forEach (function (t) {
    gulp.watch (t).on ('change', function () {
      gulp.run ('reload');
    });
  });
});

gulp.task ('reload', function () {
  livereload.changed ();
  console.info ('\nReLoad Browser!\n');
});
