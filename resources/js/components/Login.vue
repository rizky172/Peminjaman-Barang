<template>
  <div class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <b>Peminjaman Barang</b>
      </div>
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Sign in</p>
          <form @submit.prevent="onSubmit">
            <div class="input-group mb-3">
              <input type="email" 
                v-model="email" 
                class="form-control" 
                placeholder="Email" 
                required="">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" 
                v-model="password"
                class="form-control" 
                placeholder="Password" 
                required="">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-8">
                <div class="icheck-primary">
                  <input type="checkbox" id="remember">
                  <label for="remember">
                    Remember Me
                  </label>
                </div>
              </div>
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
              </div>
            </div>
          </form>
          <p class="mb-0">
            <router-link :to="{ name: 'register' }">
                <p>Register</p>
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
    name: 'login',
    data () {
        return {
            email: '',
            password: '',
        }
    },
    methods: {
        onSubmit: function () {
                let data = {
                    'email' : this.email,
                    'password' : this.password
                }
                axios.post('/api/login', data)
                .then((response) => {
                  if(response.data.class == 'success'){
                    Bus.$emit('sweetAlert', response.data);
                    Vue.$cookies.set('id', response.data.data.id);
                    Vue.$cookies.set('account_id', response.data.data.account_id);
                    Vue.$cookies.set('name', response.data.data.name);
                    Vue.$cookies.set('email', response.data.data.email);
                    Vue.$cookies.set('role', response.data.data.role);
                    Vue.$cookies.set('token', response.data.data.api_token, "60MIN");
                    
                    this.$router.push({path: '/home'});
                  }else{
                    Bus.$emit('sweetAlert', response.data);
                  }
                }).catch(e => console.log(e))   
        }
    },
    
}
</script>