<template>
  <div class="main">
  <TABLE BORDER="4"    WIDTH="100%"   CELLPADDING="4" CELLSPACING="5" id='dashboard'>
   <TR>
      <TH class='banner' COLSPAN="2" ROWSPAN="1">
      <h1 align="center" >iLinya Dashboard</h1>
      </TH>
   </TR>
   <TR>
      <TH>Now Serving</TH>
      <TH>Time</TH>
   </TR>
   <TR ALIGN="CENTER">
     <td bgcolor="#64a4d2"><ul>
        <li v-for="qnum in qnums">&nbsp;&nbsp;&nbsp;{{qnum.text}}</li>
      </ul></td>
      <TD bgcolor="#64a4d2" class='datetime'>{{dateToday}} {{hourToday}}:{{minuteToday}}</TD>
   </TR>
   <TR ALIGN="CENTER">
     <td> <bar-chart :chart-data="datacollection" :options="{responsive: false, maintainAspectRatio: false}"></bar-chart>
          <button @click="fillData()">Generate Graph</button>
    </td>
      <TD><bar-chart :chart-data="weeklydatacollection" :options="{responsive: false, maintainAspectRatio: false}"></bar-chart>
    <button @click="fillData()">Generate Graph</button>
    </TD>
   </TR>
</TABLE>
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
        dateToday: this.getDate(),
        hourToday: this.getHour(),
        minuteToday: this.getMinute(),
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
              label: 'Monthly Visits',
              backgroundColor: '#219900',
              data: [this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt()]
            }
          ]
        }
        this.weeklydatacollection = {
          labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
          datasets: [
            {
              label: 'Weekly Visits',
              backgroundColor: '#219900',
              data: [this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt()]
            }
          ]
        }
      },
      getRandomInt () {
        return Math.floor(Math.random() * (50 - 5 + 1)) + 5
      },
      getDate (){
        var d = new Date()
        return d.toDateString()
      },
      getHour (){
        var t = new Date()
        return t.getHours()
      },
      getMinute (){
        var i = new Date()
        return i.getMinutes()
      }
    }
  }
</script>

<style scoped>

th.banner{
  font-size: 40px;
  color: #ffffff;
}

td.datetime{
  font-size: 40px;
  color: #ffffff;
}

li { 
  color: #ff4500;
  background: black; 
}

li:nth-child(odd) { 
  background: #2b1e1e; 
}

h1{
  background-color: #64a4d2;

}

div.main {
    width: 900px;
    margin: auto;
    border: 1px solid #73AD21;
    font-family: ;
}

  .small {
    max-width: 600px;
    margin:  150px auto;
  }
</style>
