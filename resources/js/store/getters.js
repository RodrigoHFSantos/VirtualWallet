export default {
    loggedIn(state) {
        return state.token !== '';
    },
    isAdmin(state) {
        if(state.user)
            return state.user.roleDB == "admin" ? true : false;
        return false    
    }
}