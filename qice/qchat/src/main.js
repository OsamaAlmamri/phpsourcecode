// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import iView from 'iview';
import 'iview/dist/styles/iview.css';
import App from './App'
import router from './router'


Vue.config.productionTip = false

Vue.use(iView, {
  transfer: true,
  size: 'large',
  select: {
      arrow: 'md-arrow-dropdown',
      arrowSize: 20
  }
});

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>',

  // computed: {
  //   abc: function(){
  //     return require ('./components/Login.vue');
  //   }
  // },

  // render (h) {
  //   return h(this.abc)
  // }
})