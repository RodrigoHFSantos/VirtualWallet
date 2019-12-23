/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');

import vuetify from '../../plugins/vuetify'; // path to vuetify export
import VueRouter from 'vue-router';
import routes from './routes';
import store from './store/global-store';
import Vuelidate from 'vuelidate';
import VCalendar from 'v-calendar';

Vue.use(
    VCalendar, {componentPrefix: 'vc'},
);

Vue.use(VueRouter);
Vue.use(Vuelidate);

const router = new VueRouter({
    routes,
    // history = true //era suposto tirar o # do url mas nao funciona
})


router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
      if (!store.getters.loggedIn) {
        next({
          name: 'login',
        })
      } else {
        next()
      }
    } else if (to.matched.some(record => record.meta.requiresVisitor)) {
      // console.log(store.getters.isUser);
      // console.log(router.currentRoute.name);
      if (store.getters.loggedIn && store.getters.isUser) {
        console.log("require visitor, estou logado e sou user");
        next({
          name: 'wallet',
        })
      } else {
        next()
      }
      if (store.getters.loggedIn && store.getters.isOperator) {
        next({
          name: 'about',
        })
      } else {
        next()
      }
      if (store.getters.loggedIn && store.getters.isAmin) {
        // console.log("yah");
        next({
          name: 'about',
        })
      } else {
        next()
      }

    } else if (to.matched.some(record => record.meta.requiresOperator)) {
      if (!store.getters.isOperator) {
        next({
          name: 'home',
        })
      } else {
        next()
      } 
    } else if (to.matched.some(record => record.meta.requiresUser)) {
      if (!store.getters.isUser) {
        next({
          name: 'home',
        })
      } else {
        next()
      } 
    } else {
      next()
    }
  })

const app = new Vue({
    router,
    store,
    vuetify,
    created() {
      this.$store.commit('loadTokenAndUserFromSession');
    }
}).$mount('#app');
