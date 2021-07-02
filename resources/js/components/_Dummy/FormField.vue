<template>
    <div>
        <b-modal 
            id="id-modal"
            hide-footer
            title="Form"
            v-model="show"
            @show="resetModal"
        >   
            <div class="modal-body">
                <form @submit.prevent="onSubmit">
                    <input type="hidden" class="form-control" 
                                v-model="formData.id" disabled >
                    <template v-if="cmd !== 'change'">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Country Slug</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="text" class="form-control" 
                                v-model="formData.country_slug" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Region</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="text" class="form-control" 
                                v-model="formData.region" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Region Short</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="text" class="form-control" 
                                v-model="formData.region_short" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Region Slug</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="text" class="form-control" 
                                v-model="formData.region_slug" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Weight</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="number" class="form-control" 
                                v-model="formData.weight" :disabled="disabled" required>
                            </div> 
                        </div>
                    </template>
                    <!-- <template v-if="cmd == 'store' || cmd == 'change'">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Password</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="password" class="form-control" v-model="formData.password" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Retype Password</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="password" class="form-control" v-model="formData.retype_password" required>
                            </div> 
                        </div>
                    </template> -->
                    <template v-if="cmd !== 'detail'">
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" data-dismiss="my-modal" @click="show=false">Close</button>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </template>
                </form>
            </div>
        </b-modal>
    </div>
</template>
<script>
export default {
    name: 'FormField',
    data (){
        return {
            formData: {},
            show: false,
            cmd: 'store',
            disabled: false
        }
    },
    methods: {
        resetModal: function(){
            this.cmd = 'store';
            this.formData = {};
            this.disabled = false;
        },
        onSubmit: function(){
            let url = 'api/dummy/'+this.cmd;
            axios.post(url, this.formData).then(response => {
                if(response.data.class == 'success'){
                    Bus.$emit('sweetAlert', response.data);
                    Bus.$emit('refreshData');
                    this.show=false;
                    this.resetModal;
                }else{
                    Bus.$emit('sweetAlert', response.data);
                }
            }).catch(e => console.log(e));
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
                    let url = 'api/dummy/delete/'+id;
                    axios.get(url).then(response => {
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
        formModal: function(data){
            if(data[1] == 'delete'){
                this.delData(data[0]);
            }
            let url = 'api/dummy/show/'+data[0];
            axios.get(url).then(response => {
                if(response.data){
                    if(data[1] == 'edit'){
                        this.cmd = 'update';
                        console.log(response.data);
                        this.formData = response.data;
                    }else if(data[1] == 'detail'){
                        this.cmd = 'detail';
                        this.disabled = true;
                        this.formData = response.data;
                    }else if(data[1] == 'change'){
                        this.cmd = 'change';
                        this.formData = response.data;
                        delete this.formData.password;
                    }
                }
            }).catch(e => console.log(e));
        }
    },
    created: function () {
        Bus.$on('formModal', this.formModal);
    }
}
</script>