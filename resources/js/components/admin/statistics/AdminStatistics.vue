<template>
  <div class="small">
    <h4>Number of movements per month in last year (2019)</h4>
    <line-chart :chart-data="datacollectionPerMonth" :height="100"></line-chart>
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
      movementsPerMonth: [],
      datacollectionPerMonth: {}
    };
  },

  methods: {
    getMovementsPerMonth() {
      axios
        .get("api/movements/user/statistics/movements-per-month")
        .then(response => {
          this.objToArray = Object.values(response.data);
          // const objToArray = response.data;

          this.objToArray.forEach(element => {
            this.movementsPerMonth.push(element.length);
          });
          console.log(this.movementsPerMonth);
          this.datacollectionPerMonth = {
            labels: [
              "Janeiro",
              "Fevereiro",
              "Março",
              "Abril",
              "Maio",
              "Junho",
              "Julho",
              "Agosto",
              "Setembro",
              "Outubro",
              "Novembro",
              "Dezembro"
            ],
            datasets: [
              {
                label: "Movements",
                backgroundColor: "#FF0066",
                data: this.movementsPerMonth
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
      this.getMovementsPerMonth();
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
  margin: 50px auto;
}
</style>



