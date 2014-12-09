# gulp-livereload
require! <[
  browserify
  gulp
  gulp-autoprefixer
  gulp-stylus
  gulp-livescript
  vinyl-buffer
  vinyl-source-stream
]>

# Stylus
gulp.task 'stylus', ->
  distDest = './css'
  gulp.src ['./src/stylus/main.styl', './src/stylus/app.styl']
    .pipe gulp-stylus!
    .pipe gulp.dest distDest

gulp.task 'js', ->
  distDest = './js'
  browserify './src/ls/app.js'
    .bundle!
    .pipe vinyl-source-stream 'app.js'
    .pipe gulp.dest distDest

# javascript
# gulp.task 'js', ->
#   distDest = './js'
#   browserify (
#     transform: ['liveify']
#     entries:
#        * './src/ls/app.ls'
#        ...
#     extensions:
#        * '.ls'
#        ...
#   )
#   .bundle!
#   # .pipe vinyl-source-stream 'app.js'
#   # .pipe gulp.dest distDest

# Rerun the task when a file changes
gulp.task 'watch', !->
  gulp.watch [
    './src/stylus/**/*.styl'
    './src/ls/**/*.ls'
  ], [
    'stylus'
    'js'
  ]
    # .on 'change', gulp-livereload.changed

# The default task (called when you run `gulp` from cli)
gulp.task 'default', ['stylus', 'js']
