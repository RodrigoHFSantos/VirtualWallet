<template>
  <div>
    <div class="text-center">
      <v-dialog v-model="dialog" width="500">
        <template v-slot:activator="{ on }">
          <v-icon class="mr-2" style="font-size:20px;color:#40C4FF" dark v-on="on" @click="showDetails(movement.id)">mdi-details</v-icon>
        </template>
        <v-card class="mx-auto" max-height="450px">
          <v-card-title class="blue-grey darken-4 cardTitle" primary-title>Details</v-card-title>

          <v-card-text class="text--primary pt-4">
            <div v-if="movement.iban != null">
              <p class="label">IBAN:</p>
              <p>{{movement.iban}}</p>
            </div>

            <div v-if="movement.mb_entity_code != null">
              <p class="label">MB entity code:</p>
              <p>{{movement.mb_entity_code}}</p>
            </div>

            <div v-if="movement.mb_payment_reference != null">
              <p class="label">MB payment reference:</p>
              <p>{{movement.mb_payment_reference}}</p>
            </div>

            <div v-if="movement.description != null">
              <p class="label" text-color="blue-grey darken-4">Descrição:</p>
              <p>{{movement.description}}</p>
            </div>
            <div v-if="movement.source_description != null">
              <p class="label">Descrição da fonte:</p>
              <p>{{movement.source_description}}</p>
            </div>
            <div v-if="imageUrl != null">
              <p class="label" text-color="blue-grey darken-4">Photo:</p>
              <img :src="imageUrl" height="150" weight="150">
            </div>
            <div
              v-if="movement.iban == null && movement.description == null && movement.source_description == null
               && movement.mb_payment_reference == null && movement.mb_payment_reference == null && imageUrl == null"
            >
              <label class="label">Não há detalhes disponiveis!</label>
            </div>

            

          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="dialog = false">Close</v-btn>
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
      imageUrl: '',
      // movementID: this.movement.id,
      i: 0,
    };
  },
  methods: {
    showDetails(id){
      this.dialog = true;
      axios.get("api/movements/details/photo", { params: {
          id: id
      }})
      .then(response => {
        if(response.data != null){
          this.imageUrl = 'storage/fotos/' + response.data;
        }

        if(response.data == ""){
          this.imageUrl = null;
        }
        

      })
      .catch(error => {
        console.log(error);
      });

    }
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
</style>

