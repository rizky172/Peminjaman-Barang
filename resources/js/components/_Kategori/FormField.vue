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
                    <template>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Nama</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="text" class="form-control" 
                                v-model="formData.nama" :disabled="disabled" required>
                            </div> 
                        </div>
                    </template>
                    <template>
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
    props: {
        getUrl: String,
        postUrl: String
    },
    data (){
        return {
            formData: {},
            show: false,
            cmd: 'store',
            disabled: false
        }
    },
    methods: {
        formModal: function(data){
            let url = this.getUrl+'show/'+data.id;
            axios.get(url).then(response => {
                if(response.data !== 'empty'){
                    if(data.cmd == 'update'){
                        this.cmd = data.cmd;
                        this.formData = response.data;
                    }
                }
            }).catch(e => console.log(e));
        },
        resetModal: function(){
            this.cmd = 'store';
            this.formData = {};
            this.disabled = false;
        },
        onSubmit: function(){
            let url = this.postUrl+this.cmd;
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
        }
    },
    created: function () {
        Bus.$on('formModal', this.formModal);
    }
}
</script>
