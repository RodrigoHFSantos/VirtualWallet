<template>
  <div class="small">
    <h4>Number of categories used in expenses</h4>
    <line-chart :chart-data="datacollection" :height="180"></line-chart>
  </div>
</template>

<script>
import LineChart from "./LineChart.js";

export default {
  components: {
    LineChart
  },
  data() {
    return {
      objToArray: null,
      objToArray2: null,
      datacollectionPerUserTypes: {},
      numUsers: [],
      categoriesUsed: [],
      categoriesLabel: [],
      datacollection: {}
    };
  },

  methods: {
    getCategoriesUsed() {
      axios
        .get("api/movements/user/statistics/categories-used")
        .then(response => {
          console.log(response.data);
          this.objToArray = Object.values(response.data);

          this.objToArray.forEach(element => {
            this.categoriesUsed.push(element.numero);
            this.categoriesLabel.push(element.name);
          });
          this.datacollection = {
            labels: this.categoriesLabel,
            datasets: [
              {
                label: "Movements",
                backgroundColor: "#FF0066",
                data: this.categoriesUsed
              }
            ]
          };
        })
        .catch(error => {
          console.log(error);
        });
    }
  },
  mounted() {
    try {
      this.getCategoriesUsed();
      if (this.$store.state.token == "") {
        this.$router.push({ name: "login" });
      }
    } catch (error) {
      console.log(error);
    }
  }
};
</script>

<style>
.small {
  max-width: 800px;
  margin: 100px auto;
}
</style>
