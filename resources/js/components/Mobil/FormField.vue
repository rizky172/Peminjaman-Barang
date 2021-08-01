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
                <form @submit.prevent="onSubmit" enctype="multipart/form-data">
                    <input type="hidden" class="form-control" 
                                v-model="formData.id" disabled >
                    <template>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">No Plat</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="text" class="form-control" 
                                v-model="formData.no_plat" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Jenis</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="text" class="form-control" 
                                v-model="formData.jenis" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Gambar</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="file" class="form-control" 
                                @change="setFile" :disabled="disabled" ref="image" id="image">
                                <br>
                                <img v-if="gambar" :src="gambar" width="100%" height="60%">
                                <img v-else-if="formData.gambar" :src="'/images/mobil/'+formData.gambar" width="100%" height="60%">
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
            disabled: false,
            options: {},
            gambar: null,
        }
    },
    methods: {
        resetModal: function(){
            this.cmd = 'store';
            this.formData = {};
            this.disabled = false;
            this.gambar=null;
            
        },
        formModal: function(data){
            let url = this.getUrl+'show/'+data.id;
            axios.get(url).then(response => {
                if(response.data){
                    if(data.cmd == 'update'){
                        this.cmd = data.cmd;
                        this.formData = response.data;
                    }
                }
            }).catch(e => console.log(e));
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
        },
        setFile:function(e){
            var fileReader = new FileReader()
            fileReader.readAsDataURL(e.target.files[0])
            fileReader.onload = (e) => {
                this.formData.file = e.target.result;
                this.gambar = e.target.result;
            }   
        }
    },
    created: function () {
        Bus.$on('formModal', this.formModal);
    }
}
</script>
