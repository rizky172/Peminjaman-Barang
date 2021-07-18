<template>
    <div>
        <div class="content-wrapper">
            <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profile</h1>
                    </div>
                    <div class="col-sm-6">
                        <h1>Log Aktivitas</h1>
                    </div>
                </div>
            </div>
            </section>
            <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                src="/images/profile.png"
                                alt="User profile picture">
                            </div>
                            <h3 class="profile-username text-center">{{ formData.nama ? formData.nama : 'Data Tidak Ada' }}</h3>
                            <p class="text-muted text-center">{{ formData.nip ? formData.nip : 'Data Tidak Ada' }}</p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Account Id</b> 
                                    <a class="float-right">
                                        {{ formData.account_id ? formData.account_id : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>NIP</b> 
                                    <a class="float-right">
                                        {{ formData.nip ? formData.nip : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Nama</b>
                                    <a class="float-right">
                                        {{ formData.nama ? formData.nama : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Tanggal Lahir</b>
                                    <a class="float-right">
                                        {{ formData.tgl_lahir ? formData.tgl_lahir : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Jenis Kelamin</b>
                                    <a class="float-right">
                                        {{ formData.jenis_kelamin ? formData.jenis_kelamin : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Agama</b>
                                    <a class="float-right">
                                        {{ formData.agama ? formData.agama : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b>
                                    <a class="float-right">
                                        {{ formData.email ? formData.email : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Telepon</b>
                                    <a class="float-right">
                                        {{ formData.tlp ? formData.tlp : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Alamat</b> 
                                    <a class="float-right">
                                        {{ formData.alamat ? formData.alamat : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Unit Kerja</b>
                                    <a class="float-right">
                                        {{ formData.unit_kerja ? formData.unit_kerja : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Jabatan</b>
                                    <a class="float-right">
                                        {{ formData.jabatan ? formData.jabatan : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                            </ul>
                            <b-button class="btn btn-primary btn-block" v-b-modal="'id-modal'" @click="formModal(account_id,'update')">
                                <i class="fa fa-edit"></i> Update
                            </b-button>
                            <b-button class="btn btn-primary btn-block" v-b-modal="'id-modal'" @click="formModal(account_id,'change')">
                                <i class="fa fa-key"></i> Change Password
                            </b-button>
                        </div>
                        </div>
                    </div>

        
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content"> 
                                    <Rows></Rows>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr style="text-align:center">
                                                <th>No</th>
                                                <th>Last Login</th>
                                                <th>Last Logout</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-if="table.length > 0">
                                                <tr v-for="(row, index) in filterData" :key="row.id">
                                                    <td style="text-align:center">{{ index + 1 }}</td>
                                                    <td>{{ row.last_login ? row.last_login : 'Kosong'  }}</td>
                                                    <td>{{ row.last_logout ? row.last_logout : 'Kosong' }}</td>
                                                </tr>
                                            </template>
                                            <template v-else>
                                                <tr style="text-align:center">
                                                    <td colspan="3">Data Kosong</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
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
            </div>
            </section>
        </div>
        <FormFieldProfil
            :get-url="getUrl"
            :post-url="postUrl"
        ></FormFieldProfil>
        <Confirm></Confirm>
    </div>
</template>
<script>
import FormFieldProfil from './FormFieldProfil.vue';
import Rows from './DataTables/Rows.vue';
import ItemPerPage from './DataTables/ItemPerPage.vue';
import Scroll from './DataTables/Scroll.vue';
import Pagination from './DataTables/Pagination.vue';
import Confirm from './DataTables/Confirm.vue';
export default {
    name :'Profil',
    components: {
        FormFieldProfil,
        Rows,
        ItemPerPage,
        Scroll,
        Pagination,
        Confirm
    },
    props: {
        getUrl: {
            type: String,
            default: "api/profil/"
        },
        postUrl: {
            type: String,
            default: "api/profil/"
        },
    },
    data () {
        return {
            account_id: $cookies.get('account_id'),
            formData:{},
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
        this.getDataLog();
        this.getDataProfil();
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
        }
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
        formModal:function(id, cmd){
            let data = {
                'id'    : id,
                'cmd'   : cmd
            };
            Bus.$emit('formModal', data)
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
        getDataLog: function(){
            let url = this.getUrl+'table?s='+this.isScroll;
            axios.get(url).then(response => {
                if(response.data !== 'empty'){
                    this.totalPage = response.data.length;
                    this.table = response.data;
                    Bus.$emit('setTotalPage', this.totalPage);
                }
            }).catch(e => console.log(e));
        },
        getDataProfil: function(){
            let url = this.getUrl+'show/'+this.account_id;
            axios.get(url).then(response => {
                if(response.data){
                    this.formData=response.data;
                }
            }).catch(e => console.log(e));
        },
    },
    created: function () {
        Bus.$on('refreshData', this.getDataProfil);
        Bus.$on('setItemPerPage', this.setItemPerPage);
        Bus.$on('setScroll', this.setScroll);
        Bus.$on('setCurrentPage', this.currentPage);
        Bus.$on('setPageNext', this.pageNext);
        Bus.$on('setPageOld', this.pageOld);
    },
    watch: {
        isScroll: function () {
            this.getDataLog()
        }
    }
}
</script>