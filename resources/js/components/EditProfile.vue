<template>
  <v-card class="elevation-12 card">
    <v-toolbar color="blue-grey darken-4" flat>
      <v-toolbar-title>Edit Profile</v-toolbar-title>
    </v-toolbar>
    <v-card-text background-color="dark">
      <v-form id="check-login-form">
        <v-text-field
          color="blue-grey darken-1"
          background-color="dark"
          id="name"
          label="Name"
          name="name"
          type="name"
          v-model="name"
        />
        <v-text-field
          color="blue-grey darken-1"
          background-color="dark"
          id="password"
          label="New Password"
          name="password"
          type="password"
        /> 
        <v-text-field
          color="blue-grey darken-1"
          background-color="dark"
          id="nif"
          label="NIF"
          name="nif"
          type="text"
          v-model="nif"
        />
        <br>
        <div class="form-control mb-more">
          <div>
            <img :src="imageUrl" height="150" width="150">
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
      <v-btn block  color="blue-grey darken-1" form="check-login-form" @click.prevent="save">Submit</v-btn>
    </v-card-actions>
  </v-card>
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