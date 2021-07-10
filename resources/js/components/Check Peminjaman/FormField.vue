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
                                v-model="id" disabled >
                    <template>
                        <div class="form-group row">
                            <label style="text-align:center" class="col-form-label col-md-12">
                                <h5>Notes</h5>
                            </label>
                            <br>
                            <textarea v-model="notes" class="form-control" required></textarea>
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
    data (){
        return {
            formData: {},
            show: false,
            cmd: 'store',
            disabled: false,
            id:0,
            notes:''
        }
    },
    methods: {
        formModal: function(data){
            let url = 'api/cek_peminjaman/show/'+data[0];
            axios.get(url).then(response => {
                if(response.data){
                    this.cmd = 'setStatus';
                    this.id = response.data.id;
                    // this.formData = response.data;
                }
            }).catch(e => console.log(e));
        },
        resetModal: function(){
            this.cmd = 'store';
            this.id=0;
            this.notes='';
            // this.formData = {};
            this.disabled = false;
        },
        onSubmit: function(){
            let data = {
                'id'        : this.id,
                'notes'     : this.notes,
                'status'    : 'rejected'
            };
            let url = 'api/cek_peminjaman/'+this.cmd;
            axios.post(url, data).then(response => {
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
