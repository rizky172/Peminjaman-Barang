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
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Kategori</label>
                            <div class="col-md-8">
                                <select class="form-control" v-model="formData.id_kategori">
                                    <option v-for="option in options" v-bind:value="option.id">
                                        {{ option.nama }}
                                    </option>
                                </select>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Stok</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="number" class="form-control" 
                                v-model="formData.stok" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Harga Beli</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="number" class="form-control" 
                                v-model="formData.harga_beli" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Harga Jual</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="number" class="form-control" 
                                v-model="formData.harga_jual" :disabled="disabled" required>
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
    data (){
        return {
            formData: {},
            show: false,
            cmd: 'store',
            disabled: false,
            options:{}
        }
    },
    mounted() {
        this.getKategori();
    },
    methods: {
        getKategori: function(){
            let url = 'api/produk/getKategori';
            axios.get(url).then(response => {
                if(response.data !=='empty'){
                    this.options = response.data;
                }
            }).catch(e => console.log(e));
        },
        resetModal: function(){
            this.cmd = 'store';
            this.formData = {};
            this.disabled = false;
        },
        onSubmit: function(){
            // console.log(this.formData);
            let url = 'api/produk/'+this.cmd;
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
                    let url = 'api/produk/delete/'+id;
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
            let url = 'api/produk/show/'+data[0];
            axios.get(url).then(response => {
                if(response.data){
                    if(data[1] == 'edit'){
                        this.cmd = 'update';
                        this.formData = response.data;
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
