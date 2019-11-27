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
                    label="Name"
                    name="name"
                    type="name"
                    id="name"
                    v-model="name"
                  />

                  <v-text-field
                    label="Email"
                    name="email"
                    type="email"
                    id="email"
                    v-model="email"
                  />

                  <v-text-field
                    id="password"
                    label="Password"
                    name="password"
                    type="password"
                    v-model="password"
                  />
                   <v-text-field
                    id="nif"
                    label="NIF"
                    name="nif"
                    type="nif"
                    v-model="nif"
                  />
                <br>
                <div class="form-control mb-more">
                    <label for="photo">PHOTO</label>
                    <div>
                      <img :src="imageUrl" height="150">
                    </div>
                    <div>
                      <v-btn small color="error" @click="onPickFile">Upload Image</v-btn>
                      <input 
                        type="file"
                        style="display: none"
                        ref="fileInput"
                        accept="image/*"
                        @change="onFilePicked"
                        >  
                    </div>
                </div>
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


export default {
    name: 'register',
  data: function() {
    return {
        name: '',
        email: '',
        password: '',
        nif: '',
        // photo: '',
        imageData: '',
        imageUrl: '',
        image: null,
    }
  },
  methods: {
      register: function() {
        //console.log(this.photo.imageUrl);
         axios.post('api/register', {
           name: this.name,
           email: this.email,
           password: this.password,
           nif: this.nif,
           photo: this.photo,
         })
         .then(response => {
           axios.post('api/wallets/create', {
             email: this.email,
           })
           .then(response => {
              this.$router.push({name: 'login'})
           })
           .catch(error => {
             console.log(error);
           })
          .catch(error => {
            console.log(error);
          })
         })
      },
      onPickFile(){
        this.$refs.fileInput.click();
      },
      onFilePicked (event) {
        const files = event.target.files
        let filename = files[0].name
        if(filename.lastIndexOf('.') <= 0){
          return alert("Please add a valid file!!")
        }
        const fileReader = new FileReader()
        fileReader.addEventListener('load', () => {
        this.imageUrl = fileReader.result
        })
      fileReader.readAsDataURL(files[0])
      this.photo = filename
    }
  }
};
</script>

