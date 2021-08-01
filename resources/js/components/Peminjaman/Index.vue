<template>
    <div>
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Peminjaman Mobil</h1>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Form Peminjaman Mobil</h3>
                                </div>
                                <div class="card-body">
                                    <form role="form" @submit.prevent="onSubmit">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Mobil</label>
                                                    <select class="form-control" v-model="mobil_id" required>
                                                        <option v-for="row in options" v-bind:value="row.id">
                                                            {{ row.jenis }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>No Plat</label>
                                                    <input type="text" class="form-control" v-model="no_plat" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Keperluan</label>
                                                    <input type="text" class="form-control" v-model="formData.keperluan" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Tanggal Pinjam</label>
                                                    <input type="date" class="form-control" v-model="formData.tgl_pinjam" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Tanggal Kembali</label>
                                                    <input type="date" class="form-control" v-model="formData.tgl_kembali" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for=""></label>
                                                    <b-button type="submit" class="form-control btn btn-success">
                                                        <i class="fa fa-save"></i> Simpan
                                                    </b-button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        &nbsp;
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <Rows></Rows>
                                    <div class="card-tools">
                                        <div class="input-group">
                                            <input type="text" class="form-control float-right" placeholder="Search" v-model="search" >
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr style="text-align:center">
                                                <th rowspan="2" style="vertical-align: middle;">No</th>
                                                <th rowspan="2" style="vertical-align: middle;">No Plat</th>
                                                <th rowspan="2" style="vertical-align: middle;">Jenis</th>
                                                <th rowspan="2" style="vertical-align: middle;">Gambar</th>
                                                <th colspan="3">Tanggal</th>
                                                <th rowspan="2" style="vertical-align: middle;">Keperluan</th>
                                                <th rowspan="2" style="vertical-align: middle;">Notes</th>
                                                <th rowspan="2" style="vertical-align: middle;">Status</th>
                                                <th rowspan="2"></th>
                                            </tr>
                                            <tr style="text-align:center">
                                                <th>Pinjam</th>
                                                <th>Kembali</th>
                                                <th>Sisa Hari</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-if="table.length > 0">
                                                <tr v-for="(row, index) in filterData" :key="row.id">
                                                    <td style="text-align:center">{{ index + 1 }}</td>
                                                    <td>{{ row.no_plat }}</td>
                                                    <td>{{ row.jenis }}</td>
                                                    <td style="text-align:center">
                                                        <template v-if="row.gambar">
                                                            <a target="_blank" :href="'/images/mobil/'+row.gambar">
                                                                <img :src="'/images/mobil/'+row.gambar" width="70px">
                                                            </a>
                                                        </template>
                                                        <template v-else>
                                                            <img :src="'/images/cars.png'" width="40px">
                                                        </template>
                                                    </td>
                                                    <td>{{ row.tgl_pinjam | convertDate }}</td>
                                                    <td>{{ row.tgl_kembali | convertDate }}</td>
                                                    <td>{{ row | diffDate }}</td>
                                                    <td>{{ row.keperluan }}</td>
                                                    <td>{{ row.notes }}</td>
                                                    <td>
                                                        <Badges
                                                            :status="row.status"
                                                        ></Badges>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <button style="margin:auto" type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                                <i class="fa fa-cog"></i> 
                                                            </button>
                                                            <div class="dropdown-menu" role="menu">
                                                                <b-button v-if="row.status == 'pending'" class="dropdown-item" @click="delData(row.id)">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </b-button>
                                                                <a target="_blank" v-bind:href="'/generatePDF/peminjaman/'+row.id"> 
                                                                    <b-button v-if="row.status == 'approved' || row.status == 'return'" class="dropdown-item">
                                                                        <i class="fa fa-download"></i> PDF
                                                                    </b-button>
                                                                </a>
                                                                <b-button class="dropdown-item" v-b-modal="'modal-history'" @click="formModal(row.id,'history')">
                                                                    <i class="fa fa-table"></i> History
                                                                </b-button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template v-else>
                                                <tr style="text-align:center">
                                                    <td colspan="11">Data Kosong</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <div class="card-footer clearfix">
                                    <ItemPerPage></ItemPerPage>
                                    <Scroll></Scroll>
                                    <Pagination></Pagination>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <History
            :get-url="getUrl"
            :post-url="postUrl"
        ></History>
        <Confirm></Confirm>
    </div>
</template>
<script>
import Rows from './../DataTables/Rows.vue';
import ItemPerPage from './../DataTables/ItemPerPage.vue';
import Scroll from './../DataTables/Scroll.vue';
import Pagination from './../DataTables/Pagination.vue';
import Badges from './../DataTables/Badges.vue';
import Confirm from './../DataTables/Confirm.vue';
import History from './History.vue';
export default {
    name:'Index',
    components: {
        Rows,
        ItemPerPage,
        Scroll,
        Pagination,
        Badges,
        History,
        Confirm
    },
    props: {
        getUrl: {
            type: String,
            default: "api/peminjaman/"
        },
        postUrl: {
            type: String,
            default: "api/peminjaman/"
        },
    },
    data () {
        return {
            table : [],
            itemPerPage : [],
            itemPerScroll: 0,
            search: '',
            entry: 0,
            totalPage: 0,
            totalRecords: 0,
            isPage: 0,
            isScroll: 0,
            isCurrentPage: 1,
            isPageNext: 0,
            isPageOld: 10,
            options:{},
            mobil_id:0,
            no_plat: '',
            formData:{}
        }
    },
    mounted() {
        this.getData();
        this.getDataMobil();
        this.getCount();
    },
    filters: {
        convertDate: function(data){
            var date = new Date(data);
            var arrbulan = [
                "Januari","Februari","Maret",
                "April","Mei","Juni","Juli",
                "Agustus","September","Oktober",
                "November","Desember"
            ];

            var tanggal = date.getDate();
            var bulan   = date.getMonth();
            var tahun   = date.getFullYear();
            var result  = tanggal+"-"+arrbulan[bulan]+"-"+tahun;
            
            return result;
        },
        diffDate: function (data) {
            var tgl_pinjam  = new Date(data.tgl_pinjam);
            var tgl_kembali = new Date(data.tgl_kembali);
            var timeDiff    = Math.abs(tgl_kembali.getTime() - tgl_pinjam.getTime());
            var diffDays    = Math.ceil(timeDiff / (1000 * 3600 * 24)); 

            if(diffDays == 0){
                var result = 'Sudah habis';
            }else{
                var result = diffDays;
            }
            return result;
        }
    },
    computed:{
        filterData: function(){
            var data = this.table;
            var filterKey = this.search;
            if(filterKey){
                data = data.filter(function (row) {
                    return Object.keys(row).some(function (key) {
                        return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                    })
                })
                let setPage = {isPage:this.entry}; 
                Bus.$emit('setPage', setPage);
            }
            if(this.isCurrentPage > 1 && this.isPage > 10){
                var a = Math.floor(this.isPage + this.isPageOld);
                data = data.slice(this.isPageOld, a);
            }else{
                if(this.isCurrentPage > 1){
                    data = data.slice(this.isPageOld, this.isPageNext);
                }else{
                    data = data.slice(0, this.isPage);
                }
            }
            this.entry = data.length;
            Bus.$emit('setEntry', this.entry);
            return data;
        },
    },
    methods: {
        formModal:function(id, cmd){
            let data = {
                'id'    : id,
                'cmd'   : cmd
            };
            Bus.$emit('formModal', data)
        },
        setItemPerPage: function(data){
            this.itemPerPage = data['itemPerPage'];
            this.isPage = data['isPage'];
        },
        setScroll: function(data){
            this.isScroll = data;
        },
        currentPage: function(data){
            this.isCurrentPage = data;
        },
        pageNext: function(data){
            this.isPageNext = data;
        },
        pageOld: function(data){
            this.isPageOld = data;
        },
        getCount: function(){
            let url = this.getUrl+'count';
            axios.get(url).then(response => {
                if(response.data !== 'empty'){
                    this.totalRecords = response.data.totalRecords;
                    this.itemPerScroll = Math.floor((response.data.totalRecords / 100) + 1);
                    Bus.$emit('setItemPerScroll', this.itemPerScroll);
                    Bus.$emit('setTotalRecords', this.totalRecords);
                }
            }).catch(e => console.log(e));
        },
        getData: function(){
            let url = this.getUrl+'table?s='+this.isScroll;
            axios.get(url).then(response => {
                if(response.data !== 'empty'){
                    this.totalPage = response.data.length;
                    this.table = response.data;
                    this.getDataMobil();
                    Bus.$emit('setTotalPage', this.totalPage);
                }
            }).catch(e => console.log(e));
        },
        getDataMobil: function(){
            let url = this.getUrl+'getMobilAll';
            axios.get(url).then(response => {
                if(response.data !=='empty'){
                    this.options = response.data;
                }
            }).catch(e => console.log(e));
        },
        delData: function(id){
            let data = {
                'cmd'       : 'get',
                'url'       : this.getUrl+'delete/'+id,
                'title'     : 'Apakah kamu yakin?',
                'text'      : 'Jika kamu hapus, maka data tidak akan kembali lagi !',
                'icon'      : 'warning'
            };
            Bus.$emit('confirm', data)
        },
        onSubmit: function(){
            Object.assign(this.formData,{mobil_id:this.mobil_id});
            let url = this.postUrl+'store';
            axios.post(url, this.formData).then(response => {
                if(response.data.class == 'success'){
                    Bus.$emit('sweetAlert', response.data);
                    this.formData={};
                    this.mobil_id=0;
                    this.no_plat='';
                    this.getData();
                }else{
                    Bus.$emit('sweetAlert', response.data);
                }
            }).catch(e => console.log(e));
        },
    },
    created: function () {
        Bus.$on('refreshData', this.getData);
        Bus.$on('setItemPerPage', this.setItemPerPage);
        Bus.$on('setScroll', this.setScroll);
        Bus.$on('setCurrentPage', this.currentPage);
        Bus.$on('setPageNext', this.pageNext);
        Bus.$on('setPageOld', this.pageOld);
    },
    watch: {
        isScroll: function () {
            this.getData()
        },
        mobil_id: function(){
            if(this.mobil_id > 0){
                let mobil      = this.options.find(e => e.id == this.mobil_id);
                if(mobil){
                    this.no_plat       = mobil.no_plat;
                }
            }
        }
    }
}
</script>
<style scoped>
    li a {
        cursor: pointer;
    }
</style>