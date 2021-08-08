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
                            <label class="col-form-label col-md-4">NIP</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="number" class="form-control" 
                                v-model="formData.nip" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Nama</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="text" class="form-control" 
                                v-model="formData.name" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Role</label>
                            <div class="col-md-8">
                                <select class="form-control" v-model="formData.role"
                                :disabled="disabled" required>
                                    <option value="admin">Admin</option>
                                    <option value="member">Member</option>
                                </select>
                            </div> 
                        </div>
                    </template>
                    <template v-if="cmd == 'store' || cmd == 'change'">
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
                    </template>
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
                if(response.data){
                    if(data.cmd == 'update'){
                        this.cmd = data.cmd;
                        this.formData = response.data;
                    }else if(data.cmd == 'detail'){
                        this.cmd = data.cmd;
                        this.disabled = true;
                        this.formData = response.data;
                    }else if(data.cmd == 'change'){
                        this.cmd = data.cmd;
                        this.formData = response.data;
                        delete this.formData.password;
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
        },
    },
    created: function () {
        Bus.$on('formModal', this.formModal);
    }
}
</script>