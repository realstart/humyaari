<template>
    <div class="container">
      <div class="company_logo">
          <img class="img-responsive img-rounded" :src="'/uploads/logo/'+ company.logo">
      </div>
      <form class="form-signin" v-on:submit.prevent="onSubmit">
          <h2 class="form-signin-heading">Admin Login</h2>
          <div class="login-wrap">
            <div class="user-login-info">
                <div class="form-group has-feedback" :class="{'has-error': formErrors.has('email') }">
                  <input
                    name="email"
                    type="email"
                    class="form-control"
                    placeholder="Email"
                    v-model="email"
                    v-validate="'required|email'"
                  >
                </div>
                 <div class="form-group has-feedback" :class="{'has-error': formErrors.has('password') }">
                    <input
                      name="password"
                      type="password"
                      class="form-control"
                      placeholder="Password"
                      v-model="password"
                      v-validate="'required|min:6'"
                    >
                  
                  </div>
            </div>
            <!-- <label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
                <span class="pull-right">
                    <a data-toggle="modal" href="#myModal"> Forgot Password?</a>
                
                </span>
                </label> -->
             <input type="submit" value="Sign In" class="btn btn-lg btn-login btn-block">

            <div v-if="credentialsMismatched" class="row">
            <div class="col-xs-12">
                <br>
                <p class="text-center" style="color:red;">User name or password not matched</p>
              </div>
            </div>
            <!-- <div class="registration">
                Don't have an account yet?
                <a class="" href="registration.html">
                    Create an account
                </a>
                </div> -->
          </div>
          <!-- Modal -->
          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title">Forgot Password ?</h4>
                  </div>
                  <div class="modal-body">
                      <p>Enter your e-mail address below to reset your password.</p>
                      <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
                  </div>
                  <div class="modal-footer">
                      <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                      <button class="btn btn-success" type="button">Submit</button>
                  </div>
                </div>
            </div>
          </div>
          <!-- modal -->
      </form>
</div>
</template>

<script>
export default {
  name: "login",
  data() {
    return {
      email: '',
      password: '',
      credentialsMismatched: false,
       company: {
   
                   name:'',
                   logo:'',

       },
        
      
    }
  },
  mounted() {
    jQuery('body').addClass('login');
    this.CompanyInfo();
  },
  watch: {
    email() {
      this.credentialsMismatched = false;
    },
    password() {
      this.credentialsMismatched = false;
    }
  },
  methods: {
    CompanyInfo(){


         this.$Progress.start()
        axios.get('/api/v1/companny-info-for-login') 
             .then(response => {
   
                this.company.name = response.data.data[0].company_name
                this.company.logo = response.data.data[0].company_logo
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
       },

    login() {
      this.$validator
        .validateAll()
        .then(result => {
          if (!result) {
            return;
          }
          axios
              .post("/api/v1/login", {
                email: this.email,
                password: this.password,
              })
              .then(response => {
                console.log(response);

                localStorage.setItem("user", JSON.stringify(response.data.user));
                localStorage.setItem("token", response.data.token);
                location.reload();
              })
              .catch(e => {
                this.credentialsMismatched = true;
                console.error('[error]', e);
              });
        });
    },
    onSubmit(){

       this.$validator
        .validateAll()
        .then(result => {
          if (!result) {
            return;
          }
          axios
              .post("/api/v1/login", {
                email: this.email,
                password: this.password,
              })
              .then(response => {
                console.log(response);

                localStorage.setItem("user", JSON.stringify(response.data.user));
                localStorage.setItem("token", response.data.token);
                window.location.reload();
              })
              .catch(e => {
                this.credentialsMismatched = true;
                console.error('[error]', e);
              });
        });

    }
  }
};
</script>

<style  scoped>
  /* custom css */

    .company_logo{
    margin-top:6% !important;
    display: block;
    margin:0 auto;
    width:30%;
    text-align: center;
    }
    .company_logo img{
    width:160px;
    height:auto;
    margin:0 auto;
    }

    .form-signin{
        margin: 10px auto;
    }


    .form-signin h2.form-signin-heading{
        background-color: #520894 !important;
    }

    .btn-login{
        background-color: #721ea9 !important;
    }
</style>

