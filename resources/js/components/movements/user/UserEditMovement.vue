<template>
  <div>
    <div class="text-center">
      <v-dialog v-model="dialog" width="500">
        <template v-slot:activator="{ on }">
          <v-icon small dark  style="font-size:20px;color:red" width="50" v-on="on" @click.stop="dialog = true">mdi-pencil</v-icon>
        </template>
        <v-card  class="mx-auto" max-height="450px">
          <v-card-title class="blue-grey darken-4 cardTitle" primary-title>Edit Movement</v-card-title>

          <v-card-text class="text--primary pt-4">
            <div>
              <p class="label">Category</p>
              <v-select
                  v-model="category_edit"
                  :items="categories_names"
                  label="Edit Category:"
                  chips
                  clearable
                  hide-selected
                ></v-select>
            </div>

            <div>
              <p class="label" text-color="blue-grey darken-4">Description</p>
              <textarea class="textArea" v-model="description_edit" placeholder="Movement Description"></textarea>
            </div>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue" small @click="editMovement">Edit</v-btn>
            <v-btn color="red" small @click="close">Close</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    movement: null,
    categories_names: '' ,
  },
  data() {
    return {
      dialog: false,
      category_edit: this.movement.category_name,
      description_edit: this.movement.description,
    };
  },
  methods:{
    editMovement() {
      axios.put('api/movements/users/edit', {
        id: this.movement.id,
        category: this.category_edit,
        description : this.description_edit
      })
      .then(response => {
        this.$emit('edited', response);
        this.close();
        this.$toast.success("Movement edited!", {
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
      })
      .catch(error => {
        console.log(error);
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
    close() {
      this.category_edit = '';
      this.description_edit = '';
      this.dialog = false;
    },
  },
  mounted() {
  }
};
</script>

<style lang="scss" scoped>
.cardTitle {
  font-size: 30px;
}

.label {
  font-size: 20px;
}

.textArea {
  width: 100%;
}
</style>

