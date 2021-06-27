<template>
    <div v-if="totalPage > 10">
        <div class="card-tools">
            <ul class="pagination float-right">
                <li class="page-item"
                    v-if="setPage > 1"
                    :class="{'disabled': firstPageOn }">
                    <a class="page-link" @click="prevPage()">&laquo;</a></li>
                <li class="page-item" 
                    v-for="item in setPage" :key="item"
                    :class="{'active': currentPage === item + firstPage - 1}">
                    <a class="page-link" @click="gotoPage(item + firstPage - 1)">
                        {{ item + firstPage -1 }}</a></li>
                <li class="page-item"
                    v-if="setPage > 1"
                    :class="{'disabled': lastPageOn }">
                    <a class="page-link" @click="nextPage()">&raquo;</a></li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    name:'Pagination',
    data(){
        return{
            pages:0,
            isPage:10,
            totalPage:0,
            firstPage: 1,
            currentPage: 1,
            pageNext: 0,
            pageOld: 10,
            isSearchPage:0
        }
    },
    computed:{
        lastPage: function () {
            return this.firstPage + this.setPage - 1;
        },
        firstPageOn: function () {
            return this.currentPage === this.firstPage;
        },
        lastPageOn: function () {
            return this.currentPage === this.lastPage;
        },
        setPage:function(){
            if(this.isSearchPage){
                var data = this.isSearchPage;
            }else{
                var data = Math.floor(this.totalPage / this.isPage);
            }
            return data;
        }
    },
    methods:{
        gotoPage: function(page){
            this.pageNext = Math.floor(page * this.isPage);
            this.pageOld = Math.floor(this.pageNext - this.isPage);
            this.currentPage = page;
            Bus.$emit('setPageOld', this.pageOld);
            Bus.$emit('setPageNext', this.pageNext);
            Bus.$emit('setCurrentPage', this.currentPage);
        },
        nextPage: function(){
            this.currentPage +=1;
            this.gotoPage(this.currentPage);
        },
        prevPage: function(){
            this.currentPage -=1;
            this.gotoPage(this.currentPage);
        },
        setItemPerPage:function(data){
            this.isPage = data['isPage'];
        },
        setTotalPage:function(data){
            this.totalPage = data;
        },
        setSearchPage:function(data){
            this.isSearchPage = data['isPage'];
        },
    },
    created: function(){
        Bus.$on('setItemPerPage', this.setItemPerPage);
        Bus.$on('setTotalPage', this.setTotalPage);
        Bus.$on('setPage', this.setSearchPage);
    }
}
</script>