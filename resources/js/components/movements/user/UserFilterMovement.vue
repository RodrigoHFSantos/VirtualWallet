<template>
  <div>
    <div class="text-left">
      <v-dialog v-model="dialog" width="700px">
        <template v-slot:activator="{ on }">
          <v-flex>
            <v-btn small class="mx-1" dark color="indigo" v-on="on" @click.stop="dialog=true">Filter Movements</v-btn>
          </v-flex>
        </template>
        <v-card>
          <v-container fluid>
            <v-row align="center">
              <v-col cols="12" align="center">
                <label>Filter Your Movements</label>
                <v-combobox
                  v-model="id_selected"
                  :items="ids_numbers"
                  label="Filter by Id"
                  chips
                  clearable
                  hide-selected
                ></v-combobox>
                <v-combobox
                  v-model="category_selected"
                  :items="categories_names"
                  label="Filter by Category"
                  chips
                  clearable
                  hide-selected
                ></v-combobox>
                <v-select
                  v-model="movement_type_selected"
                  :items="movement_types"
                  label="Filter by Movement Type"
                  chips
                  clearable
                  hide-selected
                ></v-select>
                <v-combobox
                  v-model="movement_payment_type_selected"
                  :items="movement_payment_types"
                  label="Filter by Payment Type"
                  chips
                  clearable
                  hide-selected
                ></v-combobox>
                <v-combobox
                  v-model="transfer_email_selected"
                  :items="transfer_emails"
                  label="Filter by Transfer e-mail"
                  chips
                  clearable
                  hide-selected
                ></v-combobox>
                <v-date-picker
                  mode="range"
                  v-model="range"
                  :select-attribute="selectDragAttribute"
                  :drag-attribute="selectDragAttribute"
                  is-inline
                  @drag="dragValue = $event"
                >
                  <div slot="day-popover" slot-scope="{ format}">
                    {{ format(dragValue ? dragValue.start : range.start, 'MMM D') }}
                    -
                    {{ format(dragValue ? dragValue.end : range.end, 'MMM D') }}
                  </div>
                </v-date-picker>
                <v-btn small color="red" @click="clearDate">Clear Date Selection</v-btn>
              </v-col>
            </v-row>
          </v-container>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green" small @click="filter">Filter</v-btn>
            <v-btn color="red" small @click="cancelRegisterExpense">Cancel</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    activator: null,
    categories_names: '' ,
  },
  data() {
    return {
      ids_numbers: [],
      id_selected: "",
      category_selected: "",
      movement_types: ["Expense", "Income"],
      movement_type_selected: "",
      movement_payment_types: ["Cash", "Bank Tranfer", "Multibanco/MB payment"],
      movement_payment_type_selected: "",
      transfer_emails: [],
      transfer_email_selected: "",
      dragValue: null,
      range: {
        start: "",
        end: ""
      },
      dialog: false,
    };
  },
  methods: {
    filter() {
      axios
        .get("api/movements/users/filter", {
          params: {
            id: this.id_selected,
            category: this.category_selected,
            movement_type: this.movement_type_selected,
            movement_payment: this.movement_payment_type_selected,
            transfer_email: this.transfer_email_selected,
            range_date: this.range,
            start_date: this.range.start,
            end_date: this.range.end
          }
        })
        .then(response => {
          this.dialog = false;
          const objToArray = Object.values(response.data);
          this.$emit('clicked', objToArray);
          this.clearAllFields();
        })
        .catch(error => {
          this.clearAllFields();
          console.log(error);
        });
    },
    getMyMovementsIds() {
      axios
        .get("api/movements/ids")
        .then(response => {
          response.data.map(movement => this.ids_numbers.push(movement.id));
        })
        .catch(error => {
          console.log(error);
        });
    },
    getEmailsFromMyMovements() {
      axios
        .get("api/movements/users/emails")
        .then(response => {
          response.data.map(email => this.transfer_emails.push(email));
        })
        .catch(error => {
          console.log(error);
        });
    },
    clearAllFields(){
      this.id_selected = '',
      this.category_selected = '',
      this.movement_type_selected = '',
      this.movement_payment_type_selected = '',
      this.transfer_email_selected = '',
      this.clearDate();
    },
    clearDate() {
      this.range = "";
    },
    cancelRegisterExpense() {
      this.clearAllFields();
      this.dialog = false;
    }
  },
  computed: {
    selectDragAttribute() {
      return {
        popover: {
          visibility: "hover",
          isInteractive: false // Defaults to true when using slot
        }
      };
    }
  },
  mounted() {
    this.getMyMovementsIds();
    this.getEmailsFromMyMovements();
    this.clearDate();
  }
};
</script>
