<template>
    <div>
        <div class="content-wrapper">
            <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profile</h1>
                    </div>
                    <div class="col-sm-6">
                        <h1>Log Aktivitas</h1>
                    </div>
                </div>
            </div>
            </section>
            <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                src="/images/profile.png"
                                alt="User profile picture">
                            </div>
                            <h3 class="profile-username text-center">{{ formData.nama ? formData.nama : 'Data Tidak Ada' }}</h3>
                            <p class="text-muted text-center">{{ formData.nip ? formData.nip : 'Data Tidak Ada' }}</p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Account Id</b> 
                                    <a class="float-right">
                                        {{ formData.account_id ? formData.account_id : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>NIP</b> 
                                    <a class="float-right">
                                        {{ formData.nip ? formData.nip : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Nama</b>
                                    <a class="float-right">
                                        {{ formData.nama ? formData.nama : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Tanggal Lahir</b>
                                    <a class="float-right">
                                        {{ formData.tgl_lahir ? formData.tgl_lahir : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Jenis Kelamin</b>
                                    <a class="float-right">
                                        {{ formData.jenis_kelamin ? formData.jenis_kelamin : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Agama</b>
                                    <a class="float-right">
                                        {{ formData.agama ? formData.agama : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b>
                                    <a class="float-right">
                                        {{ formData.email ? formData.email : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Telepon</b>
                                    <a class="float-right">
                                        {{ formData.tlp ? formData.tlp : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Alamat</b> 
                                    <a class="float-right">
                                        {{ formData.alamat ? formData.alamat : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Unit Kerja</b>
                                    <a class="float-right">
                                        {{ formData.unit_kerja ? formData.unit_kerja : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Jabatan</b>
                                    <a class="float-right">
                                        {{ formData.jabatan ? formData.jabatan : 'Data Tidak Ada' }}
                                    </a>
                                </li>
                            </ul>
                            <b-button class="btn btn-primary btn-block" v-b-modal="'id-modal'" @click="formModal(account_id,'edit')">
                                <i class="fa fa-edit"></i> Update
                            </b-button>
                        </div>
                        </div>
                    </div>

        
                    <div class="col-md-8">
                        <div class="card">
                        <div class="card-body">
                            <div class="tab-content"> 
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="text-align:center">
                                            <th>No</th>
                                            <th>Day</th>
                                            <th>Last Login</th>
                                            <th>Last Logout</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="text-align:center">
                                            <td colspan="4">Data Kosong</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            </section>
        </div>
        <FormFieldProfil></FormFieldProfil>
    </div>
</template>
<script>
import FormFieldProfil from './FormFieldProfil.vue';
export default {
    name :'Profil',
    components: {
        FormFieldProfil
    },
    data () {
        return {
            account_id: localStorage.getItem('account_id'),
            formData:{}
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData: function(){
            let url = 'api/profil/show/'+this.account_id;
            axios.get(url).then(response => {
                if(response.data){
                    this.formData=response.data;
                }
            }).catch(e => console.log(e));
        },
        formModal:function(id, cmd){
            let data = [id,cmd]
            Bus.$emit('formModal', data)
        },
    },
    created: function () {
        Bus.$on('refreshData', this.getData);
    }
}
</script>