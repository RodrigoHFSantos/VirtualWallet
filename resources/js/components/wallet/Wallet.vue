
<template>
  <v-card
    class="mx-auto card"
	height="500px"
  >
    <v-img
      class="white--text"
      height="140px"
      src="https://media.threatpost.com/wp-content/uploads/sites/103/2018/12/31080353/Crypto-Wallet.jpg"
    >
      
    </v-img>
	<v-card-title class="cardTitle justify-center">Wallet Details</v-card-title>

    <v-card-text class="text--primary">
		<div>Username: {{name}}</div>
		<div>Email: {{email}}</div>
		<div>{{currentBalance}}€</div>
    </v-card-text>
    <v-card-actions class="justify-center flexcard">
      <v-btn block  color="blue-grey darken-1" to="/wallet/movements/me">Movements</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
	export default{
		data: function(){
			return{
				currentBalance: null,
				email: this.$store.state.user.email,
				name: this.$store.state.user.name,
			}
		},

		methods:{
			getCurrentBalance:function () {
				axios.get('api/wallet/me')
					.then(response=>{
						this.currentBalance = response.data;
					})
			},
		},
		
		mounted(){
			this.getCurrentBalance();
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
		// height: 500px;
		width: 500px;
	}

	.cardTitle{
		font-size: 30px;
	}

	.flexcard {
		position: absolute;
		bottom: 0;
		width: 100%;
	}
	</style>