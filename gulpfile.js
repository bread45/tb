

const {src, dest} = require('gulp');
const minifyCss = require('gulp-clean-css');
const minifyJs = require('gulp-uglify');
const sourceMap = require('gulp-sourcemaps');
const concat = require('gulp-concat');

const cssHomeFiles = ['./front/css/bootstrap.min.css', './front/css/slick.min.css', './front/css/pnotify.custom.min.css'
, './front/css/custom.css', './front/css/responsive.css','./front/css/owl.carousel.min.css'];

const bundleHomeCss = () => {
    return src(cssHomeFiles)
    .pipe(sourceMap.init())
    .pipe(minifyCss())
    .pipe(concat('bundleHome.css'))
    .pipe(sourceMap.write())
    .pipe(dest('./front/bundleCss/'));
};  

exports.bundleHomeCss = bundleHomeCss;

const cssResourceFiles = ['./front/css/bootstrap.min.css', './front/css/slick.min.css', './front/css/pnotify.custom.min.css'
, './front/css/custom.css', './front/css/responsive.css','./front/css/owl.carousel.min.css'];

const bundleResourceCss = () => {
    return src(cssResourceFiles)
    .pipe(sourceMap.init())
    .pipe(minifyCss())
    .pipe(concat('bundleResource.css'))
    .pipe(sourceMap.write())
    .pipe(dest('./front/bundleCss/'));
};  

exports.bundleResourceCss = bundleResourceCss;

const cssTrainerFiles = ['./public/front/trainer/css/bootstrap.min.css', './public/front/trainer/css/calendar.css', './public/front/trainer/css/jquery.dataTables.min.css'
, './public/theme/vendors/general/select2/dist/css/select2.css', './front/css/pnotify.custom.min.css', './public/front/trainer/css/custom.css',
 './public/front/trainer/css/developer.css', './public/front/trainer/css/responsive.css'];

const bundleTrainerCss = () => {
    return src(cssTrainerFiles)
    .pipe(sourceMap.init())
    .pipe(minifyCss())
    .pipe(concat('bundleTrainer.css'))
    .pipe(sourceMap.write())
    .pipe(dest('./front/bundleCss/'));
};  

exports.bundleTrainerCss = bundleTrainerCss;

const jsHomeFiles = ['./front/js/jquery-3.4.1.min.js', './front/js/popper.min.js', './front/js/bootstrap.min.js', './front/js/slick.min.js',
 './front/js/lazysizes.min.js', './front/js/pnotify.custom.min.js'];

const bundleHomeJs = () => {
    return src(jsHomeFiles)
    .pipe(sourceMap.init())
    .pipe(minifyJs())
    .pipe(concat('bundleHome.js'))
    .pipe(sourceMap.write())
    .pipe(dest('./front/bundleJs/'));
};

exports.bundleHomeJs = bundleHomeJs;

 const jsTrainerFiles = ['./front/js/jquery-3.4.1.min.js', './front/js/popper.min.js', './front/js/bootstrap.min.js', './front/js/calendar.min.js', './front/js/developer.js'];

const bundleTrainerJs = () => {
    return src(jsTrainerFiles)
    .pipe(sourceMap.init())
    .pipe(minifyJs())
    .pipe(concat('bundleTrainer.js'))
    .pipe(sourceMap.write())
    .pipe(dest('./front/bundleJs/'));
};

exports.bundleTrainerJs = bundleTrainerJs;



