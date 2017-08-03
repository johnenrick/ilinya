<template>
  <div class="main">
  <h1 padding>&nbsp;iLinya Dashboard</h1>
  <table>
    <tr>
    <h6>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now Serving&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Counter</h6>
      <ul>
        <li v-for="qnum in qnums">&nbsp;&nbsp;&nbsp;{{qnum.text}}</li>
      </ul>
    </tr>
    <tr></tr>
  </table>
  <table>
    <tr>
      <bar-chart :chart-data="datacollection" :options="{responsive: false, maintainAspectRatio: false}"></bar-chart>
    <button @click="fillData()">Generate Graph</button>
    </tr>
    <tr>
      <bar-chart :chart-data="weeklydatacollection" :options="{responsive: false, maintainAspectRatio: false}"></bar-chart>
    <button @click="fillData()">Generate Graph</button>
    </tr>
  </table>  
  </div>
</template>

<script>
  import BarChart from './BarChart.js'

  export default {
    components: {
      BarChart
    },
    data () {
      return {
        datacollection: null,
        weeklydatacollection: null,
        qnums: [
         {text: '1141'},
         {text: '1142'},
         {text: '1143'},
         {text: '1144'},
         {text: '1145'}
        ]
      }
    },
    mounted () {
      this.fillData()
    },
    methods: {
      fillData () {
        this.datacollection = {
          labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
          datasets: [
            {
              label: 'Monthly View',
              backgroundColor: '#219900',
              data: [this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt()]
            }
          ]
        }
        this.weeklydatacollection = {
          labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
          datasets: [
            {
              label: 'Weekly View',
              backgroundColor: '#219900',
              data: [this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt()]
            }
          ]
        }
      },
      getRandomInt () {
        return Math.floor(Math.random() * (50 - 5 + 1)) + 5
      }
    }
  }
</script>

<style scoped>
li { 
  color:#ff4500;
  background: black; 
}
li:nth-child(odd) { 
  background: #2b1e1e; 
}

h1{
  background-color: #42f4f1
}
div.main {
    width: 600px;
    margin: auto;
    border: 1px solid #73AD21;
}
  .small {
    max-width: 600px;
    margin:  150px auto;
  }
</style>
