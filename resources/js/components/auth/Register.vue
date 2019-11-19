<template>
  <v-app id="inspire">
    <v-content>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
              <v-toolbar color="primary" dark flat>
                <v-toolbar-title>Register</v-toolbar-title>
                <v-spacer />
              </v-toolbar>
              <v-card-text>
                <v-form id="check-login-form">
                  <v-text-field
                    label="Email"
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
                   <v-text-field
                    id="nif"
                    label="NIF"
                    name="nif"
                    type="nif"
                    v-model="user.nif"
                  />
                <br>
                  <p><font size="4">Photo (Optional)</font></p>
                   <v-image-input
                    v-model="user.photo"
                    :image-quality="0.85"
                    clearable
                    image-format="jpeg"
                />
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer />
                <v-btn color="primary" form="check-login-form" @click="register">Register</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
import VImageInput from 'vuetify-image-input';

export default {
    name: "register",
    components: {
        VImageInput,
  },
  data: function() {
    return {
      user: {
        username: "",
        password: "",
        nif: "",
        photo: ""
      },
    };
  },
  methods: {
      register: function() {
          console.log(user.photo);
      }
  }
};
</script>