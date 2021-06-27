<template>
    <div>
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Penerima</h1>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="float-left form-inline">
                                        Show &nbsp;
                                        <select class="form-control"  v-model="itemPerPage">
                                            <option>10</option>
                                            <option>25</option>
                                            <option>50</option>
                                            <option>75</option>
                                            <option>100</option>
                                        </select>
                                        &nbsp;
                                        Entries 
                                    </div>
                                    <div class="card-tools">
                                        <div class="input-group">
                                            <input type="text" class="form-control float-right" placeholder="Search" v-model="search" >
                                            &nbsp;
                                            <b-button variant="primary" v-b-modal="'id-modal'">
                                                <i class="fa fa-plus"></i> Tambah Data
                                            </b-button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr style="text-align:center">
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Kontak</th>
                                                <th>Alamat</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-if="table.length > 0">
                                                <tr v-for="(row, index) in table" :key="row.id">
                                                    <td style="text-align:center">{{ index + 1 }}</td>
                                                    <td>{{ row.name }}</td>
                                                    <td>{{ row.email }}</td>
                                                    <td>{{ row.kontak }}</td>
                                                    <td>{{ row.alamat }}</td>
                                                    <td>
                                                        <div class="input-group">
                                                            <button style="margin:auto" type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                                <i class="fa fa-cog"></i> 
                                                            </button>
                                                            <div class="dropdown-menu" role="menu">
                                                                <b-button class="dropdown-item" v-b-modal="'id-modal'" @click="formModal(row.id,'edit')">
                                                                    <i class="fa fa-edit" ></i> Edit
                                                                </b-button>
                                                                <b-button class="dropdown-item" @click="delData(row.id)">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </b-button>
                                                                <div class="dropdown-divider"></div>
                                                                <b-button class="dropdown-item" v-b-modal="'id-modal'" @click="formModal(row.id,'change')">
                                                                    <i class="fa fa-key"></i> Change Password
                                                                </b-button>
                                                                <b-button class="dropdown-item" v-b-modal="'id-modal'" @click="formModal(row.id, 'detail')">
                                                                    <i class="fa fa-calendar"></i> Detail
                                                                </b-button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template v-else>
                                                <tr style="text-align:center">
                                                    <td colspan="6">Data Kosong</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer clearfix">
                                    <template v-if="table.length > 0">
                                        <div class="float-left">
                                            <span>Showing 1 to {{ entry }} of {{ total }} Entries</span>
                                        </div>
                                    </template>
                                    <div class="float-right">
                                        <pagination :data="filterData" v-on:pagination-change-page="getData"></pagination>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <FormField></FormField>
    </div>
</template>
<script>
import FormField from './FormField.vue';
export default {
    name:'Index',
    components: {
        FormField
    },
    data () {
        return {
            table : [],
            filterData: {},
            search: '',
            entry: 0,
            total: 0,
            itemPerPage: 10
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        loadSearch: function(page){
            if (typeof page === 'undefined') {
                page = 1;
            }
            let url = 'api/penerima/search?q='+this.search;
            axios.get(url).then(response => {
                if(response.data){
                    this.entry = response.data.length;
                    this.table = response.data;
                    this.filterData = {};
                }
            }).catch(e => console.log(e));
        },
        getData: function(page){
            if (typeof page === 'undefined') {
                page = 1;
            } 
            let url = 'api/penerima/table?p='+this.itemPerPage+'&page='+page;
            axios.get(url).then(response => {
                if(response.data.data !== 'empty'){
                    this.entry = response.data.data.length;
                    this.total = response.data.total;
                    this.table = response.data.data;
                    this.filterData = response.data;
                }
            }).catch(e => console.log(e));
        },
        formModal:function(id, cmd){
            let data = [id,cmd]
            Bus.$emit('formModal', data)
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
                    let url = 'api/penerima/delete/'+id;
                    axios.get(url).then(response => {
                        if(response.data.class == 'success'){
                            Bus.$emit('sweetAlert', response.data);
                            this.table.splice(this.table.indexOf(id), 1);
                        }else{
                            Bus.$emit('sweetAlert', response.data);
                        }
                    }).catch(e => console.log(e));
                }
            })
        }
    },
    created: function () {
        Bus.$on('refreshData', this.getData);
    },
    watch: {
        search: function (q) {
          if (q != '') {
            this.loadSearch()  
          }
          else {
            this.getData()
          }
          
        },
        itemPerPage: function(p){
            this.getData()
        }
    }
}
</script>