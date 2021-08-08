<template>
<div>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12" style="text-align:center">
            <h1 class="m-0 text-dark">Selamat Datang di Aplikasi Sistem Informasi Peminjaman Kendaraan</h1>
          </div>
          <div class="col-sm-6">
            <template v-if="role == 'admin'">
              <br>
              <h5 class="m-0 text-dark">Pilih Mobil</h5>
              <select class="form-control col-lg-4" v-model="mobil_id" required>
                  <option v-for="row in options" v-bind:value="row.id">
                      {{ row.jenis }}
                  </option>
              </select>
            </template>
          </div>
        </div>
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 col-12">
            <bar-chart></bar-chart>
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
      role: $cookies.get('role'),
      options:{},
      mobil_id: 0
    }
  },
  methods: {
    getDataMobil: function(){
        let url = 'api/cek_peminjaman/getMobilAll';
        axios.get(url).then(response => {
          this.mobil_id=response.data[0]['id'];
          this.options = response.data;
        }).catch(e => console.log(e));
    },
  },
  created: function () {
    this.getDataMobil();
  },
  watch: {
    mobil_id: function(){
        if(this.mobil_id > 0){
            Bus.$emit('loadChart', this.mobil_id);
        }
    }
  }

}
</script>