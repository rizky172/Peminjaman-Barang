<template>
  <div class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <b>SIRODA</b>
      </div>
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Sign in</p>
          <form @submit.prevent="onSubmit">
            <div class="input-group mb-3">
              <input type="number" 
                minlength="18"
                maxlength="19"
                v-model="nip" 
                class="form-control" 
                placeholder="NIP" 
                required="">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-address-card"></span>
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
            <router-link :to="{ name: 'signup' }">
                <p>Sign Up</p>
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
            nip: '',
            password: '',
        }
    },
    methods: {
        onSubmit: function () {
                let data = {
                    'nip' : this.nip,
                    'password' : this.password
                }
                axios.post('/api/login', data)
                .then((response) => {
                  if(response.data.class == 'success'){
                    Bus.$emit('sweetAlert', response.data);
                    Vue.$cookies.set('id', response.data.data.id);
                    Vue.$cookies.set('account_id', response.data.data.account_id);
                    Vue.$cookies.set('name', response.data.data.name);
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