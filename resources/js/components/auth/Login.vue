<template>
  <v-app id="inspire">
    <v-content>
      <v-container
        class="fill-height"
        fluid
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="8"
            md="4"
          >
            <v-card class="elevation-12">
              <v-toolbar
                color="primary"
                dark
                flat
              >
                <v-toolbar-title>Login</v-toolbar-title>
                <v-spacer />
              </v-toolbar>
              <v-card-text>
                <v-form   id="check-login-form">
                  <v-text-field
                    label="Username/Email"
                    name="username"
                    type="email"
                    id="username"
                    v-model="user.username"
                  />

                  <v-text-field
                    id="password"
                    label="Password"
                    name="password"
                    type="password"
                    v-model="user.password"
                  />
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer />
                <v-btn color="primary" form="check-login-form" @click="login">Login</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
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