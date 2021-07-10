<template>
    <div>
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Peminjaman Barang</h1>
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
                                    <h3 class="card-title">Form Peminjaman Barang</h3>
                                </div>
                                <div class="card-body">
                                    <form role="form" @submit.prevent="onSubmit">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Barang</label>
                                                    <select class="form-control" v-model="barang_id" required>
                                                        <option v-for="row in optionsBarang" v-bind:value="row.id">
                                                            {{ row.nama }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Merk</label>
                                                    <input type="text" class="form-control" v-model="merk" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Kategori</label>
                                                    <input type="text" class="form-control" v-model="kategori" disabled>
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
                                                    <label>Keperluan</label>
                                                    <input type="text" class="form-control" v-model="formData.keperluan" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="form-group">
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
                                                <th rowspan="2" style="vertical-align: middle;">Nama</th>
                                                <th rowspan="2" style="vertical-align: middle;">Kategori</th>
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
                                                    <td>{{ row.nama_barang }}</td>
                                                    <td>{{ row.nama_kategori }}</td>
                                                    <td>{{ row.tgl_pinjam }}</td>
                                                    <td>{{ row.tgl_kembali }}</td>
                                                    <td>{{ row.tgl_kembali }}</td>
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
                                                                <b-button class="dropdown-item" @click="delData(row.id)">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </b-button>
                                                                <!-- <b-button class="dropdown-item" v-b-modal="'id-modal'" @click="formModal(row.id, 'detail')">
                                                                    <i class="fa fa-calendar"></i> Detail
                                                                </b-button> -->
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template v-else>
                                                <tr style="text-align:center">
                                                    <td colspan="10">Data Kosong</td>
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
    </div>
</template>
<script>
import Rows from './../DataTables/Rows.vue';
import ItemPerPage from './../DataTables/ItemPerPage.vue';
import Scroll from './../DataTables/Scroll.vue';
import Pagination from './../DataTables/Pagination.vue';
import Badges from './../DataTables/Badges.vue';
export default {
    name:'Index',
    components: {
        Rows,
        ItemPerPage,
        Scroll,
        Pagination,
        Badges
    },
    props: {
        readonly: false
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
            optionsBarang:{},
            barang_id:0,
            merk: '',
            kategori:'',
            formData:{}
        }
    },
    mounted() {
        this.getData();
        this.getDataBarang();
        this.getCount();
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
            let url = 'api/peminjaman/count';
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
            let url = 'api/peminjaman/table?s='+this.isScroll;
            axios.get(url).then(response => {
                if(response.data !== 'empty'){
                    this.totalPage = response.data.length;
                    this.table = response.data;
                    Bus.$emit('setTotalPage', this.totalPage);
                }
            }).catch(e => console.log(e));
        },
        getDataBarang: function(){
            let url = 'api/peminjaman/getBarangAll';
            axios.get(url).then(response => {
                if(response.data !=='empty'){
                    this.optionsBarang = response.data;
                }
            }).catch(e => console.log(e));
        },
        delData: function(id){
            this.$swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Jika kamu hapus, maka data tidak akan kembali lagi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
                }).then((result) => {
                if (result.value) {
                    let url = 'api/peminjaman/delete/'+id;
                    axios.get(url).then(response => {
                        if(response.data.class == 'success'){
                            Bus.$emit('sweetAlert', response.data);
                            // Bus.$emit('refreshData');
                            this.getData();
                        }else{
                            Bus.$emit('sweetAlert', response.data);
                        }
                    }).catch(e => console.log(e));
                }
            })
        },
        onSubmit: function(){
            Object.assign(this.formData,{barang_id:this.barang_id});
            let url = 'api/peminjaman/store';
            axios.post(url, this.formData).then(response => {
                if(response.data.class == 'success'){
                    Bus.$emit('sweetAlert', response.data);
                    this.formData={};
                    this.barang_id=0;
                    this.merk='';
                    this.kategori='';
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
        barang_id: function(){
            if(this.barang_id > 0){
                let barang      = this.optionsBarang.find(e => e.id == this.barang_id);
                if(barang){
                    this.merk       = barang.merk;
                    this.kategori   = barang.nama_kategori;
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