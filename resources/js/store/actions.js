export default {
    // retrieveTotalWallets(context){
    //     axios.get('api/wallets/total')
	// 				.then(response=>{
    //                     // context.commit('retrieveTotalWallet', response.data)
	// 				})
    // },
    retrieveToken(context, credentials){
        axios.defaults.headers.common.Authorization = 'Bearer ' + context.state.token;
        return new Promise((resolve, reject) => {
            axios.post('api/login',  {
                // porque é que aqui não consigo enviar o objeto this.user como fazia com o pedido axios no componente login
                username: credentials.username,
                password: credentials.password,
              })
            .then(response => {
                console.log(response.data.access_token);
                const token = response.data.access_token;
                localStorage.setItem('access_token', token);
                context.commit('setToken', token);
                resolve(response);
            })
            .catch(error => {
                console.log(error);
                reject(error);
            });
        })
    },
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