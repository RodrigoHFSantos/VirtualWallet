window.Vue = require('vue');

import MasterComponent from './components/layouts/Master.vue';
const master = Vue.component('master', MasterComponent);

import LoginComponent from './components/auth/Login.vue';
const login = Vue.component('login', LoginComponent);

import RegisterComponent from './components/auth/Register.vue';
const register = Vue.component('register', RegisterComponent);


import LandingPageComponent from './components/marketing/LandingPage.vue';
const landingpage = Vue.component('landingpage', LandingPageComponent);

import AboutComponent from './components/marketing/About.vue';
const about = Vue.component('about', AboutComponent);



const routes = [
    { 
        path: '/',
        name: 'home',
        component: landingpage
    },

    {
        path: '/login',
        name: 'login',
        component: login,
        meta: {
            requiresVisitor: true,
        }
    },

    {
        path: '/register',
        name: 'register',
        component: register,
        meta: {
            requiresVisitor: true,
        }
    },

    // {
    //     path: '/logout',
    //     name: 'logout',
    //     component: logout
    // },

    {
        path: '/about',
        name: 'about',
        component: about
    },
]

export default routes