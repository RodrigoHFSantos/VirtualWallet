<template>
	<div>
	    <div id="" class="flex-center"> 
	        Landing Page
	    </div>
	    <div id="" class="flex-center">
	    	Total Wallets -> {{totalWallets}}
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
		
		mounted(){
			this.getTotalWallets();
		}
	}
</script>