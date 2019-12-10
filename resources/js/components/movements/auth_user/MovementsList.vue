<template>
<div>
	<div class="title">
		<h1>Movements</h1>
	 </div>		

																				 
	<v-simple-table>
		<template v-slot:default>
		<thead>
			<tr class="text-left">
				<th>ID</th> 
				<th>Type</th>
				<th>Transfer Email</th> 
				<th>Type of Payment</th>
				<th>Category</th> 
				<th>Date</th>
				<th>Start Balance</th> 
				<th>End Balance</th>
				<th>Value</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="movement in movements" :key="movement.id"  @click="add()">
				<td>{{movement.id}}</td>
				<td v-if="movement.type === 'e'">
					expence
				</td>
				<td v-else-if="movement.type === 'i'">
  					income
				</td>
				<td v-if="movement.transfer_wallet === null">
					------
				</td>
				<td v-else>
  					{{movement.transfer_wallet}}
				</td>
				<td v-if="movement.type_payment === 'c'">
					cash
				</td>
				<td v-else-if="movement.type_payment === 'bt'">
  					bank transfer
				</td>
				<td v-else-if="movement.type_payment === 'mb'">
  					MB payment
				</td>
				<td v-else-if="movement.type_payment === null">
  					------
				</td>
				<td>{{movement.category_name}}</td>
				<td>{{movement.date}}</td>
				<td>{{movement.start_balance}}</td>
				<td>{{movement.end_balance}}</td>
				<td>{{movement.value}}</td>
			</tr>
		</tbody>
		</template>
	</v-simple-table>
</div>
</template>

<script>	
	export default{
		data: function(){
			return{
				movements: [],
			}
		},

		methods:{
			getMovements:function () {
				axios.get('api/wallet/movements/me')
				.then(response=>{
					console.log(response.data);
					this.movements = response.data;
				})
			},
			add:function() {
				
			}
		},
		
		mounted(){
			this.getMovements();
		}
	}
</script>

<style scoped>
	.title{
		display: flex;
		align-items: center;
		justify-content: center;
	}
</style>

