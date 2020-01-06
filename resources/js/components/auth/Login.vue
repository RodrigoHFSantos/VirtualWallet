<template>
  <v-card class="card">
    <v-toolbar color="blue-grey darken-4" flat>
      <v-toolbar-title>Sign in</v-toolbar-title>
        </v-toolbar>
          <v-card-text background-color="dark">
            <v-form id="check-login-form">
              <v-text-field
                class="shadow"
                color="blue-grey darken-1"
                background-color="dark"
                label="Email"
                name="username"
                type="email"
                id="username"
                v-model="user.email"
              />
              <v-text-field
                color="blue-grey darken-1"
                id="password"
                label="Password"
                name="password"
                type="password"
                v-model="user.password"
              />
            </v-form>
          </v-card-text>
        <v-card-actions class="justify-center">
          <v-btn  block  color="blue-grey darken-1" form="check-login-form" align-end @click="login">Sign in</v-btn>
        </v-card-actions>
  </v-card>
</template>

<script>
export default {
   data() {
      return {
         user: {
            email: "",
            password: ""
         }
      }
   },
   methods: {
      login: function() {
         axios.post('api/login', this.user).then(response => {
            this.$store.commit("setToken", response.data.access_token);
            return axios.get("api/users/me");
         })
         .then(response => {
            this.$store.commit("setUser", response.data.data);

            this.$socket.emit('user_enter',response.data.data);
            
            if(this.$store.getters.isUser){
              this.$router.push({ name: "wallet" });
            }
            if(this.$store.getters.isOperator){
              this.$router.push({ name: "operator-movement-income" });
            }
            if(this.$store.getters.isAdmin){
              this.$router.push({ name: "admin-statistics" });
            }
         })
         .catch(error => {
                this.$toast.error("Login Failed!", {
                  position: "top-right",
                  timeout: 5000,
                  closeOnClick: true,
                  pauseOnFocusLoss: true,
                  pauseOnHover: true,
                  draggable: true,
                  draggablePercent: 0.6,
                  hideCloseButton: false,
                  hideProgressBar: false,
                  icon: true
                });
          })   
      }
   }
}
</script>

<style scoped>

  .card{
		position: absolute;
		top: 50%;
		left: 50%;
		margin-right: -50%;
		transform: translate(-50%, -50%);
    width: 500px;
  }

    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus,
    textarea:-webkit-autofill,
    textarea:-webkit-autofill:hover,
    textarea:-webkit-autofill:focus,
    select:-webkit-autofill,
    select:-webkit-autofill:hover,
    select:-webkit-autofill:focus {
      border: 1px solid green !important;
      -webkit-text-fill-color: green !important;
      -webkit-box-shadow: 0 0 0px 1000px #000 inset !important;
      transition: background-color 5000s ease-in-out 0s !important;
    }
  

</style>