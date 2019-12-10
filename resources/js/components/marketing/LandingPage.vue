<template>
	<div class="container">
		<div class="container2">
			<h1>Wallets for everyone</h1>
			<p>There are currently {{totalWallets}} virtual wallets, sign up and get yours too</p>
			<v-btn class="primary" v-if="!loggedIn" to="/login">Sign in</v-btn>
			<v-btn class="primary" v-if="!loggedIn" to="/register">Sign up</v-btn>
		</div>  
    </div>
</template>

<script>
	export default{
		data: function(){
			return{
				totalWallets: null
			}
		},
		methods:{
			getTotalWallets:function () {
				axios.get('api/wallets/total')
					.then(response=>{
						this.totalWallets = response.data;
					})
			},
		},
		computed: {
     		loggedIn: function(){
       			return this.$store.getters.loggedIn;
     		},
		},
		mounted(){
			this.getTotalWallets();
		}
	}
</script>

<style scoped>

	.container2{
		margin: 0;
		position: absolute;
		top: 50%;
		left: 50%;
		margin-right: -50%;
		transform: translate(-50%, -50%);
		text-align: center;
	 }

	.container2 h1{
		font-size: 70px;
		color: white;
	}

	.container2 p{
		font-size: 20px;
		color: white;
	}
	
</style>