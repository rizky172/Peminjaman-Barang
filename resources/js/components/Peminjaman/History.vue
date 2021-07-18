<template>
    <div>
        <b-modal 
            id="modal-history"
            hide-footer
            title="History"
            v-model="show"
            @show="resetModal"
            size="xl"
        >   
            <div class="modal-body" >
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <Rows></Rows>
                                <div class="card-tools">
                                    <div class="input-group">
                                        <input type="text" class="form-control float-right" placeholder="Search" v-model="search" >
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
                                            <th>Keperluan</th>
                                            <th>Notes</th>
                                            <th>Status</th>
                                            <th>User</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="table.length > 0">
                                            <tr v-for="(row, index) in filterData" :key="row.id">
                                                <td style="text-align:center">{{ index + 1 }}</td>
                                                <td>{{ row.no_plat }}</td>
                                                <td>{{ row.jenis }}</td>
                                                <td>{{ row.keperluan }}</td>
                                                <td>{{ row.notes }}</td>
                                                <td>
                                                    <Badges
                                                        :status="row.status"
                                                    ></Badges>
                                                </td>
                                                <td>{{ row.user }}</td>
                                                <td>{{ row.created_at | convertDate }}</td>
                                            </tr>
                                        </template>
                                        <template v-else>
                                            <tr style="text-align:center">
                                                <td colspan="8">Data Kosong</td>
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
        </b-modal>
    </div>
</template>
<script>
import Rows from './../DataTables/Rows.vue';
import ItemPerPage from './../DataTables/ItemPerPage.vue';
import Scroll from './../DataTables/Scroll.vue';
import Pagination from './../DataTables/Pagination.vue';
import Badges from './../DataTables/Badges.vue';
export default {
    name: 'History',
    components: {
        Rows,
        ItemPerPage,
        Scroll,
        Pagination,
        Badges
    },
    props: {
        getUrl: String,
        postUrl: String
    },
    data (){
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
            show: false,
            id:0,
        }
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
            var detik   = date.getSeconds();
            var menit   = date.getMinutes();
            var jam     = date.getHours();
            var tanggal = date.getDate();
            var bulan   = date.getMonth();
            var tahun   = date.getFullYear();
            var result  = tanggal+"-"+arrbulan[bulan]+"-"+tahun+" "+jam+" : "+menit+" : "+detik ;
            
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
            let url = this.getUrl+'countHistory/'+this.id;
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
            let data = {
                'pinjam_id'     : this.id,
                's'             : this.isScroll
            };
            let url = this.getUrl+'getHistoryById';
            axios.post(url, data).then(response => {
                if(response.data !== 'empty'){
                    this.totalPage = response.data.length;
                    this.table = response.data;
                    Bus.$emit('setTotalPage', this.totalPage);
                }
            }).catch(e => console.log(e));
        },
        formModal: function(data){
            if(data.cmd == 'history'){
                this.id=data.id;
                this.getData();
                this.getCount();
            }
        },
        resetModal: function(){
            this.table=[];
            // this.id=0;
        },
    },
    created: function () {
        Bus.$on('formModal', this.formModal);
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
