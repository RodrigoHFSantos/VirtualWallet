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
import VueSocketIO from "vue-socket.io";
import Toasted from 'vue-toasted';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import * as VeeValidate from 'vee-validate'

Vue.use(Toast, {
  transition: "Vue-Toastification__bounce",
  maxToasts: 20,
  newestOnTop: true
});

Vue.directive('VeeValidate', VeeValidate)
Vue.use(VeeValidate, {
  inject: true
})

Vue.use(
    VCalendar, {componentPrefix: 'vc'},
);

Vue.use(VueRouter);
Vue.use(Vuelidate);
Vue.use(Toasted);

const router = new VueRouter({
    routes,
    // history = true //era suposto tirar o # do url mas nao funciona
})


// Vue.use(new VueSocketIO({
//   debug: true,
//   connection: 'http://10.200.0.203'
// })); 
 


router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    store.commit('loadTokenAndUserFromSession');
    if (!store.getters.loggedIn) {
      next({
        name: 'login',
      })
    } else {
      next()
    }
  } else {
    next()
  }
  if (to.matched.some(record => record.meta.requiresVisitor)) {
    if (store.getters.loggedIn && store.getters.isUser) {
      next({
        name: 'wallet',
      })
    } else {
      next()
    }
    if (store.getters.loggedIn && store.getters.isOperator) {
      next({
        name: 'operator-movement-income',
      })
    } else {
      next()
    }
    if (store.getters.loggedIn && store.getters.isAdmin) {
      next()
    } else {
      next()
    }
  } else {
    next()
  }
  if (to.matched.some(record => record.meta.requiresUser)) {
    if (store.getters.loggedIn && store.getters.isOperator) {
      next({
        name: 'operator-movement-income',
      })
    } else {
      next()
    }
    if (store.getters.loggedIn && store.getters.isAdmin) {
      next({
        name: 'admin-statistics',
      })
    } else {
      next()
    }
  } else {
    next()
  }
  if (to.matched.some(record => record.meta.requiresOperator)) {
    if (store.getters.loggedIn && store.getters.isUser) {
      next({
        name: 'wallet',
      })
    } else {
      next()
    }
    if (store.getters.loggedIn && store.getters.isAdmin) {
      next({
        name: 'admin-statistics',
      })
    } else {
      next()
    }
  } else {
    next()
  }
  if (to.matched.some(record => record.meta.requiresAdmin)) {
    if (store.getters.loggedIn && store.getters.isUser) {
      next({
        name: 'wallet',
      })
    } else {
      next()
    }
    if (store.getters.loggedIn && store.getters.isOperator) {
      next({
        name: 'operator-movement-income',
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
