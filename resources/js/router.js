import Vue from 'vue';
import Router from 'vue-router';
import Login from './components/Login.vue';
import Eror404 from './components/Error404.vue';
import Register from './components/Register.vue';
import Home from './components/Home.vue';
import User from './components/User/Index.vue';
import Kategori from './components/Kategori/Index.vue';
import Dummy from './components/Dummy/Index.vue';
import Produk from './components/Produk/Index.vue';

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
      name:'kategori',
      path:'/kategori',
      component: Kategori,
      meta: {
        auth: true
      },
    },
    {
      name:'dummy',
      path:'/dummy',
      component: Dummy,
      meta: {
        auth: true
      },
    },
    {
      name:'produk',
      path:'/produk',
      component: Produk,
      meta: {
        auth: true
      },
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