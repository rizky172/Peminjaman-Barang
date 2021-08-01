<script>
  import { Bar } from 'vue-chartjs'

  export default {
    extends: Bar,
    props: {
      getUrl: {
          type: String,
          default: "api/cek_peminjaman/"
      },
    },
    data () {
      return {
        chartData: {
          labels: [],
          datasets: []
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              },
              gridLines: {
                display: true
              }
            }],
            xAxes: [ {
              gridLines: {
                display: false
              }
            }]
          },
          legend: {
            display: true
          },
          responsive: true,
          maintainAspectRatio: false
        },
        options2: [],
      }
    },
    mounted () {
      this.renderChart(this.chartData, this.options);
      // this.bar();
    },
    methods: {
      getData: function(){
        var dataset = [];
        // var labels = [7,8];
        let url = this.getUrl+'grafik';
        axios.get(url).then(response => {
            // console.log(response.data);
            response.data.forEach((row, i) => {
              this.options2 = row.bulan;
              // labels.push(row.bulan);
              // this.chartData.labels = ['aku'];
              // this.$set(this.chartData.datasets, i, {
              //   label: 'Mobil',
              //   data : [1],
              //   fill: false,
              //   borderColor: '#2554FF',
              //   backgroundColor: '#2554FF',
              //   borderWidth: 1
              // })
            });
        }).catch(e => console.log(e));
        console.log(this.options2);
        // this.chartData.labels = labels;
        let data = {
          label: 'Mobil',
          data: [100],
          fill: false,
          borderColor: '#2554FF',
          backgroundColor: '#2554FF',
          borderWidth: 1
        };
        
        dataset.push(data);
        this.chartData.datasets = dataset;
        console.log(this.chartData);
      }
    },
    created: function () {
        this.getData();
    },
  }
</script>