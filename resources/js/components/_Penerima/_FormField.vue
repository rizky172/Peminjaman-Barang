<template>
    <div>
        <!-- <div v-click-outside="resetModal"></div> -->
        <b-modal 
            id="id-modal"
            hide-footer
            title="Form"
            v-model="show"
            @show="resetModal"
        >   
            <div class="modal-body">
                <form @submit.prevent="onSubmit">
                    <input type="hidden" class="form-control" v-model="formData.id" disabled >
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input autocomplete="off" type="text" class="form-control" 
                                        v-model="formData.name" :disabled="disabled" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input autocomplete="off" type="email" class="form-control" 
                                        v-model="formData.email" :disabled="disabled" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Kontak</label>
                                    <input autocomplete="off" type="number" class="form-control" 
                                    v-model="formData.kontak" :disabled="disabled" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea autocomplete="off" class="form-control" 
                                    v-model="formData.alamat" :disabled="disabled" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea autocomplete="off" rows="5" class="form-control" 
                                    v-model="formData.keterangan" :disabled="disabled" required></textarea>
                                </div>
                            </div>
                        </div>
                    <template v-if="cmd !== 'detail'">
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" data-dismiss="id-modal" @click="show=false">Close</button>
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
            let url = 'api/penerima/'+this.cmd;
            console.log(this.formData)
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
        formModal: function(data){
            let url = 'api/penerima/show/'+data[0];
            axios.get(url).then(response => {
                if(response.data){
                    if(data[1] == 'edit'){
                        this.cmd = 'update';
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