import Vue from 'vue';
import Router from 'vue-router';
import Login from './components/Login.vue';
import Eror404 from './components/Error404.vue';
import Register from './components/Register.vue';
import Home from './components/Home.vue';
import User from './components/User/Index.vue';
import Kategori from './components/Kategori/Index.vue';
import Barang from './components/Barang/Index.vue';
import Pegawai from './components/Pegawai/Index.vue';
import Profil from './components/Profil.vue';
import Pinjam from './components/Pinjam/Index.vue';

Vue.use(Router);

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '*', 
      component: Eror404,
      meta: {
        auth: true
      }
    },
    {
      name: 'login',
      path: '/',
      component: Login
    },
    {
      name: 'register',
      path: '/register',
      component: Register
    },
    {
      name: 'home',
      path: '/home',
      component: Home,
      meta: {
        auth: true
      },
    },
    {
      name:'user',
      path:'/user',
      component: User,
      meta: {
        auth: true
      },
    },
    {
      name:'pegawai',
      path:'/pegawai',
      component: Pegawai,
      meta: {
        auth: true
      },
    },
    {
      name:'kategori',
      path:'/kategori',
      component: Kategori,
      meta: {
        auth: true
      },
    },
    {
      name:'barang',
      path:'/barang',
      component: Barang,
      meta: {
        auth: true
      },
    },
    {
      name:'profil',
      path:'/profil',
      component: Profil,
      meta: {
        auth: true
      }
    },
    {
      name:'pinjam',
      path:'/pinjam',
      component: Pinjam,
      meta: {
        auth: true
      }
    }
  ]
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.auth)) {
    if (localStorage.getItem('token')) {
      next();
    } else {
      window.alert('Harus Login Dulu !')
      next('/');
    }
  } else {
    if(localStorage.getItem('token') && to.path == '/'){
      next({name :'home'})
    }else{
      next();
    }
  }
});



export default router;