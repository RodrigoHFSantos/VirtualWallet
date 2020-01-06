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
import * as VeeValidate from 'vee-validate';
import vueCharts from "vue-chartjs";
import VueSocketio from 'vue-socket.io';

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
Vue.use(new VueSocketio({
  debug: true,
  // connection: 'http://192.168.10.10:8080'
  connection: 'http://127.0.0.1:8080'
}));

const router = new VueRouter({
    routes,
    // history = true //era suposto tirar o # do url mas nao funciona
})

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
    vueCharts,
    sockets:{
      privateMessage(dataFromServer) {
        let name =
            dataFromServer[1] === null ? "Unknown" : dataFromServer[1].name;
        this.$toasted.show(
            'Message "' + dataFromServer[0] + '" sent from "' + name + '"'
        );
    },
    privateMessage_unavailable(destUser) {
        this.$toasted.error(
            'User "' + destUser.name + '" is not available'
        );
    },
    privateMessage_sent(dataFromServer) {
        this.$toasted.success(
            'Message "' +
                dataFromServer[0] +
                '" was sent to "' +
                dataFromServer[1].name +
                '"'
        );
    },
      income_movement_made(){
        this.$toasted.show("You have received an Income Movement");
      },
      transfer_movement_made(){
        this.$toasted.show("A Transfer Movement was made to your wallet");
      },
      // notify_by_email(email){
      //   if(email){
      //     console.log("enviar email para "+email)
      //     let auxType = "";
      //     if(this.$store.state.user.type == 'u'){
      //       auxType = "Income";
      //     }else{
      //       auxType = "Transfer";
      //     }
      //     axios.post('/api/sendEmail', {to: email, typeEmail: auxType})
      //     .then(response => {          
      //       this.$toasted.success(response.data.success);
      //     })
      //     .catch(response => {
      //         this.$toasted.error("Unable to notify "+email);
      //     });
      //   }
      // },
    },
    created() {
      this.$store.commit('loadTokenAndUserFromSession');
    }
}).$mount('#app');
