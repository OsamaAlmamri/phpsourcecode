/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import moreArticles from './components/articles/more-articles';
import topArticles from './components/home/top-articles';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('moreArticles', moreArticles);
Vue.component('topArticles', topArticles);

// noinspection ES6ModulesDependencies
new Vue({
    el: '#gryenApp'
});
