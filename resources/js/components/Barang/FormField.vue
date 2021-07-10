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
                            <label class="col-form-label col-md-4">Nama</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="text" class="form-control" 
                                v-model="formData.nama" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Merk</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="text" class="form-control" 
                                v-model="formData.merk" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Kategori</label>
                            <div class="col-md-8">
                                <select class="form-control" v-model="id_kategori">
                                    <option v-for="option in options" v-bind:value="option.id">
                                        {{ option.nama }}
                                    </option>
                                </select>
                            </div> 
                        </div>
                        <template v-if="nama_kategori">
                            <div class="form-group row">
                                <label class="col-form-label col-md-4">Stok</label>
                                <div class="col-md-8">
                                    <input autocomplete="off" type="number" class="form-control" 
                                    v-model="formData.stok" :disabled="disabled">
                                </div> 
                            </div>
                        </template>
                        <!-- <div class="form-group row">
                            <label class="col-form-label col-md-4">Gambar</label>
                            <div class="col-md-8">
                                <input  type="file" class="form-control" id="file" ref="file"
                                v-on:change="setFile" :disabled="disabled" required />
                            </div> 
                        </div> -->
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
            options: {},
            files: '',
            id_kategori: 0,
            nama_kategori: false
        }
    },
    mounted() {
        this.getKategori();
    },
    methods: {
        resetModal: function(){
            this.cmd = 'store';
            this.formData = {};
            this.id_kategori=0;
            this.nama_kategori=false;
            this.disabled = false;
            
        },
        formModal: function(data){
            if(data[1] == 'delete'){
                this.delData(data[0]);
            }
            let url = 'api/barang/show/'+data[0];
            axios.get(url).then(response => {
                if(response.data){
                    if(data[1] == 'edit'){
                        this.cmd = 'update';
                        this.formData = response.data;
                        this.id_kategori=response.data.id_kategori;
                    }
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
                    let url = 'api/barang/delete/'+id;
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
        onSubmit: function(){
            Object.assign(this.formData,{id_kategori:this.id_kategori});
            let url = 'api/barang/'+this.cmd;
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
        getKategori: function(){
            let url = 'api/barang/getKategori';
            axios.get(url).then(response => {
                if(response.data !=='empty'){
                    this.options = response.data;
                }
            }).catch(e => console.log(e));
        },
        getKategoriById: function(){
            let url = 'api/barang/getKategoriById/'+this.id_kategori;
            axios.get(url).then(response => {
                this.nama_kategori=false;
                if(response.data.nama !== 'Mobil'){
                    this.nama_kategori = true;
                }
            }).catch(e => console.log(e));
        },
        setFile:function(e){
            // this.formData.file = e.target.files[0];
            var fileReader = new FileReader()
                fileReader.readAsDataURL(e.target.files[0])
                fileReader.onload = (e) => {
                    this.formData.file = e.target.result
                }   
        }
    },
    created: function () {
        Bus.$on('formModal', this.formModal);
    },
    watch: {
        id_kategori: function () {
            if(this.id_kategori > 0){
                this.getKategoriById()
            }
        }
    }
}
</script>
