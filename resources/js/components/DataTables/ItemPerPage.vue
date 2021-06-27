<template>
    <div>
        <div class="float-left form-inline">
            Show &nbsp;
            <select class="form-control"  v-model="isPage" @change="setItemPerPage">
                <option v-for="item in itemPerPage" :value="item">{{ item }}</option>
            </select>
            &nbsp;
            &nbsp; 
            &nbsp;
        </div>
    </div>
</template>
<script>
export default {
    name:'ItemPerPage',
    data(){
        return{
            itemPerPage : [10,20,50,100],
            isPage: 10
        }
    },
    mounted() {
        this.setItemPerPage();
        // this.getCount();
    },
    methods:{
        setItemPerPage:function(){
            let data = {
                itemPerPage:this.itemPerPage,
                isPage:this.isPage
            };
            Bus.$emit('setItemPerPage', data);
        }
    },
    created:function(){
        // this.setItemPerPage();
    },
}
</script>