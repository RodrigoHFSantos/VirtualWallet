<template>
  <div id="app">
    <v-app id="inspire">
      <v-navigation-drawer
        v-model="drawer"
        app
      >
        <v-list dense>
          <v-list-item :to="{ name: 'home' }">
            <v-list-item-action>
              <v-icon>mdi-home</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Home</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item v-if="loggedIn" v-on:click.prevent="logout()">
            <v-list-item-action>
              <v-icon>mdi-logout</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Logout</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item v-if="loggedIn && isUser" to="/wallet/me">
            <v-list-item-action>
              <v-icon>mdi-wallet</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Wallet</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item v-if="loggedIn && isUser" :to="{ name: 'user-statistics' }">
            <v-list-item-action>
              <v-icon>mdi-chart-bell-curve</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Statistics</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item v-if="loggedIn" :to="{ name: 'editprofile' }">
            <v-list-item-action>
              <v-icon>mdi-pencil</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Edit Profile</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item v-if="loggedIn && isOperator" :to="{ name: 'operator-movement-income' }">
            <v-list-item-action>
              <v-icon>mdi-cached</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Movement Income</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item v-if="loggedIn && isAdmin" :to="{ name: 'admin-manage-user-accounts' }">
            <v-list-item-action>
              <v-icon>mdi-account</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Manage All User Accounts</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item v-if="loggedIn && isAdmin" :to="{ name: 'admin-statistics' }">
            <v-list-item-action>
              <v-icon>mdi-chart-bell-curve</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Statistics</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item v-if="loggedIn && isAdmin" :to="{ name: 'register' }">
            <v-list-item-action>
              <v-icon>mdi-account</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Register Account</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item to="/about">
            <v-list-item-action>
              <v-icon>mdi-information</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>About</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          
        </v-list>
      </v-navigation-drawer>

      <v-app-bar app fixed dense>
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title>Virtual Wallet</v-toolbar-title>
        <v-spacer></v-spacer>
      </v-app-bar>
      <v-content fill-height fluid>
          <router-view></router-view>
      </v-content>

      <v-footer app class="footer">
        <span>&copy; 2019</span>
      </v-footer>
    </v-app>
  </div>
</template>


<script>

export default {
    data: function () {
        return {
          drawer: null
        }
    },
   computed: {
    loggedIn: function(){
      return this.$store.getters.loggedIn;
    },
    isOperator: function(){
      return this.$store.getters.isOperator;
    },
    isUser: function(){
      return this.$store.getters.isUser;
    },
    isAdmin: function(){
      return this.$store.getters.isAdmin;
    }
   },
   methods: {
     logout() {
      axios.post("api/logout").then(response => {
        this.$store.commit("clearUserAndToken");

        this.$socket.emit('user_exit',this.$store.state.user);
        
        this.$router.push({name: 'home'});
      })
      .catch(error => {
        this.$store.commit("clearUserAndToken");
      });
    }
   }

}
</script>

<style lang="scss">

  .footer{
    font-size: 15px;
  }

</style>