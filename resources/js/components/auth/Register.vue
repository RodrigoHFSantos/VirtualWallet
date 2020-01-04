<template>
  <v-card class="elevation-12 card">
    <v-toolbar color="blue-grey darken-4" flat>
      <v-toolbar-title>Sign up</v-toolbar-title>
    </v-toolbar>
    <v-card-text background-color="dark">
      <v-form id="check-register-form">
        <v-text-field
          color="blue-grey darken-1"
          background-color="dark"
          label="Name"
          name="name"
          type="name"
          id="name"
          v-model="name"
          :rules="[v => !!v || 'Name is required', $v.name.alpha || 'Name can only contain alphabet characters']"
         />
        <v-text-field
          color="blue-grey darken-1"
          background-color="dark"
          label="Email"
          name="email"
          type="email"
          id="email"
          v-model="$v.email.$model"
          :rules="[$v.email.required || 'Email is required']"
        />
        <v-text-field
          color="blue-grey darken-1"
          background-color="dark"
          id="password"
          label="Password"
          name="password"
          v-model="password"
          :type="show1 ? 'text' : 'password'"
          :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
          @click:append="show1 = !show1"
          v-model.trim="$v.password.$model"
          :rules="[$v.password.required || 'Password is required', $v.password.minLength || 'Password must be at least 3 characters!']"
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
          :rules="[$v.nif.required|| 'Nif required!', $v.nif.numeric|| 'Nif can only have digits!', $v.nif.minLength || 'Nif must have exactly 9 digits!', $v.nif.maxLength || 'Nif must have exactly 9 digits!']"
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
      <v-btn block  color="blue-grey darken-1" form="check-register-form" @click="register">Sign up</v-btn>
    </v-card-actions>
  </v-card>
</template> 

<script>
import { required, sameAs, minLength, maxLength, numeric, helpers,email } from 'vuelidate/lib/validators';

const alpha = helpers.regex('alpha', /^[a-zA-Z ]*$/);

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
        show1: false,
        show2: false,
        valid: false,
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
            
              if(error.response.data.errors.email){
                this.$toast.error(error.response.data.errors.email[0], {
                  position: "top-right ",
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
              }
              if(error.response.data.errors.nif){
                this.$toast.error("The nif has already been taken.", {
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
              }
            
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
  },
  mounted() {
    this.isAuth();
  },
  validations: {
    password: {
      required,
      minLength: minLength(3),
    },
    nif:{
      required,
      numeric,
      minLength: minLength(9),
      maxLength: maxLength(9),
    },
    name:{
      alpha
    },
    email:{
      required,
      email,
    }
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

