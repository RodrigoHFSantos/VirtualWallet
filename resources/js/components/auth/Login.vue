<template>
  <div class="login-form">
    <h2 class="login-heading">Login</h2>
    <form action="#" @submit.prevent="login">

      <div class="form-control">
        <label for="email">Username/Email</label>
        <input type="email" name="username" id="username" class="login-input" v-model="user.username">
      </div>

      <div class="form-control mb-more">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="login-input" v-model="user.password">
      </div>

      <div class="form-control">
        <button type="submit" class="btn-submit">Login</button>
      </div>

    </form>
  </div>
</template>

<script>
export default {
    name: 'login',
    data(){
        return {
          user: {
            username: '',
            password: '',
          },
            
            token: null,
        }
    },
    methods: {
        login: function() {
          this.$store.dispatch('retrieveToken', this.user)
            .then(response => {
              console.log(response);
              this.$router.push( { name: "home" } );
            })
            .catch(error => {
              console.log(error);
            })
          
        }
    }
}
</script>