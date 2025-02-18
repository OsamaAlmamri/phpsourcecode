var gulp = require('gulp');
var elixir = require('laravel-elixir');
var minifyCss = require('gulp-minify-css'); //- 压缩CSS为一行;
var uglify = require('gulp-uglify'); //压缩JS
var header = require('gulp-header'); //头部内容写入
var APP_ENV = 'local'; //开发模式用vue.js 方便使用vue DevTools 调试  线上发布使用vue.min.js 
//默认源地图
elixir.config.sourcemaps = false;
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
var path = {
    public: "public/",
    root: "resources/assets/"
}
var model = {
    admin: "admin/",
    home: "home/",
    user: "user/",
    base: "base/",
    login: "login/",
    build: "build/"
}
var file = {
    css: "css/",
    less: "less/",
    js: "js/",
}

var site = {
    pkg: require('./package.json'),
    banner: [
        '/**',
        ' * <%= pkg.name %> <%= pkg.version %>',
        ' * <%= pkg.description %>',
        ' * <%= pkg.homepage %>',
        ' * Author <%= pkg.author %>',
        // ' * Licensed under <%= pkg.license %>',
        // ' * Released on: <%= date.month %> <%= date.day %>, <%= date.year %>',
        ' */',
        ''
    ].join('\n'),
    date: {
        year: new Date().getFullYear(),
        month: ('January February March April May June July August September October November December').split(' ')[new Date().getMonth()],
        day: new Date().getDate()
    }
};

// 生产版本 带版本号 
elixir(function (mix) {
    //编译less至css目录
    mix.less(model.admin + 'admin.less', path.root + file.css + model.admin + 'app.css')
        .less(model.home + 'home.less', path.root + file.css + model.home + 'app.css')
        .less(model.user + 'user.less', path.root + file.css + model.user + 'app.css')
        .less(model.login + 'login.less', path.root + file.css + model.login + 'app.css')
        //css目录里各目录css至public/css下各目录
        .stylesIn(path.root + file.css + model.admin, path.public + file.css + 'admin.css')
        .stylesIn(path.root + file.css + model.home, path.public + file.css + 'home.css')
        .stylesIn(path.root + file.css + model.user, path.public + file.css + 'user.css')
        .stylesIn(path.root + file.css + model.login, path.public + file.css + 'login.css')
        .scripts([
            (APP_ENV == 'local' ? 'module/vue-2.6.11/dist/vue.js' : 'module/vue-2.6.11/dist/vue.min.js'), //开发模式用vue.js  线上发布使用vue.min.js
            'module/vue-resource/dist/vue-resource.min.js',
            'module/vue-router/dist/vue-router.min.js',
        ], path.root + file.js + model.admin + 'main.js', path.public)
        .scripts([
            (APP_ENV == 'local' ? 'module/vue-2.6.11/dist/vue.js' : 'module/vue-2.6.11/dist/vue.min.js'),
            'module/vue-resource/dist/vue-resource.min.js',
            'module/vue-router/dist/vue-router.min.js',
        ], path.root + file.js + model.home + 'main.js', path.public)
        .scripts([
            (APP_ENV == 'local' ? 'module/vue-2.6.11/dist/vue.js' : 'module/vue-2.6.11/dist/vue.min.js'),
            'module/vue-resource/dist/vue-resource.min.js',
            'module/vue-router/dist/vue-router.min.js',
        ], path.root + file.js + model.user + 'main.js', path.public)
        .scripts([
            (APP_ENV == 'local' ? 'module/vue-2.6.11/dist/vue.js' : 'module/vue-2.6.11/dist/vue.min.js'),
            'module/vue-resource/dist/vue-resource.min.js',
            'module/vue-router/dist/vue-router.min.js',
        ], path.root + file.js + model.login + 'main.js', path.public)
        //合并js目录里各目录js文件至resources/js下
        .scriptsIn(path.root + file.js + model.admin, path.root + file.js + 'admin.js')
        .scriptsIn(path.root + file.js + model.home, path.root + file.js + 'home.js')
        .scriptsIn(path.root + file.js + model.user, path.root + file.js + 'user.js')
        .scriptsIn(path.root + file.js + model.base, path.root + file.js + 'base.js')
        .scriptsIn(path.root + file.js + model.login, path.root + file.js + 'login.js')
        //合并js下各文件类型
        .scripts(['admin.js', 'base.js'], path.public + file.js + 'admin.js')
        .scripts(['home.js', 'base.js'], path.public + file.js + 'home.js')
        .scripts(['user.js', 'base.js'], path.public + file.js + 'user.js')
        .scripts(['login.js', 'base.js'], path.public + file.js + 'login.js')
        .task('headercss')
        .task('headerjs')
        //资源版本管理    
        .version([file.css + 'admin.css', file.css + 'home.css', file.css + 'user.css', file.css + 'login.css', file.js + 'admin.js', file.js + 'home.js', file.js + 'user.js', file.js + 'login.js']);
});
//头部内容写入
gulp.task('headercss', function () {
    gulp.src(path.public + file.css + "**/*.css") //该任务针对的文件
        .pipe(header(site.banner, {
            pkg: site.pkg,
            date: site.date
        }))
        .pipe(gulp.dest(path.public + file.css)); //输出生成文件
});
//头部内容写入
gulp.task('headerjs', function () {
    gulp.src(path.public + file.js + "**/*.js") //该任务针对的文件
        .pipe(header(site.banner, {
            pkg: site.pkg,
            date: site.date
        }))
        .pipe(gulp.dest(path.public + file.js)); //输出生成文件
});
/*
gulp.task('admin-less', function () {
    elixir(function (mix) {
        //编译less至css目录
        mix.less(model.admin + 'admin.less', path.root + file.css + model.admin + 'app.css')
            //css目录里各目录css至public/css下各目录
            .stylesIn(path.root + file.css + model.admin, path.public + file.css + 'admin.css')
    });
});
gulp.task('home-less', function () {
    elixir(function (mix) {
        //编译less至css目录
       mix.less(model.home + 'home.less', path.root + file.css + model.home + 'app.css')
           //css目录里各目录css至public/css下各目录
           .stylesIn(path.root + file.css + model.home, path.public + file.css + 'home.css')
    });
});
gulp.task('user-less', function () {
   elixir(function (mix) {
        //编译less至css目录
        mix.less(model.user + 'user.less', path.root + file.css + model.user + 'app.css')
            //css目录里各目录css至public/css下各目录
            .stylesIn(path.root + file.css + model.user, path.public + file.css + 'user.css')
    });
});
gulp.task('login-less', function () {
    elixir(function (mix) {
        //编译less至css目录
        mix.less(model.login + 'login.less', path.root + file.css + model.login + 'app.css')
            //css目录里各目录css至public/css下各目录
            .stylesIn(path.root + file.css + model.login, path.public + file.css + 'login.css')
    });
});
gulp.task('base-js', function () {
    elixir(function (mix) {
        //编译less至css目录
        //合并js目录里各目录js文件至resources/js下
            mix.scriptsIn(path.root + file.js + model.base, path.root + file.js + 'base.js')
            //合并js下各文件类型
            .scripts(['admin.js', 'base.js'], path.public + file.js + 'admin.js')
            .scripts(['home.js', 'base.js'], path.public + file.js + 'home.js')
            .scripts(['user.js', 'base.js'], path.public + file.js + 'user.js')
            .scripts(['login.js', 'base.js'], path.public + file.js + 'login.js')
    });
});
gulp.task('admin-js', function () {
    elixir(function (mix) {
        //编译less至css目录
        mix.scripts([
                (APP_ENV == 'local' ? 'module/vue/dist/vue.js' : 'module/vue/dist/vue.min.js'), //开发模式用vue.js  线上发布使用vue.min.js
                'module/vue-resource/dist/vue-resource.min.js',
                'module/vue-router/dist/vue-router.min.js',
            ], path.root + file.js + model.admin + 'main.js', path.public)
            //合并js目录里各目录js文件至resources/js下
            .scriptsIn(path.root + file.js + model.admin, path.root + file.js + 'admin.js')
            //合并js下各文件类型
            .scripts(['admin.js', 'base.js'], path.public + file.js + 'admin.js')
    });
});
gulp.task('home-js', function () {
    elixir(function (mix) {
        //编译less至css目录
        mix.scripts([
                (APP_ENV == 'local' ? 'module/vue/dist/vue.js' : 'module/vue/dist/vue.min.js'),
                'module/vue-resource/dist/vue-resource.min.js',
                'module/vue-router/dist/vue-router.min.js',
            ], path.root + file.js + model.home + 'main.js', path.public)
            //合并js目录里各目录js文件至resources/js下
            .scriptsIn(path.root + file.js + model.home, path.root + file.js + 'home.js')
            //合并js下各文件类型
            .scripts(['home.js', 'base.js'], path.public + file.js + 'home.js')
    });
});
gulp.task('user-js', function () {
    elixir(function (mix) {
        //编译less至css目录
        mix.scripts([
                (APP_ENV == 'local' ? 'module/vue/dist/vue.js' : 'module/vue/dist/vue.min.js'),
                'module/vue-resource/dist/vue-resource.min.js',
                'module/vue-router/dist/vue-router.min.js',
            ], path.root + file.js + model.user + 'main.js', path.public)
            //合并js目录里各目录js文件至resources/js下
            .scriptsIn(path.root + file.js + model.user, path.root + file.js + 'user.js')
            //合并js下各文件类型
            .scripts(['user.js', 'base.js'], path.public + file.js + 'user.js')
    });
});
gulp.task('login-js', function () {
    elixir(function (mix) {
        //编译less至css目录
        mix.scripts([
                (APP_ENV == 'local' ? 'module/vue/dist/vue.js' : 'module/vue/dist/vue.min.js'),
                'module/vue-resource/dist/vue-resource.min.js',
                'module/vue-router/dist/vue-router.min.js',
            ], path.root + file.js + model.login + 'main.js', path.public)
            //合并js目录里各目录js文件至resources/js下
            .scriptsIn(path.root + file.js + model.base, path.root + file.js + 'base.js')
            //合并js下各文件类型
            .scripts(['login.js', 'base.js'], path.public + file.js + 'login.js')
    });
});
*/
// 监听版本不带版本号 位于public/css or public/js目录下
// gulp.task('watch', ['home-less'], function () {
//     // gulp.watch(path.root + file.less + model.admin + '*.less', ['admin-less']);
//     //  gulp.watch(path.root + file.less + model.home + '*.less', ['home-less']);
//     // gulp.watch(path.root + file.less + model.user + '*.less', ['user-less']);
//     // gulp.watch(path.root + file.less + model.login + '*.less', ['login-less']);
//     // gulp.watch(path.root + file.js + model.base + '*.js', ['base-js']);
//     // gulp.watch(path.root + file.js + model.admin + '*.js', ['admin-js']);
//     // gulp.watch(path.root + file.js + model.home + '*.js', ['home-js']);
//     // gulp.watch(path.root + file.js + model.user + '*.js', ['user-js']);
//     // gulp.watch(path.root + file.js + model.login + '*.js', ['login-js']);
// });

