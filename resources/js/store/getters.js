export default {
    loggedIn(state) {
        return state.token !== null;
    },
    isAdmin(state) {
        if(state.user)
            return state.user.roleDB == "admin" ? true : false;
        return false    
    }
}