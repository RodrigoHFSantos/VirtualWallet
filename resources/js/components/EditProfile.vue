<template>
  <v-card class="elevation-12 card">
    <v-toolbar color="blue-grey darken-4" flat>
      <v-toolbar-title>Edit Profile</v-toolbar-title>
    </v-toolbar>
    <v-card-text background-color="dark">
      <v-form id="formEdit" enctype='multipart/form-data' v-model="valid" lazy-validation>
        <v-text-field
          color="blue-grey darken-1"
          background-color="dark"
          id="name"
          label="Name"
          name="name"
          type="name"
          v-model="name"
          :rules="[v => !!v || 'Name is required', $v.name.alpha || 'Name can only contain alphabet characters']"
        />
         <v-text-field
            color="blue-grey darken-1"
            background-color="dark"
            id="password"
            label="New Password"
            name="password"
            ref="password"
            :type="show1 ? 'text' : 'password'"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="show1 = !show1"
            v-model.trim="$v.password.$model"
            :rules="[$v.password.required || 'Password is required']"
          /> 
          <v-text-field
            color="blue-grey darken-1"
            background-color="dark"
            id="confPassword"
            label="Confirm Password"
            name="confPassword"
            type="password"
            :type="show2 ? 'text' : 'password'"
            :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="show2 = !show2"
            v-model.trim="$v.confpassword.$model"
            :rules="[$v.confpassword.required || 'Passwords must be identical', $v.confpassword.sameAsPassword || 'Passwords must be identical']"
          />  
        <v-text-field v-if="userRole === 'u'"
          color="blue-grey darken-1"
          background-color="dark"
          id="nif"
          label="NIF"
          name="nif"
          type="text"
          v-model.trim="$v.nif.$model"
          :rules="[$v.nif.required|| 'Nif required!', $v.nif.numeric|| 'Nif can only have digits!', $v.nif.minLength || 'Nif must have exactly 9 digits!', $v.nif.maxLength || 'Nif must have exactly 9 digits!']"
        />
        <br>
        <div class="form-control mb-more">
          <div>
            <img :src="imageUrl" height="150" weight="150">
          </div>
          <div>
            <v-btn small class="mx-1" fab dark color="indigo" @click="onPickFile">	
              <v-icon>mdi-plus</v-icon>	
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
      <v-spacer />
      <v-btn block  color="blue-grey darken-1" form="formEdit" @click.prevent="save" :disabled="!valid">Submit</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { required, sameAs, minLength, maxLength, numeric, helpers } from 'vuelidate/lib/validators';
const alpha = helpers.regex('alpha', /^[a-zA-Z ]*$/);
    export default{
        name:'editprofile',
        data: function() {
            return {
                show1: false,
                show2: false,
                valid: false,
                id: this.$store.state.user.id,
                name: this.$store.state.user.name,
                password: '',
                confpassword: '',
                nif: this.$store.state.user.nif,
                imageUrl: 'storage/fotos/' + this.$store.state.user.photo,
                photo: '',
                userRole: '',
            }
        },
        methods: {
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
 
 
            save: function() {
                axios.put('api/users/me',{
                    id: this.id,
                    name: this.name,
                    password: this.password,
                    nif: this.nif,
                    photo: this.photo
                })
                .then(response => {
                    this.$store.commit("setUser", response.data);
                    this.$router.push({name: 'home'});
                }).catch(error =>{
                    console.log(error);
                })
            }
        },
        mounted(){
          this.userRole = this.$store.state.user.type;
          if(this.$store.state.token == ''){
				    this.$router.push({name: 'login'})
			    }
        },
        validations: {
            password: {
              required,
            },
            confpassword: {
                required,
                sameAsPassword: sameAs('password')
            },
            nif:{
                required,
                numeric,
                minLength: minLength(9),
                maxLength: maxLength(9),
            },
            name:{
                alpha
            }
        }
    }
 
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