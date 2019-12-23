window.Vue = require('vue');

import MasterComponent from './components/Master.vue';
const master = Vue.component('master', MasterComponent);

import LoginComponent from './components/auth/Login.vue';
const login = Vue.component('login', LoginComponent);

import RegisterComponent from './components/auth/Register.vue';
const register = Vue.component('register', RegisterComponent);

import LandingPageComponent from './components/marketing/LandingPage.vue';
const landingpage = Vue.component('landingpage', LandingPageComponent);

import AboutComponent from './components/marketing/About.vue';
const about = Vue.component('about', AboutComponent);

import EditProfileComponent from './components/EditProfile.vue';
const editprofile = Vue.component('editprofile', EditProfileComponent);

import OperatorIncomeMovementComponent from './components/movements/operator/RegisterIncome.vue';
const operatorMovementIncome = Vue.component('operatorIncomeMovement', OperatorIncomeMovementComponent);

import MovementsListComponent from './components/movements/auth_user/MovementsList.vue';
const movementsList = Vue.component('movementsList', MovementsListComponent);
import UserFilterMovementComponent from './components/movements/user/UserFilterMovement.vue';
const userFilterMovements = Vue.component('userFilterMovements', UserFilterMovementComponent);

import WalletComponent from './components/wallet/Wallet.vue';
const wallet = Vue.component('wallet', WalletComponent);

import UserRegisterExpenseComponent from './components/movements/user/UserExpenseMovement.vue';
const userRegisterExpense = Vue.component('userRegisterExpense', UserRegisterExpenseComponent);

const routes = [
    { 
        path: '/',
        name: 'home',
        component: landingpage,
        meta: {
            requiresVisitor: true,
        }
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

    {
        path: '/about',
        name: 'about',
        component: about
    },

    {
        path: '/users/me',
        name: 'editprofile',
        component: editprofile,
        meta: {
            requiresAuth: true,
        }
    },

    {
        path: '/operator/movements/register-income',
        name: 'operator-movement-income',
        component: operatorMovementIncome,
        meta: {
            requiresOperator: true,
        }
    },

    {
        path: '/wallet/movements/me',
        name: 'movementsList',
        component: movementsList,
    },
    {
        path: '/wallet/me',
        name: 'wallet',
        component: wallet,
        meta: {
            requiresAuth: true,
            requiresUser: true,
        }
    },

    {
        path: '/user/movements/register-expense',
        name: 'user-movements-register-expense',
        component: userRegisterExpense,
        meta: {
            requiresUser: true,
        }
    }
]

export default routes