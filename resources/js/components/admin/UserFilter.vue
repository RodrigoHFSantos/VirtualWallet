<template>
  <div>
    <div class="text-left">
      <v-dialog v-model="dialog" width="700px">
        <template v-slot:activator="{ on }">
          <v-flex>
            <v-btn small class="mx-1" dark color="indigo" v-on="on" @click.stop="dialog=true">Filter Users</v-btn>
          </v-flex>
        </template>
        <v-card>
          <v-container fluid>
            <v-row align="center">
              <v-col cols="12" align="center">
                <label>Filter Users</label>
                <v-select
                  v-model="user_type_selected"
                  :items="user_types"
                  label="Filter by User Type"
                  chips
                  clearable
                  hide-selected
                ></v-select>
                <v-select
                  v-model="name_selected"
                  :items="names"
                  label="Filter by User Name"
                  chips
                  clearable
                  hide-selected
                ></v-select>
                <v-select
                  v-model="email_selected"
                  :items="emails"
                  label="Filter by User Email"
                  chips
                  clearable
                  hide-selected
                ></v-select>
                <v-select
                  v-model="status_selected"
                  :items="status"
                  label="Filter by User Status (only for User type)"
                  chips
                  clearable
                  hide-selected
                ></v-select>
              </v-col>
            </v-row>
          </v-container>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green" @click="filter">Filter</v-btn>
            <v-btn color="red" @click="cancelFilter">Cancel</v-btn>
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
        user_type_selected: "",
        user_types: ['Administrator', 'Operator', 'User'],
        name_selected: "",
        names: [],
        email_selected: "",
        emails: [],
        status_selected: "",
        status: ['Active', 'Inactive'],
        dialog: false,
        i: 0,
    };
  },
  methods: {
    filter() {
      axios
        .get("api/users/filter", {
          params: {
            type_selected: this.user_type_selected,
            name: this.name_selected,
            email: this.email_selected,
            status: this.status_selected,
          }
        })
        .then(response => {
            this.dialog = false;
            const objToArray = Object.values(response.data);
            for (this.i = 0; this.i < objToArray.length; this.i++) {
               objToArray[this.i].photo = 'storage/fotos/' + objToArray[this.i].photo;
            }
            this.$emit('clicked', objToArray);
            this.clearAllFields();
        })
        .catch(error => {
          this.clearAllFields();
          console.log(error);
        });
    },
    getUsersNames: function() {
        axios.get("api/users/names-emails")
        .then(response => {
            response.data.map(user => this.names.push(user.name));
            response.data.map(user => this.emails.push(user.email));
        })
    },
    clearAllFields(){
        this.user_type_selected = '',
        this.name_selected = '',
        this.email_selected = '',
        this.status_selected = '';
    },
    cancelFilter() {
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
    this.getUsersNames();
    this.clearAllFields();
  }
};
</script>
