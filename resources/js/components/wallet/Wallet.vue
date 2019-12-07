
<template>
  <v-card
    class="mx-auto"
    max-width="344"
    outlined
  >
    <v-list-item three-line>
      <v-list-item-content>
        <div class="overline mb-4">Wallet</div>
        <v-list-item-title class="headline mb-1">{{name}}</v-list-item-title>
        <v-list-item-subtitle>{{email}}</v-list-item-subtitle>
      </v-list-item-content>

    </v-list-item>

    <v-card-actions>
      <v-btn to="/wallet/movements/me">Movements</v-btn>
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