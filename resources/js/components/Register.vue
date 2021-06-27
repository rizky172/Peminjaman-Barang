<template>
    <div class="hold-transition register-page">
        <div class="register-box">
  <div class="register-logo">
    <a href="#"><b>Inventory</b></a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register</p>

      <form @submit.prevent="onSubmit">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Full name" v-model="name" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" v-model="email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" v-model="password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Retype password" v-model="retype_password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div> -->
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
        <router-link :to="{ name: 'login' }">
                <p>Sign In</p>
        </router-link>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
    </div>
</template>
<script>
export default {
    name: 'Register',
    data () {
        return {
            name:'',
            email: '',
            password: '',
            retype_password:''
        }
    },
    methods: {
        onSubmit: function () {
                let data = {
                    'name'              : this.name,
                    'email'             : this.email,
                    'password'          : this.password,
                    'retype_password'   : this.retype_password
                }
                axios.post('/api/register', data)
                .then((response) => {
                  if(response.data.data){
                    Bus.$emit('sweetAlert', response.data);
                    // localStorage.setItem('id', response.data.data.id);
                    // localStorage.setItem('name', response.data.data.name);
                    // localStorage.setItem('token', response.data.data.api_token);
                    // this.$router.push({path: '/home'});
                  }else{
                    Bus.$emit('sweetAlert', response.data);
                  }
                }).catch(e => console.log(e))   
        }
    },
}
</script>