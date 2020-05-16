<template>
<div class="container" id="charts">
  <h2>Statystyki dotyczące liczby członków w oddziałach</h2>
      <div class="progress" v-if="isProgress">
        <div class="indeterminate"></div>
      </div>
  <div class="big">
    <bar-chart :chart-data="datacollection"></bar-chart>
  </div><br>
  <h4> Statystyki autoryzacji użytkowników dla poszczególnych oddziałów:</h4>
  <div v-for="(division,i) in divisions" :key="i" class="small">
    <h5>{{division}}</h5>
    parafia {{parishes[i]}}
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
      parishes: [],
      all_members: [],
      aut_members: [],
      // dataa:[],
      isProgress: true,
    };
  },
  mounted() {
    this.getStats();
  },
  methods: {
    getStats: function() {
      this.axios.get("/api/getDivisionsStats").then(
        function(response) {
          // this.dataa = response.data;
          for (var division of response.data) {
            this.divisions.push(division.town);
             this.parishes.push(division.parish);
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
            barThickness: 30,
            maxBarThickness: 40,
            minBarLength: 3,
            data: this.all_members,
            backgroundColor: "rgba(255, 201, 24, 0.5)",
            borderColor: "rgb(255, 201, 24)",
            borderWidth: 2
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
              backgroundColor: ["rgba(255, 201, 24, 0.679)", "rgba(3, 35, 138, 0.674)"],
              borderColor: ["rgba(255, 201, 24,1)", "rgba(3, 35, 138, 1)"],
            borderWidth: 2
            }
          ]
        });
      }
      this.isProgress=false;
    },
    getRandomInt() {
      return Math.floor(Math.random() * (50 - 5 + 1)) + 5;
    }
  }
};
</script>

<style scoped>
.big {
  max-width: 800px;
  margin: 40px auto;
  backgroundcolor: rgba(3, 35, 138, 0.774);
}

.small {
  max-width: 400px;
  margin: 30px auto;
  display: inline-block;
}
.progress {
  margin: 0px;
  background-color: transparent;
}
.indeterminate {
  background-color: rgba(3, 35, 138, 0.774);
}
#charts{
  text-align: center;
}
</style>