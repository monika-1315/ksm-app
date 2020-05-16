<template>
<div>
  <div class="small">
    <bar-chart :chart-data="datacollection"></bar-chart>
  </div>
  <div v-for="(division,i) in divisions" :key="i" class="small">
    <h4>{{division}}</h4>
    <pie-chart :chart-data="datacollections[i]"></pie-chart>
  </div>
</div>
</template>

<script>
import BarChart from "../BarChart.js";
import PieChart from "../PieChart.js";

export default {
  components: {
    BarChart,
    PieChart
  },
  data() {
    return {
      datacollection: null,
      datacollections: [],
      divisions: [],
      all_members: [],
      aut_members: [],
      dataa: []
    };
  },
  mounted() {
    this.getStats();
  },
  methods: {
    getStats: function() {
      this.axios.get("/api/getDivisionsStats").then(
        function(response) {
          this.dataa = response.data;
          for (var division of response.data) {
            this.divisions.push(division.town);
            this.all_members.push(division.cnt_all);
            this.aut_members.push(division.cnt_aut);
          }
          this.fillData();
        }.bind(this)
      );
    },
    fillData() {
      this.datacollection = {
        labels: this.divisions,

        datasets: [
          {
            label: "Liczba członków w oddziale",
            barPercentage: 0.8,
            barThickness: 20,
            maxBarThickness: 30,
            minBarLength: 2,
            data: this.all_members,
            backgroundColor: "rgba(255, 201, 24, 0.719)"
          }
        ]
      };
      for (var i = 0; i < this.divisions.length; i++) {
        this.datacollections.push({
          labels: ['Zautoryzowani członkowie', 'Niezatwierdzeni'],
          datasets: [
            {
              label: "Liczba członków w oddziale",
              data: [this.aut_members[i], this.all_members[i]-this.aut_members[i]],
              backgroundColor: ["rgba(255, 201, 24, 0.719)", "darkblue"],
            }
          ]
        });
      }
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