<template>
  <v-card class="elevation-12 card">
    <v-toolbar color="blue-grey darken-4" flat>
      <v-toolbar-title>Sign up</v-toolbar-title>
    </v-toolbar>
    <v-card-text background-color="dark">
      <v-form id="check-login-form">
        <v-text-field
          color="blue-grey darken-1"
          background-color="dark"
          label="Name"
          name="name"
          type="name"
          id="name"
          v-model="name"
         />
        <v-text-field
          color="blue-grey darken-1"
          background-color="dark"
          label="Email"
          name="email"
          type="email"
          id="email"
          v-model="email"
        />
        <v-text-field
          color="blue-grey darken-1"
          background-color="dark"
          id="password"
          label="Password"
          name="password"
          type="password"
          v-model="password"
        />
        <v-text-field
          v-if="!isLoggedIn"
          color="blue-grey darken-1"
          background-color="dark"
          id="nif"
          label="NIF"
          name="nif"
          type="nif"
          v-model="nif"
        />
        <v-select
          v-if="isLoggedIn"
          v-model="role"
          :items="roles"
          label="Choose user type"
          chips
          clearable
          hide-selected
        ></v-select>
        <br>
        <div class="form-control mb-more">
          <label for="photo">PHOTO</label>
          <div>
            <img :src="imageUrl" height="150" >
          </div>
          <div>
            <v-btn small class="mx-1" fab dark color="indigo" @click="onPickFile">
              <v-icon dark>mdi-plus</v-icon>
            </v-btn>
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
    <v-card-actions class="justify-center">
      <v-btn block  color="blue-grey darken-1" form="check-login-form" @click="register">Sign up</v-btn>
    </v-card-actions>
  </v-card>
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
        imageData: '',
        imageUrl: '',
        image: null,
        role: '',
        roles: ['Administrator', 'Operator'],
        isLoggedIn: '',
    }
  },
  methods: {
      register: function() {
        this.isAuth();
        if(this.role == 'Administrator'){
          this.role = 'a';
        }
        if(this.role == 'Operator'){
          this.role = 'o';
        }
         axios.post('api/register', {
           name: this.name,
           email: this.email,
           password: this.password,
           nif: this.nif,
           photo: this.photo,
           role: this.role,
         })
         .then(response => {
            if(this.role == 'u'){
              axios.post('api/wallets/create', {
              email: this.email,
            })
            .then(response => {
            })
            .catch(error => {
              console.log(error);
            })
           }
            if(this.isLoggedIn == false){
              this.$router.push({name: 'login'});
            }else{
              this.$router.push({name: 'admin-statistics'});
            }
            this.clearInputs();
         })
          .catch(error => {
            console.log(error);
          })
      },
      onPickFile(){
      this.$refs.fileInput.click();
      },
      onFilePicked (event) {
        let photo = event.target.files[0];
        var fileReader = new FileReader();
        fileReader.readAsDataURL(event.target.files[0])
        fileReader.onload = (event) =>{
          this.photo = event.target.result;
          this.imageUrl = event.target.result;
        }
      },
      isAuth(){
        if(this.$store.state.token == ''){
          this.role = 'u';
          this.isLoggedIn = false;
        }else{
          this.isLoggedIn = true;
          console.log(this.role);
        }
      },
      clearInputs() {
        this.name = '';
        this.email = '';
        this.password = '';
        this.nif = '';
        this.role = '';
      }
  },
  mounted() {
    this.isAuth();
  }
};
</script>

<style lang="scss" scoped>

  .card{
		position: absolute;
		top: 50%;
		left: 50%;
		margin-right: -50%;
		transform: translate(-50%, -50%);
    width: 500px;
  }

  

</style>

