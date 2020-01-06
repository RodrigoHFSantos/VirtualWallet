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
                  :rules="[$v.data.iban.required || 'Iban is required', $v.data.iban.alpha || 'Iban must have 2 capital letters and 23 digits']"
                />
                <v-text-field
                  v-if="data.movement_payment_type_selected == 'Multibanco/MB payment'"
                  id="mb_code"
                  label="MB Code"
                  name="mb_code"
                  type="value"
                  v-model="data.mb_code "
                  :rules="[$v.data.mb_code.required || 'MB code is required', $v.data.mb_code.numeric || 'MB code can only have digits!']"
                />
                <v-text-field
                  v-if="data.movement_payment_type_selected == 'Multibanco/MB payment'"
                  id="mb_reference"
                  label="MB Payment Reference"
                  name="mb_reference"
                  type="value"
                  v-model="data.mb_reference"
                  :rules="[$v.data.mb_reference.required || 'MB reference is required', $v.data.mb_reference.numeric || 'MB reference can only have digits!']"
                />
                <v-text-field
                  v-if="data.type_movement_selected == 'Transfer'"
                  id="email"
                  label="Email Destination"
                  name="email_destination"
                  type="email_destination"
                  v-model="data.email_destination"
                  :rules="[$v.data.email_destination.required || 'Email is required']"
                />
                <v-text-field
                  v-if="data.type_movement_selected == 'Transfer'"
                  id="source_description"
                  label="Source Description"
                  name="source_description"
                  type="source_description"
                  v-model="data.source_description"
                  :rules="[$v.data.source_description.required || 'Source description is required']"
                />
                <v-text-field
                  id="value"
                  label="Value (From 0,01€ up to 5000,00€)"
                  name="value"
                  type="value"
                  v-model="data.value"
                  :rules="[$v.data.value.required || 'Value is required', $v.data.value.numeric || 'Value can only have digits!', $v.data.value.between || 'Value between 0,01€ up to 5000,00€']"
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
                  :rules="[$v.data.description.required || 'Description is required']"
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

import  {required,numeric, between,minLength, maxLength,helpers} from 'vuelidate/lib/validators';
const alpha = helpers.regex('alpha', /[A-Z]{2}[0-9]{23}/);

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
  validations: {
        data: {
            value: {
                required,
                numeric,
                between: between(0.01, 5000)
            },
            mb_code: {
              required,
              numeric,
            },
            iban: {
              required,
              minLength: minLength(24),
              maxLength: maxLength(26),
              alpha,
            },
            description:{
              required,
            },
            email_destination:{
              required,
            },
            source_description:{
              required,
            },
            mb_reference:{
              required,
              numeric
            },
        }      
  },
  methods: {
    registerExpense() {
      axios
        .post("api/movements/users/register/expense", this.data)
        .then(response => {
          this.dialog = false;
          this.$emit('registed');
          axios.get('api/user/getByEmail', { params: { email: this.data.email_destination}})
          .then(response => {
             this.$socket.emit('privateMessage', 'Transfer Added!', this.$store.state.user, response.data);
           })

          this.$toast.success("Success", {
            position: "top-right ",
            timeout: 5000,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
            hideCloseButton: false,
            hideProgressBar: false,
            icon: true
          });
          this.clearAllFields();
        })
        .catch(error => {
          console.log(error.response.data.message);
          if(error.response.status == 404){
            this.$toast.error(error.response.data.message, {
              position: "top-right ",
              timeout: 5000,
              closeOnClick: true,
              pauseOnFocusLoss: true,
              pauseOnHover: true,
              draggable: true,
              draggablePercent: 0.6,
              hideCloseButton: false,
              hideProgressBar: false,
              icon: true
            });
          }
        })
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
    },
     sendEmail: function(){
            axios.post('api/user/send-email', {
                value: this.data.value,
                email: this.data.email_destination,
                description: this.data.source_description,
            })
            .then(response => {
                console.log(response);
                console.log("enviei email");
            })
        },
  },
  mounted() {
    this.getAllCategoriesNames();
    this.route();
    if(this.$store.state.token == ''){
			this.$router.push({name: 'login'})
		}
  },
  sockets: {
        privateMessage_unavailable(destUser) {
            this.sendEmail();
            console.log("adwadawd");
        }
    },
};
</script>
