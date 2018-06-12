const gulp = require('gulp');
const concat = require('gulp-concat');
const sass = require('gulp-sass');

gulp.task('js', () =>
    gulp.src([
        'node_modules/prismjs/prism.js',
        'node_modules/prismjs/plugins/show-invisibles/prism-show-invisibles.js',
        'node_modules/prismjs/components/prism-clike.js',
        'node_modules/prismjs/components/prism-markup-templating.js',
        'node_modules/prismjs/components/prism-php.js',
        'node_modules/prismjs/components/prism-diff.js',
        'assets/app.js',
    ])
        .pipe(concat('app.js'))
        .pipe(gulp.dest('html'))
);

gulp.task('css', () =>
    gulp.src('assets/style.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('html'))
);

gulp.task('default', gulp.parallel(['js', 'css']));
