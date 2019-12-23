<template>
  <div>
    <div class="text-left">
      <v-dialog v-model="dialog" width="700px">
        <template v-slot:activator="{ on }">
          <v-flex>
            <v-btn v-if="routeWalletMe" block color="blue-grey darken-1" v-on="on">Register Expense</v-btn>
            <v-btn v-if="routeMovementList" small class="mx-1" dark color="blue-grey darken-1" v-on="on">Register Expense</v-btn>
          </v-flex>
        </template>
        <v-card>
          <v-container fluid>
            <v-row align="center">
              <v-col cols="20" align="center">
                <label>Register Expense</label>
                <v-select
                  v-model="data.type_movement_selected"
                  :items="type_movements"
                  label="Choose Type of Movement"
                  chips
                  hide-selected
                  clearable
                ></v-select>
                <v-select
                  v-if="data.type_movement_selected == 'External Entity'"
                  v-model="data.movement_payment_type_selected"
                  :items="movement_payment_types"
                  label="Choose Movement Payment Type"
                  chips
                  hide-selected
                  clearable
                ></v-select>
                <v-text-field
                  v-if="data.movement_payment_type_selected == 'Bank Transfer'"
                  id="iban"
                  label="IBAN"
                  name="iban"
                  type="value"
                  v-model="data.iban"
                />
                <v-text-field
                  v-if="data.movement_payment_type_selected == 'Multibanco/MB payment'"
                  id="mb_code"
                  label="MB Code"
                  name="mb_code"
                  type="value"
                  v-model="data.mb_code"
                />
                <v-text-field
                  v-if="data.movement_payment_type_selected == 'Multibanco/MB payment'"
                  id="mb_reference"
                  label="MB Payment Reference"
                  name="mb_reference"
                  type="value"
                  v-model="data.mb_reference"
                />
                <v-text-field
                  v-if="data.type_movement_selected == 'Transfer'"
                  id="email"
                  label="Email Destination"
                  name="email_destination"
                  type="email_destination"
                  v-model="data.email_destination"
                />
                <v-text-field
                  v-if="data.type_movement_selected == 'Transfer'"
                  id="source_description"
                  label="Source Description"
                  name="source_description"
                  type="source_description"
                  v-model="data.source_description"
                />
                <v-text-field
                  id="value"
                  label="Value (From 0,01€ up to 5000,00€)"
                  name="value"
                  type="value"
                  v-model="data.value"
                />
                <v-select
                  v-model="data.category_selected"
                  :items="categories_names"
                  label="Filter by Category"
                  chips
                  clearable
                  hide-selected
                ></v-select>
                <v-text-field
                  id="description"
                  label="Description"
                  name="description"
                  type="description"
                  v-model="data.description"
                />
              </v-col>
            </v-row>
          </v-container>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="registerExpense">Register</v-btn>
            <v-btn color="red" @click="cancelRegisterExpense">Cancel</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    movement: null
  },
  data() {
    return {
      dialog: false,
      data: {
        senderEmail: this.$store.state.user.email,
        value: "",
        movement_payment_type_selected: "",
        category_selected: "",
        source_description: "",
        iban: "",
        mb_code: "",
        mb_reference: "",
        type_movement_selected: "",
        email_destination: "",
        description: ""
      },
      movement_payment_types: ["Bank Transfer", "Multibanco/MB payment"],
      categories_names: [],
      type_movements: ["External Entity", "Transfer"],
      routeWalletMe: false,
      routeMovementList: false,
    };
  },
  methods: {
    registerExpense() {
      axios
        .post("api/movements/users/register/expense", this.data)
        .then(response => {
          this.clearAllFields();
          this.dialog = false;
          this.$emit('registed');
        })
        .catch(error => {
          console.log(error.response.data.message);
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
    route() {
      if(this.$router.currentRoute.name == 'movementsList'){
        this.routeMovementList = true; 
      }
      if(this.$router.currentRoute.name == 'wallet'){
        this.routeWalletMe = true; 
      }
    },
    clearAllFields() {
      this.data.value = "";
      this.data.movement_payment_type_selected = "";
      this.data.category_selected = "";
      this.data.source_description = "";
      this.data.iban = "";
      this.data.mb_code = "";
      this.data.mb_reference = "";
      this.data.type_movement_selected = "";
      this.data.email_destination = "";
      this.data.description = "";
    },
    cancelRegisterExpense() {
      this.clearAllFields();
      this.dialog = false;
    }
  },
  mounted() {
    this.getAllCategoriesNames();
    this.route();
    if(this.$store.state.token == ''){
			this.$router.push({name: 'login'})
		}
  }
};
</script>
