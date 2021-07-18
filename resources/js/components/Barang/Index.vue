<template>
    <div>
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Mobil <span class="nav-icon fas fa-car"></span></h1>
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
                                    <Rows></Rows>
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
                                    <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr style="text-align:center">
                                                <th>No</th>
                                                <th>No Plat</th>
                                                <th>Jenis</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-if="table.length > 0">
                                                <tr v-for="(row, index) in filterData" :key="row.id">
                                                    <td style="text-align:center">{{ index + 1 }}</td>
                                                    <td>{{ row.no_plat }}</td>
                                                    <td>{{ row.jenis }}</td>
                                                    <td>
                                                        <div class="input-group">
                                                            <button style="margin:auto" type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                                <i class="fa fa-cog"></i> 
                                                            </button>
                                                            <div class="dropdown-menu" role="menu">
                                                                <b-button class="dropdown-item" v-b-modal="'id-modal'" @click="formModal(row.id,'update')">
                                                                    <i class="fa fa-edit" ></i> Edit
                                                                </b-button>
                                                                <b-button class="dropdown-item" @click="delData(row.id)">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </b-button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template v-else>
                                                <tr style="text-align:center">
                                                    <td colspan="4">Data Kosong</td>
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
        <FormField
            :get-url="getUrl"
            :post-url="postUrl"
        ></FormField>
        <Confirm></Confirm>
    </div>
</template>
<script>
import FormField from './FormField.vue';
import Rows from './../DataTables/Rows.vue';
import ItemPerPage from './../DataTables/ItemPerPage.vue';
import Scroll from './../DataTables/Scroll.vue';
import Pagination from './../DataTables/Pagination.vue';
import Confirm from './../DataTables/Confirm.vue';
export default {
    name:'Index',
    components: {
        FormField,
        Rows,
        ItemPerPage,
        Scroll,
        Pagination,
        Confirm
    },
    props: {
        getUrl: {
            type: String,
            default: "api/barang/"
        },
        postUrl: {
            type: String,
            default: "api/barang/"
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
            isPageOld: 10
        }
    },
    mounted() {
        this.getData();
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
                    Bus.$emit('setTotalPage', this.totalPage);
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
        }
    }
}
</script>
<style scoped>
    li a {
        cursor: pointer;
    }
</style>