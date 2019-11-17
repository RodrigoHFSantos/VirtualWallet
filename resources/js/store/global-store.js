/*jshint esversion: 6 */

import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        token: null,
        user: null,
    },
    getters: {
        loggedIn(state) {
            return state.token !== null;
        },
        isAdmin(state) {
            if(state.user)
                return state.user.roleDB == "admin" ? true : false;
            return false    
        },
    },
    mutations: {
        //* Informação sobre o USER
        clearUserAndToken: state => {
            state.user = null;
            state.token = '';
            localStorage.removeItem('user');
            localStorage.removeItem('token');
            axios.defaults.headers.common.Authorization = undefined;
        },
        clearUser: state => {
            state.user = null;
            localStorage.removeItem('user');
        },
        destroyToken: state => {
            state.token = null;
            localStorage.removeItem('token');
        },
        setUser: (state, user) => {
            state.user = user;
            localStorage.setItem('user', JSON.stringify(user));
        },
        setToken: (state, token) => {
            state.token = token;
            localStorage.setItem('token', token);
            axios.defaults.headers.common.Authorization = 'Bearer ' + token;
        },
        loadTokenAndUserFromSession: state => {
            state.token = '';
            state.user = null;
            let token = localStorage.getItem('token');
            let user = localStorage.getItem('user');
            if (token) {
                state.token = token;
                axios.defaults.headers.common.Authorization = 'Bearer ' + token;
            }
            if (user) {
                state.user = JSON.parse(user);
            }
        } 
    },
    actions: {
        destroyToken(context){
            axios.defaults.headers.common.Authorization = 'Bearer ' + context.state.token;
            if(context.getters.loggedIn){
                return new Promise((resolve, reject) => {
                    axios.post('/api/logout')
                    .then(response => {
                        localStorage.removeItem('access_token');
                        context.commit('destroyToken');
                        resolve(response);
                    })
                    .catch(error => {
                        localStorage.removeItem('access_token');
                        context.commit('destroyToken');
                        reject(error);
                    })
                })
            }
        }
    }
});
