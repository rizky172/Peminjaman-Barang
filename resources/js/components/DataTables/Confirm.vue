<template>
    <div>
        
    </div>
</template>
<script>
export default {
    name: 'Confirm',
    methods:{
        confirm: function(data){
            if(data.cmd == 'get'){
                this.cmdGet(data);
            }else if(data.cmd == 'post'){
                this.cmdPost(data);
            }
        },
        cmdGet: function(data){
            this.$swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
                }).then((result) => {
                if (result.value) {
                    axios.get(data.url).then(response => {
                        if(response.data.class == 'success'){
                            Bus.$emit('sweetAlert', response.data);
                            Bus.$emit('refreshData');
                        }else{
                            Bus.$emit('sweetAlert', response.data);
                        }
                    }).catch(e => console.log(e));
                }
            })
        },
        cmdPost: function(data){
            this.$swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
                }).then((result) => {
                if (result.value) {
                    axios.post(data.url, data.formData).then(response => {
                        if(response.data.class == 'success'){
                            Bus.$emit('sweetAlert', response.data);
                            Bus.$emit('refreshData');
                        }else{
                            Bus.$emit('sweetAlert', response.data);
                        }
                    }).catch(e => console.log(e));
                }
            })
        }
    },  
    created: function(){
        Bus.$on('confirm', this.confirm);
    }
}
</script>