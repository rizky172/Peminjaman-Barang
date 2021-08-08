<script>
  import { Bar } from 'vue-chartjs';
  import moment from 'moment';

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
        chartData: {},
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
        }
      }
    },
    methods: {
      getData: function(id){
        this.chartData = {};
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        var randomColor = "rgb(" + r + "," + g + "," + b + ")";
        
        var name = [];
        var jumlah = [];
        var labels = [];
        
        axios.get(this.getUrl+'grafik/'+id).then(response => {
          name.push(response.data[0]['mobil']);
          response.data.forEach((row, i) => {
            const dates = moment(row.date, "YYYYMMDD").format("YYYY-MM");
            jumlah.push(row.jumlah);
            labels.push(dates);
          });
          
          this.renderChart(this.chartData, this.options);
        }).catch(e => console.log(e));
        
        var dataset = [
          {
            label: name,
            data: jumlah,
            fill: false,
            backgroundColor: randomColor
          }
        ];
        this.chartData.labels=labels;
        this.chartData.datasets=dataset;
      }
    },
    created: function () {
      Bus.$on('loadChart', this.getData);
    },
  }
</script>