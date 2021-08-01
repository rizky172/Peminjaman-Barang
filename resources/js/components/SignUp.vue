<template>
    <div class="hold-transition register-page">
        <div class="register-box">
  <div class="register-logo">
    <a href="#"><b>SIRODA</b></a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Sign Up</p>

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
          <input type="number" class="form-control" placeholder="NIP" v-model="nip" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-address-card"></span>
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
            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
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
    name: 'SignUp',
    data () {
        return {
            name:'',
            nip: '',
            password: '',
            retype_password:''
        }
    },
    methods: {
        onSubmit: function () {
                let data = {
                    'name'              : this.name,
                    'nip'               : this.nip,
                    'password'          : this.password,
                    'retype_password'   : this.retype_password
                }
                axios.post('/api/register', data)
                .then((response) => {
                  if(response.data.class == 'success'){
                    Bus.$emit('sweetAlert', response.data);
                    this.$router.push({path: '/'});
                  }else{
                    Bus.$emit('sweetAlert', response.data);
                  }
                }).catch(e => console.log(e))   
        }
    },
}
</script>