<template>
  <div class="small">
    <h4>Number of movements per month in last year (2019)</h4>
    <line-chart :chart-data="datacollectionPerMonth" :height="100"></line-chart>
  
    <h4>Number of users per type</h4>
    <line-chart :chart-data="datacollectionPerUserTypes" :height="100"></line-chart>

    <h4>Categories Used</h4>
    <line-chart :chart-data="datacollectionCategoriesUsed" :height="100"></line-chart>
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
        movementsPerMonth: [],
        datacollectionPerMonth: {},
        categories_names: [],
        datacollectionCategoriesUsed: {},
    };
  },

  methods: {
    getMovementsPerMonth() {
      axios
        .get("api/movements/admin/statistics/movements-per-month")
        .then(response => {
          this.objToArray = Object.values(response.data);
          this.objToArray.forEach(element => {
            this.movementsPerMonth.push(element.length);
          });
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
    },
    getUsersPerType() {
      axios
        .get("api/movements/admin/statistics/users-per-type")
        .then(response => {
            this.objToArray = Object.values(response.data);
            this.objToArray.forEach(element => {
                this.numUsers.push(element);
            });
        
        this.datacollectionPerUserTypes = {
        labels: [
          "User",
          "Operator",
          "Admin",
        ],
        datasets: [
          {
            label: "Users",
            backgroundColor: "#FF0066",
            data: this.numUsers
          }
        ]
        };
        })
        .catch(error => {
          console.log(error);
        });
    },
    getCategoriesUsed(){
        axios.get("api/categories/names")
        .then(response => {
            response.data.map(category => this.categories_names.push(category.name));
        });

        axios.get("api/movements/admin/statistics/users-created-per-year")
        .then(response => {
            this.objToArray = Object.values(response.data);
            this.objToArray.forEach(element => {
                this.numUsers.push(element);
            });
            console.log(response.data);

            // this.datacollectionCategoriesUsed = {
            // labels: [
            //     "User",
            //     "Operator",
            //     "Admin",
            // ],
            // datasets: [
            //     {
            //         label: "Categories Used",
            //         backgroundColor: "#FF0066",
            //         data: this.numUsers
            //     }
            // ]
            // };
        })
    }
  },

  mounted() {
    try {
        this.getMovementsPerMonth();
        this.getCategoriesUsed();
        this.getUsersPerType();
        if (this.$store.state.token == "") {
            this.$router.push({ name: "login" });
        }
    } catch (error) {
      console.log(error);
    }
  }
}
</script>

<style>
.small {
  max-width: 800px;
  margin: 50px auto;
}
</style>



