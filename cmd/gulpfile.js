var gulp       = require ('gulp'),
    livereload = require('gulp-livereload'),
    uglifyJS   = require ('gulp-uglify'),
    htmlmin    = require('gulp-html-minifier'),
    // imagemin = require('gulp-imagemin'),
    // pngquant = require('imagemin-pngquant'),
    del        = require('del');

// ===================================================

gulp.task ('default', function () {

  livereload.listen ();

  ['./root/*.html', './root/css/**/*.css', './root/js/**/*.js'].forEach (function (t) {
    gulp.watch (t).on ('change', function () {
      gulp.run ('reload');
    });
  });
});
gulp.task ('reload', function () {
  livereload.changed ();
  console.info ('\n== ReLoad Browser! ================================================\n');
});

// ===================================================

gulp.task ('minify', function () {
  gulp.run ('js-uglify');
  gulp.run ('minify-html');
  gulp.run ('image-min');
});
gulp.task ('js-uglify', function () {
  gulp.src ('./root/js/**/*.js')
      .pipe (uglifyJS ())
      .pipe (gulp.dest ('./root/js/'));

  gulp.src ('./root/resource/javascript/**/*.js')
      .pipe (uglifyJS ())
      .pipe (gulp.dest ('./root/resource/javascript/'));
});
gulp.task ('minify-html', function () {
  gulp.src ('./root/*.html')
      .pipe (htmlmin ({collapseWhitespace: true}))
      .pipe (gulp.dest ('./root/'));
});
gulp.task ('image-min', function () {
  // gulp.src ('./root/resource/image/**/*.+(png|jpg|gif)')
  //     .pipe (imagemin ({
  //       progressive: true,
  //       svgoPlugins: [{removeViewBox: false}],
  //       use: [pngquant ()]
  //     }))
  //     .pipe(gulp.dest ('./root/resource/image/'));
});

// ===================================================

gulp.task ('gh-pages', function () {
  del (['./root']);
});