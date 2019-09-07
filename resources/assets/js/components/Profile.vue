<template>
   <div>
      <section>
         <div class="container">
            <div class="row">
               <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Update User Details
                        </header>
                        <div class="panel-body">
                            <div class="position-center">
                                     <form @submit.prevent="UpdateUserData">
                           <div class="form-group">
                              <label for="name">Userame</label>
                              <input type="text" class="form-control" name="name" id="name" v-model="profile.name" />
                              <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                           </div>
                           <div class="form-group">
                              <label for="firstname">First Name</label>
                              <input type="text" class="form-control" name="firstname" id="firstname" v-model="profile.firstname" />
                              <div v-if="errors && errors.firstname" class="text-danger">{{ errors.firstname[0] }}</div>
                           </div>
                           <div class="form-group">
                              <label for="lastname">Last Name</label>
                              <input type="text" class="form-control" name="lastname" id="lastname" v-model="profile.lastname" />
                              <div v-if="errors && errors.lastname" class="text-danger">{{ errors.lastname[0] }}</div>
                           </div>
                           <div class="form-group">
                              <label for="phone">Phone</label>
                              <input type="text" class="form-control" name="phone" id="phone" v-model="profile.phone" />
                              <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                           </div>
                           <input type="hidden" name="id" v-model="profile.id">
                           <button type="submit" class="btn btn-round btn btn-primary sbt-btn">Update</button>
                           <div v-if="success" class="alert alert-success mt-3">
                              Details Updated Successfully
                           </div>
                        </form>
                        <hr>
                        <h2 class="text-center">Change Password</h2>
                        <form @submit.prevent="PasswordChange">
                           <!-- <div class="form-group">
                              <label for="cur_password">Current Password</label>
                              <input type="text" class="form-control" v-on:change="PasswordCheck" name="cur_password" id="cur_password"  />
                              <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                           </div> -->
                           <div class="form-group">
                              <label for="password">New Password</label>
                              <input type="text" class="form-control" name="password" id="password" v-model="fields.password" />
                              <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
                           </div>
                           <!-- <div class="form-group">
                              <label for="cpassword">Confirm New password</label>
                              <input type="text" class="form-control" name="cpassword" id="cpassword" v-model="fields.cpassword" />
                              <div v-if="errors && errors.cpassword" class="text-danger">{{ errors.cpassword[0] }}</div>
                           </div> -->
                            <input type="hidden" name="id" v-model="fields.id">
                           <button type="submit" class="btn btn-round btn btn-info">Change</button>
                           <div v-if="success" class="alert alert-success mt-3">
                              Password Changed Successfully
                           </div>
                        </form>
                            </div>

                        </div>
                    </section>

            </div> 
            </div>
         </div>
      </section>
   </div>
</template>
<script type="text/ecmascript-6">
   export default {
     name: 'Profile',
     data() {
       return {
          currentUserID:'',
          user: JSON.parse(localStorage.getItem('user')),
          profile: {
   
                name:'',
                firstname : '',
                lastname : '',
                email: '',
                phone: '',
                id : '',
                password:'',
                
         },
        errors: {},
        fields:{},
        success: false,
        loaded: true, 
       }
     },
      
     created() {
         this.GetUserDetails();
     },
     methods: {
   
         GetUserDetails(){
             this.currentUserID = this.user.id
             this.$Progress.start()
             axios.get('/api/v1/get-user-info/'+this.currentUserID) 
             .then(response => {
                 
                  this.profile.name = response.data.data.name
                  this.profile.firstname = response.data.data.firstname
                  this.profile.lastname = response.data.data.lastname
                  this.profile.phone = response.data.data.phone
                  this.profile.email = response.data.data.email
                  this.profile.id = response.data.data.id
                  this.fields.id = response.data.data.id

              
              this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
         },
         UpdateUserData(){

             if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.post('/api/v1/update-user-info', this.profile).then(response => {
                   console.log(response);
                    // this.company = {}; //Clear input fields.
                    this.loaded = true;
                     this.success = true;
                    location.href= "/profile-management";
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 200) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }

         },
      //     PasswordCheck: function(e){
      //     let password = e.target.value;
      //      //console.log(password+' '+this.currentUserID);
      //         axios.post('/api/v1/check-user-password/'+this.currentUserID+'/'+password) 
      //            .then(response => {
      //               console.log(response);
      //               // if(response.data.data == 'yes'){
      //               //    this.emailSuccess = false;
      //               //     $(".sbt-btn").attr("disabled", true);
      //               //    //$(".sbt-btn").attr("disabled", true);
   
      //               // }else{
      //               //    this.emailSuccess = true;
                      
      //               // }
                    
      //            })
      //            .catch(e => {
        
      //               console.log(e)
      //                  this.$Progress.fail()
      //            })
   
      //  },
      PasswordChange(){

        if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.post('/api/v1/update-user-password', this.fields, this.profile).then(response => {
                   console.log(response);
                  
                   localStorage.removeItem('token');
                     localStorage.removeItem('user');
                     location.reload();
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 200) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }

      }
     },
     filters: {
       
     },
     
   }
   
</script>