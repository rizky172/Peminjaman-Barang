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
                    <a href="#" @click.prevent="logout()" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    </div>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-6">
            <a href="/" class="brand-link">
                <span class="brand-text font-weight-light">Peminjaman Barang</span>
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
                            <li class="nav-item has-treeview">
                            <a href="" class="nav-link">
                            <i class="nav-icon fas fa-sitemap"></i>
                                <p>
                                    Master
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <router-link :to="{ name: 'barang' }" class="nav-link">
                                        <i class="nav-icon fas fa-cubes"></i> 
                                            <p>Barang</p>
                                    </router-link>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <router-link :to="{ name: 'kategori' }" class="nav-link">
                                        <i class="nav-icon fas fa-cubes"></i> 
                                            <p>Kategori</p>
                                    </router-link>
                                </li>
                            </ul>
                        </li>  
                        </template>
                        <template v-if="role == 'member'">
                            <li class="nav-item has-treeview">
                            <a href="" class="nav-link">
                            <i class="nav-icon fas fa-sitemap"></i>
                                <p>
                                    Transaksi
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-cubes"></i> 
                                            <p>Peminjaman</p>
                                    </a>
                                        
                                </li>
                            </ul>
                        </li>  
                        </template>

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
    data: () => ({
        name: localStorage.getItem('name'),
        role: localStorage.getItem('role'),
        token: localStorage.getItem('token'),
    }),   
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
                    localStorage.removeItem('token');
                    this.$router.push({path: '/'});
                }).catch(e => console.log(e))
            },
    },
}
</script>