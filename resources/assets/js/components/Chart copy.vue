<template>
  <div class="small">
    <line-chart :chart-data="datacollection"></line-chart>
    <button @click="fillData()">Randomize</button>
  </div>
</template>

<script>
  import LineChart from '../Chart.js'

  export default {
    components: { 
      LineChart
    },
    data () {
      return {
        datacollection: null
      }
    },
    mounted () {
      this.fillData()
    },
    methods: {
      fillData () {
        this.datacollection = {
          labels: [this.getRandomInt(), this.getRandomInt()],
          datasets: [
              {data: [10, 20, 30],
              backgroundColor: ['lightblue','#f87909','#f87979']}
            // {
            //   label: 'Data One',
            //   backgroundColor: '#f87979',
            //   data: [this.getRandomInt(), this.getRandomInt()]
            // }, {
            //   label: 'Data Two',
            //   backgroundColor: '#f87909',
            //   data: [this.getRandomInt(), this.getRandomInt()]
            // }
          ]
        }
      },
      getRandomInt () {
        return Math.floor(Math.random() * (50 - 5 + 1)) + 5
      }
    }
  }
</script>

<style>
  .small {
    max-width: 600px;
    margin:  150px auto;
  }
</style>