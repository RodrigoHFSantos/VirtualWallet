<template>
	<v-app id="inspire">
    <v-content>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
              <v-toolbar color="primary" dark flat>
                <v-toolbar-title>Edit Profile</v-toolbar-title>
                <v-spacer />
              </v-toolbar>
              <v-card-text>
                <v-form id="check-login-form">
                  <v-text-field
                    id="name"
                    label="Name"
                    name="name"
                    type="name"
                    v-model="name"
                  />
                  <v-text-field
                    id="password"
                    label="New Password"
                    name="password"
                    type="password"
                  /> 
                  <!-- // -->
                   <v-text-field
                    id="nif"
                    label="NIF"
                    name="nif"
                    type="text"
                    v-model="nif"
                  />
                <br>
                <div class="form-control mb-more">
                    <!-- <label for="photo">PHOTO</label> -->
                    <div>
                      <img :src="imageUrl" height="150" weight="150">
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
                <v-btn color="primary" form="check-login-form" @click.prevent="save">Submit</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
	export default{
		name:'editprofile',
		data: function() {
		    return {
		    	id: this.$store.state.user.id,
		    	name: this.$store.state.user.name,
			    password: '',
			    //password_confirmation: '',
			    nif: this.$store.state.user.nif,
			    imageUrl: 'storage/fotos/' + this.$store.state.user.photo,
			    imageData: '',
			    image: null,
		    }
		},
		methods: {
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
    		},
    		save: function() {
    	    	axios.put('api/users/me', {
    	    		id: this.$store.state.user.id,
    	    		name: this.name,
    	    	   	password: this.password,
    	    	   	nif: this.nif
    	    	   	//photo: response.data.photo,
    	    	})
    	    	.then(response => {
    	    		console.log(response);
    	    		this.$store.state.user.name = this.name;
    	    		this.$router.push({name: 'home'})
    	    	}).catch(error =>{
    	    		console.log(error);
    	    	})
    	  	},
  		}
	};
</script>