<template>
  <div>
    <div class="text-center">
      <v-dialog v-model="dialog" width="500">
        <template v-slot:activator="{ on }">
          <v-btn small class="info" dark v-on="on" @click.stop="dialog = true">Details</v-btn>
        </template>
        <v-card  class="mx-auto" max-height="450px">
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
            <div
              v-if="movement.iban == null && movement.description == null && movement.source_description == null
               && movement.mb_payment_reference == null && movement.mb_payment_reference == null"
            >
              <label class="label">Não há detalhes disponiveis!</label>
            </div>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" text @click="dialog = false">Close</v-btn>
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
      dialog: false
    };
  },
  mounted() {
    // console.log(this.movement);
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

