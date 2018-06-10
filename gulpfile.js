const gulp = require('gulp');
const concat = require('gulp-concat');

gulp.task('js', () =>
    gulp.src([
        'node_modules/prismjs/prism.js',
        'node_modules/prismjs/plugins/show-invisibles/prism-show-invisibles.js',
        'node_modules/prismjs/components/prism-php.js',
        'node_modules/prismjs/components/prism-diff.js',
    ])
        .pipe(concat('app.js'))
        .pipe(gulp.dest('html'))
);

gulp.task('css', () =>
    gulp.src([
        'node_modules/bootstrap/dist/css/bootstrap.css',
        'node_modules/prismjs/themes/prism.css',
        'node_modules/prismjs/plugins/show-invisibles/prism-show-invisibles.css',
    ])
        .pipe(concat('style.css'))
        .pipe(gulp.dest('html'))
);

gulp.task('default', gulp.parallel(['js', 'css']));
