export default {
    // retrieveTotalWallet: (state, totalWallets) => {
    //     state.totalWallets = totalWallets;
    // },
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
        state.token = '';
        // localStorage.removeItem('token');
    },
    setUser: (state, user) => {
        state.user = user;
        localStorage.setItem('user', JSON.stringify(user));
    },
    setToken: (state, token) => {
        state.token = token;
    },
    loadTokenAndUserFromSession: state => {
        state.token = '';
        state.user = null;
        let token = localStorage.getItem('access_token');
        let user = localStorage.getItem('user');
        if (token) {
            state.token = token;
            axios.defaults.headers.common.Authorization = 'Bearer ' + token;
        }
        if (user) {
            state.user = JSON.parse(user);
        }
    } 
}