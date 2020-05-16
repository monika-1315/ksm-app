<template>
  <div class="small">
    <bar-chart :chart-data="datacollection"></bar-chart>
  </div>
</template>

<script>
import BarChart from "../BarChart.js";

export default {
  components: {
    BarChart
  },
  data() {
    return {
      datacollection: null,
      labels: [],
      data: [],
      dataa:[],
    };
  },
  mounted() {
    this.getStats();
  },
  methods: {
    getStats: function() {
      this.axios.get("/api/getDivisionsStats").then(
        function(response) {
          this.dataa=response.data;
          for (var division of response.data) {
            this.labels.push(division.town);
            this.data.push(division.cnt1);
          }
          this.fillData();
        }.bind(this)
      );
    },
    fillData() {
      this.datacollection = {
        labels: this.labels,

        datasets: [
          {
            label: "Liczba członków w oddziale",
            barPercentage: 0.8,
            barThickness: 20,
            maxBarThickness: 30,
            minBarLength: 2,
            data: this.data,
            backgroundColor: "rgba(255, 201, 24, 0.719)"
          }
        ]
      };
    },
    getRandomInt() {
      return Math.floor(Math.random() * (50 - 5 + 1)) + 5;
    }
  }
};
</script>

<style>
.small {
  max-width: 600px;
  margin: 150px auto;
  backgroundcolor: rgba(255, 201, 24, 0.719);
}
</style>