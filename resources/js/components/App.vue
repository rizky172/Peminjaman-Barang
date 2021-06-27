<template>
    <div>
        <template v-if="$route.path === '/' || $route.path === '/register'">
            <router-view></router-view>
        </template>
        <template v-else>
            <Navbar></Navbar>
            <router-view></router-view>
            <Footer></Footer>
        </template>
        <SweetAlert></SweetAlert>
    </div>
</template>
<script>
import Navbar from './Navbar.vue';
import SweetAlert from './SweetAlert.vue';
import Footer from './Footer.vue';
export default {
    name: 'App',
    components: {
        Navbar,
        SweetAlert,
        Footer
    }
}
</script>
