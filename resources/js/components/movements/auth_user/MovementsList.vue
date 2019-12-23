<template>
  <div>
    <div class="title pt-6 pb-6">
      <h1>Movements</h1>
    </div>
    <div class="row ml-8">
      <UserFilterMovements
        :categories_names="categories_names"
        :activator="activator"
        @clicked="movementsFiltered"
      />
      <UserExpenseMovement :activator="activator" @registed="movementRegisted" />
    </div>
    <v-flex v-if="isMovementsFiltered">
      <v-btn color="red" dark @click="clearMovementsFiltered">Clear Filter</v-btn>
    </v-flex>
    <v-snackbar v-model="noFilterAlert.value">
      {{ noFilterAlert.text }}
      <v-btn color="red" text @click="noFilterAlert.value = false">Close</v-btn>
    </v-snackbar>
    <v-data-table
      :headers="headers"
      :items="movements"
      :items-per-page="10"
      class="elevation-1 ml-8 mt-4 mr-8 mb-8"
    >
      <template #item.type="{item}">
        <div v-if="item.type === 'e'">Expence</div>
        <div v-else>Income</div>
      </template>
      <template #item.transfer_wallet="{item}">
        <div v-if="item.transfer_wallet === null">-----</div>
        <div v-else>{{item.transfer_wallet}}</div>
      </template>
      <template #item.type_payment="{item}">
        <div v-if="item.type_payment === 'c'">Cash</div>
        <div v-else-if="item.type_payment === 'bt'">Bank transfer</div>
        <div v-else-if="item.type_payment === 'mb'">MB payment</div>
        <div v-else>-----</div>
      </template>
      <template #item.actions="{item}">
        <v-flex>
          <MovementDetails :movement="item" />
          <UserEditMovement :categories_names="categories_names" :movement="item" @edited="edited" />
        </v-flex>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import MovementDetails from "./MovementDetails";
import UserFilterMovements from "../user/UserFilterMovement.vue";
import UserExpenseMovement from "../user/UserExpenseMovement.vue";
import UserEditMovement from "../user/UserEditMovement.vue";
export default {
  components: { MovementDetails, UserFilterMovements, UserExpenseMovement, UserEditMovement },
  data: function() {
    return {
      movements: [],
      activator: "",
      isMovementsFiltered: false,
      categories_names: [],
      noFilterAlert: {
        value: false,
        text: "Não à movimentos para esses filtros"
      },
      headers: [
          {
            text: 'ID',
            align: 'left',
            sortable: false,
            value: 'id',
          },
          { text: 'Type', value: 'type'},
          { text: 'Transfer Email', value: 'transfer_wallet'},
          { text: 'Type of Payment',value: 'type_payment'},
          { text: 'Category', value:'category_name'},
          { text: 'Date', value:'date'},
          { text: 'Start Balance', value:'start_balance'},
          { text: 'End Balance', value:'end_balance'},
          { text: 'Value', value:'value'},
          { text: 'Actions', value: 'actions'},
        ],
    };
  },
  methods: {
    getMovements: function() {
      axios.get("api/wallet/movements/me").then(response => {
        this.movements = response.data;
      });
    },
     getAllCategoriesNames() {
      axios
        .get("api/categories/names")
        .then(response => {
          this.categories_names = response.data.map(category => category.name);
        })
        .catch(error => {
          console.log(error);
        });
    },
     getAllCategoriesNames() {
      axios
        .get("api/categories/names")
        .then(response => {
          response.data.map(category =>
            this.categories_names.push(category.name)
          );
        })
        .catch(error => {
          console.log(error);
        });
      },
      movementsFiltered: function(value) {
        if (value.length == 0) {
          this.isMovementsFiltered = false;
          this.noFilterAlert.value = true;
        } else {
          this.movements = value;
          this.isMovementsFiltered = true;
        }
      },
      movementRegisted: function(value){
        this.getMovements();
      },
      clearMovementsFiltered: function() {
        this.getMovements();
        this.isMovementsFiltered = false;
      },
      edited: function(movement_edited) {
        // console.log(movement_edited);
        if(movement_edited){
          this.getMovements();
        }
        // altera apenas o movimento que foi editado em vez de ir buscar todos os movimentos 
        // this.movements.forEach(movement => {
        //   if(movement.id == movement_edited.data.id){
        //     movement.category_id = movement_edited.data.category_id;
        //     movement.description = movement_edited.data.description;
        //   }
        // });  
      },
    },
    mounted() {
      this.getMovements();
      this.getAllCategoriesNames();
      if(this.$store.state.token == ''){
				this.$router.push({name: 'login'})
			}
    },
}
</script>

<style scoped>
.title {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
