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
                <form>
                    <input type="hidden" class="form-control" 
                                v-model="formData.id" disabled >
                    <template v-if="cmd !== 'change'">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">NIP</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="number" class="form-control"
                                minlength="13" maxlength="18" 
                                v-model="formData.nip" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Nama</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="text" class="form-control" 
                                v-model="formData.nama" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Tanggal Lahir</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="date" class="form-control" 
                                v-model="formData.tgl_lahir" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Agama</label>
                            <div class="col-md-8">
                                <select class="form-control" v-model="formData.agama"
                                :disabled="disabled" required>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                </select>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Jenis Kelamin</label>
                            <div class="col-md-8">
                                <select class="form-control" v-model="formData.jenis_kelamin"
                                :disabled="disabled" required>
                                    <option value="laki-laki">Laki-Laki</option>
                                    <option value="perempuan">Perempuan</option>
                                </select>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Unit Kerja</label>
                            <div class="col-md-8">
                                <textarea autocomplete="off" v-model="formData.unit_kerja"
                                class="form-control" :disabled="disabled" required></textarea>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Jabatan</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="text" class="form-control" 
                                v-model="formData.jabatan" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Telepon</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="text" class="form-control" 
                                v-model="formData.tlp" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Email</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="email" class="form-control" 
                                v-model="formData.email" :disabled="disabled" required>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Alamat</label>
                            <div class="col-md-8">
                                <textarea autocomplete="off" v-model="formData.alamat"
                                class="form-control" :disabled="disabled" required></textarea>
                            </div> 
                        </div>
                        <!-- <div class="form-group row">
                            <label class="col-form-label col-md-4">Foto</label>
                            <div class="col-md-8">
                                <input autocomplete="off" type="file" class="form-control" 
                                v-model="formData.tgl_lahir" :disabled="disabled" required>
                            </div> 
                        </div> -->
                    </template>
                    <template v-if="cmd == 'change'">
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
                    if(data.cmd == 'detail'){
                        this.cmd = data.cmd;
                        this.disabled = true;
                        this.formData = response.data;
                    }
                }
            }).catch(e => console.log(e));
        },
        resetModal: function(){
            this.cmd = 'store';
            this.formData = {};
            this.disabled = false;
        }
    },
    created: function () {
        Bus.$on('formModal', this.formModal);
    }
}
</script>