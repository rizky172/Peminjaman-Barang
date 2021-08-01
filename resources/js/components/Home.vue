<template>
<div>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div>
        </div>
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 col-12">
            <bar-chart
              
            ></bar-chart>
          </div>
            
        </div>
      </div>
    </section>
  </div>
</div>
</template>
<script>
import BarChart from './BarChart.vue';
export default {
  name: 'Home',
  components: {
    BarChart
  },
  data() {
    return {
      chartData: []
    }
  },
  methods: {
    getData: function(){
      let url = 'api/cek_peminjaman/grafik';
      axios.get(url).then(response => {
        response.data.forEach((row, i) => {
              this.chartData
            });
          // this.chartData = response.data;
          // console.log(this.chartData);
      }).catch(e => console.log(e));
    }
  },
  created: function () {
    // this.getData();
  },

}
</script>