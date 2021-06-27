<template>
    <div>
        
    </div>
</template>
<script>
export default {
    name: 'SweetAlert',
    methods:{
        sweetAlert: function(data){
            this.$swal.fire({
                title: data.class,
                text: data.message,
                icon: data.class,
                timer: 2000
            });
        }
    },  
    created: function(){
        Bus.$on('sweetAlert', this.sweetAlert)
    }
}
</script>