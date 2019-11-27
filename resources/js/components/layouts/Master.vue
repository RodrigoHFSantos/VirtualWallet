<template>
  <v-app>
    <v-card>
      <v-toolbar dense color="deep-orange darken-3">

        <v-toolbar-title class="white--text">
          <v-icon color="white">mdi-wallet</v-icon> Virtual Wallet  
        </v-toolbar-title>

        <v-spacer></v-spacer>

        <v-btn icon color="white" :to="{ name: 'home' }">
          <v-icon>mdi-home</v-icon>
        </v-btn>

        <v-btn icon color="white" to="/about">
          <v-icon>mdi-information</v-icon>
        </v-btn>

        <v-menu
          left
          bottom
        >
          <template v-slot:activator="{ on }">
            <v-btn icon v-on="on" color="white">
              <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
          </template>

          <v-list>
            <v-list-item v-if="!loggedIn" to="/login">Login</v-list-item>
            <v-list-item v-if="!loggedIn" to="/register">Register</v-list-item>
            <v-list-item v-if="loggedIn" v-on:click.prevent="logout()">Logout</v-list-item>
          </v-list>
        </v-menu>
      </v-toolbar>      
    </v-card>

    <router-view></router-view>
  </v-app>
</template>

<script>
// import NavbarComponent from './Navbar.vue';

export default {
   computed: {
     loggedIn: function(){
       return this.$store.getters.loggedIn;
     }
   },
   methods: {
     logout() {
      axios.post("api/logout").then(response => {
        this.$store.commit("clearUserAndToken");
        this.$router.push({name: "login"});
      })
      .catch(error => {
        this.$store.commit("clearUserAndToken");
      });
    }
   }

}
</script>

<style lang="scss">
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }
  #app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    font-size: 24px;
    height: 100vh;
    background-color: #FFE0B2;
  }
  .flex-center {
    display: flex;
    justify-content: center;
  }

  // Auth Pages
  label {
    display: block;
    margin-bottom: 4px;
  }
  .login-heading {
    margin-bottom: 16px;
  }
  .form-control {
    margin-bottom: 24px;
  }
  .mb-more {
    margin-bottom: 42px;
  }
  .login-form {
    max-width: 500px;
    margin: auto;
  }
  .login-input {
    width: 100%;
    font-size: 16px;
    padding: 12px 16px;
    outline: 0;
    border-radius: 3px;
    border: 1px solid lightgrey;
  }
  .btn-submit {
    width: 100%;
    padding: 14px 12px;
    font-size: 18px;
    font-weight: bold;
    background: #60BD4F;
    color: white;
    border-radius: 3px;
    cursor: pointer;
    &:hover {
      background: darken(#60BD4F, 10%);
    }
  }



  
</style>