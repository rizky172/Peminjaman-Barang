<template>
    <div>
        <div class="float-left form-inline">
            <span>Showing 1 to {{ entry }} of {{ totalPage }} Entries</span>
        </div>
    </div>
</template>
<script>
export default {
    name:'Rows',
    data(){
        return {
            entry : 0,
            totalPage: 0
        }
    },
    methods:{
        setTotalPage: function(data){
            this.totalPage = data;
        },
        setEntry: function(data){
            this.entry = data
        }
    },
    created:function(){
        Bus.$on('setTotalPage', this.setTotalPage);
        Bus.$on('setEntry', this.setEntry);
    }

}
</script>
<style></style>