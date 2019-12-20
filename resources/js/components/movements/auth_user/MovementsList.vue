<template>
  <div>
    <div class="title pt-6 pb-6">
      <h1>Movements</h1>
    </div>
    <div class="row ml-8">
        <UserFilterMovements :activator="activator" @clicked="movementsFiltered" />
        <UserExpenseMovement :activator="activator" @registed="movementRegisted"/>
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
        <MovementDetails :movement="item" />
      </template>
      </v-data-table>
  </div>
</template>

<script>
import MovementDetails from "./MovementDetails";
import UserFilterMovements from "../user/UserFilterMovement.vue";
import UserExpenseMovement from "../user/UserExpenseMovement.vue";
export default {
  components: { MovementDetails, UserFilterMovements, UserExpenseMovement },
  data: function() {
    return {
      movements: [],
      activator: "",
      isMovementsFiltered: false,
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
          // { text: 'Calories'}, value: 'calories' },
          { text: 'Type', value: 'type'},
          { text: 'Transfer Email', value: 'transfer_wallet'},
          { text: 'Type of Payment',value: 'type_payment'},
          { text: 'Category', value:'category_name'},
          { text: 'Date', value:'date'},
          { text: 'Start Balance', value:'start_balance'},
          { text: 'End Balance', value:'end_balance'},
          { text: 'Value', value:'value'},
          {text: 'Actions', value: 'actions'},
        ],
    };
  },
  methods: {
    getMovements: function() {
      axios.get("api/wallet/movements/me").then(response => {
        // console.log(response.data.data);
        this.movements = response.data;
      });
    },
    movementsFiltered: function(value) {
      //ver se isto esta a funcionar
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
    }
  },
  mounted() {
    this.getMovements();
  }
};
</script>

<style scoped>
.title {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>

