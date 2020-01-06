<template>
  <div>
    <div class="title pt-6 pb-6">
      <h1>Users Accounts</h1>
    </div>
    <div class="row ml-8">
      <UserFilter
        :activator="activator"
        @clicked="usersFiltered"
      />
    </div>
    <v-snackbar v-model="noFilterAlert.value">
      {{ noFilterAlert.text }}
      <v-btn color="red" text @click="noFilterAlert.value = false">Close</v-btn>
    </v-snackbar>
    <v-flex v-if="isUsersFiltered">
      <v-btn color="red" dark @click="clearUsersFiltered">Clear Filter</v-btn>
    </v-flex>
    <v-data-table
      :headers="headers"
      :items="users"
      :items-per-page="10"
      class="elevation-1 ml-8 mt-4 mr-8 mb-8"
    >
      <template #item.type="{item}">
          <div v-if="item.type === 'u'">User</div>
          <div v-if="item.type === 'o'">Operator</div>
          <div v-if="item.type === 'a'">Administrator</div>
      </template>
      <template #item.active="{item}">
        <div v-if="item.type === 'u'">
          <div v-if="item.active === '1'">Active</div>
          <div v-else>Inactive</div>
        </div>
      </template>
      <template #item.balance="{item}">
        <div v-if="item.type === 'u'">
          <div v-if="item.balance > 0">Has Money</div>
          <div v-if="item.balance == 0">Empty</div>
        </div>
        <div v-if="item.type === 'a' || item.type === 'o'">
          <div>-----</div>
        </div>
      </template>
      <template #item.photo="{item}">
        <div v-if="item.photo == 'storage/fotos/null'">No Photo</div>
        <div v-if="item.photo != 'storage/fotos/null'">
          <img :src="item.photo" height="50" weight="50">
        </div>
      </template>
      <template #item.actions="{item}">
        <v-flex>
          <div v-if="item.type === 'u'">
            <div v-if="item.balance == 0">
              <div v-if="item.active == 1">
                <v-btn color="red" @click="activateOrDesactivate(item.id, item.active)">Desactivate!</v-btn>
              </div>
            </div>
              <div v-if="item.active == 0">
                <v-btn color="green" @click="activateOrDesactivate(item.id, item.active)">Activate!</v-btn>
              </div>
            </div>
        </v-flex>
        <v-flex>
          <!-- <div v-if="item.id != this.myAccountId"> -->
            <div v-if="item.type === 'o' ||item.type === 'a'">
              <v-btn color="red" @click="removeUserOperatorOrAdmin(item.email)">Remove</v-btn>
            </div>
          <!-- </div> -->
        </v-flex>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import UserFilter from "./UserFilter.vue";
export default {
  components: {UserFilter},
  data: function() {
    return {
      users: [],
      activator: "",
      isUsersFiltered: false,
      noFilterAlert: {
        value: false,
        text: "Não à users para esses filtros"
      },
      imageUrl: [],
      i: 0,
      myAccountId: '',
      headers: [
          { text: 'User Type', value: 'type'},
          { text: 'Name', value: 'name'},
          { text: 'Email', value: 'email'},
          { text: 'Account Status',value: 'active'},
          { text: 'Balance', value:'balance'},
          { text: 'Photo', value:'photo'},
          { text: 'Actions', value: 'actions'},
        ],
    };
  },
  methods: {
    getUsers: function() {
      axios.get("api/users/all")
      .then(response => {
        this.users = response.data;
        for (this.i = 0; this.i < this.users.length; this.i++) {
          this.users[this.i].photo = 'storage/fotos/' + this.users[this.i].photo;
        }
      });
    },
    usersFiltered: function(response){
      this.isUsersFiltered = true;
      this.users = response;
      if (response.length == 0) {
        this.noFilterAlert.value = true;
      }
    },
    clearUsersFiltered: function() {
      this.getUsers();
      this.isUsersFiltered = false;
    },
    activateOrDesactivate: function(id, active_value) {
      axios.put('api/users/activate-or-deactivate', {id, active_value})
      .then(response => {
        console.log(response.data);
        this.getUsers();
      })
      .catch(error => {
        console.log(error);
      })
    },
    removeUserOperatorOrAdmin: function(email){
      axios.delete('api/user/delete', {data:{email}})
      .then(response => {
        console.log(response.data);
        this.getUsers();
      })
      .catch(error => {
        console.log(error);
      })
    }
  },
  mounted() {
    // this.myAccountId = this.$store.state.user.id;
    // console.log(this.myAccountId);
    this.getUsers();
      if(this.$store.state.token == ''){
        this.$router.push({name: 'login'})
      }
  },
  async created() {
    this.myAccountId = this.$store.state.user.id;
    
  }
}
</script>

<style scoped>
.title {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
