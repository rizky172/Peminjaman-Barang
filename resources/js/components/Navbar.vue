<template>
    <div>
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-user"> {{ name }}</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <div class="dropdown-divider"></div>
                        <router-link :to="{ name: 'profil' }" class="dropdown-item">
                            <i class="fas fa-user"></i> Profil
                        </router-link>
                        <a href="#" @click.prevent="logout()" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-6">
            <a href="/" class="brand-link">
                <span class="brand-text font-weight-light">SIRODA</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <router-link :to="{ name: 'home' }" class="nav-link">
                                <i class="nav-icon fas fa-home"></i> 
                                    <p>Home</p>
                            </router-link>
                        </li>
                        <template v-if="role == 'admin'">
                        <li class="nav-item">
                            <router-link :to="{ name: 'mobil' }" class="nav-link">
                                <i class="nav-icon fas fa-car"></i> 
                                    <p>Mobil</p>
                            </router-link>
                        </li>
                        </template>
                        <li class="nav-item">
                            <router-link :to="{ name: 'peminjaman' }" class="nav-link">
                                <i class="nav-icon fas fa-database"></i> 
                                    <p>Peminjaman</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'cek_peminjaman' }" class="nav-link">
                                <i class="nav-icon fas fa-check"></i> 
                                    <p>Check Peminjaman</p>
                            </router-link>
                        </li>
                        <template v-if="role == 'admin'">
                            <li class="nav-header">EXAMPLES</li>
                            <li class="nav-item">
                                <router-link :to="{ name: 'pegawai' }" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i> 
                                        <p>Pegawai</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link :to="{ name: 'user' }" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i> 
                                        <p>Users</p>
                                </router-link>
                            </li>
                        </template>
                    </ul>
                </nav>
            </div>
        </aside>
    </div>      
</template>
<script>
export default {
    name :'Navbar',
    data () {
        return {
            name: $cookies.get('name'),
            role: $cookies.get('role'),
            token: $cookies.get('token')
        }
    },
    methods:{
        logout:function(){
            let config = {
                headers: {
                    'Authorization': 'Bearer ' + this.token,
                },
            }
            axios.post('/api/logout', {}, config)
                .then((response) => {
                    Bus.$emit('sweetAlert', response.data);
                    // localStorage.removeItem('token');
                    $cookies.keys().forEach(cookie => this.$cookies.remove(cookie));
                    this.$router.push({path: '/'});
                }).catch(e => console.log(e))
            },
    },
    created:function(){
        axios.defaults.headers.common['Content-Type'] = 'application/json';
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + $cookies.get('token');
    }
}
</script>