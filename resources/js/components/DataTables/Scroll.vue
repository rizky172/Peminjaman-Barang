<template>
    <div>
        <div class="float-left form-inline" v-if="totalRecords > 100">
            Scroll &nbsp;
            <select class="form-control" v-model="isScroll" @change="setScroll">
                <option v-for="item in itemPerScroll" :value="item">{{ item }}</option>
            </select>
        </div>
    </div>
</template>
<script>
export default {
    name:'Scroll',
    data(){
        return{
            isScroll:1,
            itemPerScroll:0,
            totalRecords:0
        }
    },
    methods:{
        setItemPerScroll:function(data){
            this.itemPerScroll = data;
        },
        setScroll:function(){
            Bus.$emit('setScroll', this.isScroll);
        },
        setTotalRecords:function(data){
            this.totalRecords = data;
        }
    },
    created: function(){
        Bus.$on('setTotalRecords', this.setTotalRecords);
        Bus.$on('setItemPerScroll', this.setItemPerScroll);
    }
}
</script>