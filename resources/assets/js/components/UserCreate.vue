<template>
   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:;" v-on:click="LoadPrevious()">User</a></li>
            <!-- <li class="breadcrumb-item"><a href="#">Customer</a></li> -->
            <li class="breadcrumb-item"><a href="#">Add New User</a></li>
         </ol>
      </nav>
      <div class="">
         <vue-progress-bar></vue-progress-bar>
         <div class="row" v-if="!AddNew">
            <div class="col-md-12">
               <a class="btn btn-danger" title="+ Add New Account" href="#" v-on:click="AddNewUser()" >+ Add New User</a>
            </div>
         </div>
         <br>
         <div v-if="AddNew" class="row">
            <div class="col-lg-12">
               <section class="panel">
               <header class="panel-heading">
                  New User Details
               </header>
               <div class="panel-body">
                  <div class="position-center">
                      <form @submit.prevent="submit" class="col-md-8">
                        <div class="form-group">
                           <label for="name">Userame</label>
                           <input type="text" class="form-control" name="name" id="name" v-model="fields.name" />
                           <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                        </div>
                        <div class="form-group">
                           <label for="firstname">First Name</label>
                           <input type="text" class="form-control" name="firstname" id="firstname" v-model="fields.firstname" />
                           <div v-if="errors && errors.firstname" class="text-danger">{{ errors.firstname[0] }}</div>
                        </div>
                        <div class="form-group">
                           <label for="lastname">Last Name</label>
                           <input type="text" class="form-control" name="lastname" id="lastname" v-model="fields.lastname" />
                           <div v-if="errors && errors.lastname" class="text-danger">{{ errors.lastname[0] }}</div>
                        </div>
                        <div class="form-group">
                           <label for="phone">Phone</label>
                           <input type="text" class="form-control" name="phone" id="phone" v-model="fields.phone" />
                           <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                        </div>
                        <div class="form-group">
                           <label for="email">Email</label>
                           <input type="email" class="form-control" name="email" v-on:change="EmailCheck" id="email" v-model="fields.email" />
                           <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                        </div>
                        <div v-if="emailSuccess == true" class="alert alert-success mt-3">
                           Correct Email
                        </div>
                        <div v-if="emailSuccess == false" class="alert alert-danger mt-3">
                           This email is already registered
                        </div>
                        <div class="form-group">
                           <label for="password">Password</label>
                           <input type="text" class="form-control" name="password" id="password" v-model="fields.password" />
                           <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
                        </div>
                        <!-- <div class="form-group">
                           <label for="c_password">Confirm Password</label>
                           <input type="text" class="form-control" name="c_password" id="c_password" v-model="fields.c_password" />
                           <div v-if="errors && errors.c_password" class="text-danger">{{ errors.c_password[0] }}</div>
                           </div> -->
                        <div class="form-group">
                           <label for="role">User Role</label>
                           <select name="role" class="form-control" id="role" v-model="fields.role">
                              <option value="" disabled> --- Select Role ---</option>
                              <option value="admin">Admin</option>
                              <option value="sales">Sales</option>
                           </select>
                           <div v-if="errors && errors.role" class="text-danger">{{ errors.role[0] }}</div>
                        </div>
                        <button type="submit" class="btn btn-round btn btn-info">Create</button>
                        <div v-if="success" class="alert alert-success mt-3">
                           User Created Successfully
                        </div>
                     </form>
                  </div>
               </div>
               </section>
            </div>
         </div>
         <div class="row" v-if="!AddNew">
            <div class="col-md-12 table-responsive">
               <section class="panel">
                  <header class="panel-heading">
                     User List
                     <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                        <!-- <a href="javascript:;" class="fa fa-cog"></a> -->
                        <!-- <a href="javascript:;" class="fa fa-times"></a> -->
                     </span>
                  </header>
                  <div class="panel-body" style="display: block;">
                     <table class="table table-hover general-table">
                        <thead>
                           <tr>
                              <th scope="col">#</th>
                              <th scope="col">Name</th>
                              <th scope="col">Email</th>
                              <th scope="col">Role</th>
                              <th scope="col">Created At</th>
                              <th scope="col">Last Update</th>
                              <th scope="col">Settings</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr v-show="users.length" v-for="(user, index) in users" :key="user.id">
                              <th scope="row">{{index + 1}}</th>
                              <td>{{ user.name }}</td>
                              <td>{{ user.email }}</td>
                              <td style="text-transform:uppercase">{{ user.role }}</td>
                              <td>{{ user.created_at | moment("MMMM Do YYYY") }}</td>
                              <td>{{ user.updated_at | moment("MMMM Do YYYY") }}</td>
                              <td>
                                 <!-- <a href="" class="btn btn-info" title="View Account"><i class="fa fa-files-o"></i> View</a> -->
                                 <!-- <a href="javascript:;" v-on:click="ChangePassword(user.id)" class="btn btn-warning" title="Change Password"><i class="fa fa-key"></i></a> -->
                                 <!-- <a href="javascript:;" v-on:click="EditUser(user.id)" class="btn btn-success" title="Edit"><i class="fa fa-edit"></i></a> -->
                                 <button  type="button" v-on:click="ChangePassword(user.id)" class="btn btn-warning" data-toggle="modal" data-target="#myPasswordModal"><i class="fa fa-key"></i></button>

                                  <button  type="button" v-on:click="EditUser(user.id)" class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i></button>
                                 <a href="javascript:;"  v-on:click="DeleteUser(user.id)" class="btn btn-danger" title="Delete"><i class="fa fa-trash-o"></i></a>
                              </td>
                           </tr>
                           <tr v-show="!users.length" >
                              <td colspan="6">
                                 <div class="alert alert-danger" role="alert">
                                    Opps! Sorry no data found
                                 </div>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </section>
            </div>
         </div>
         <div class="text-center" v-if="!AddNew">
            <pagination v-if="pagination.last_page > 1"
               :pagination="pagination"
               :offset="5"
               @paginate="query === '' ? loadUsers() : SearchData()"   
               ></pagination>
         </div>
      </div>
      <div  class="modal fade" id="myModal" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Edit User Details</h4>
            </div>
            <div class="modal-body">
                  <section class="panel">
                     <!-- <header class="panel-heading">
                        Edit Customer Details
                     </header> -->
                     <div class="panel-body">
                        <div class="position-center">
                              <form @submit.prevent="Update">
                            <div class="form-group">
                                <label for="name">User Name</label>
                                <input type="template_title" class="form-control" name="name" id="name" v-model="user.name" />
                                <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input type="firstname" class="form-control" name="firstname" id="firstname" v-model="user.firstname" />
                                <div v-if="errors && errors.firstname" class="text-danger">{{ errors.firstname[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="lastname" class="form-control" name="lastname" id="lastname" v-model="user.lastname" />
                                <div v-if="errors && errors.lastname" class="text-danger">{{ errors.lastname[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" v-model="user.email" />
                                <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="tel" class="form-control" name="phone" id="phone" v-model="user.phone" />
                                <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                            </div>
                             <div class="form-group">
                           <label for="role">User Role</label>
                              <select name="role" class="form-control" id="role" v-model="user.role">
                                 <option value="" disabled> --- Select Role ---</option>
                                 <option value="admin">Admin</option>
                                 <option value="sales">Sales</option>
                              </select>
                              <div v-if="errors && errors.role" class="text-danger">{{ errors.role[0] }}</div>
                           </div>
                            
                            
                            <input type="hidden" name="id" v-model="user.id">
                            <button type="submit" class="btn btn-success">Update</button>
                            <div v-if="success" class="alert alert-success mt-3">
                               Notes's Informations Updated Successfully
                            </div>
                        </form>
                        </div>
                     </div>
                  </section>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>
            
         </div>
      </div>

      <div  class="modal fade" id="myPasswordModal" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Change User Password</h4>
            </div>
            <div class="modal-body">
                  <section class="panel">
                     <div class="panel-body">
                        <div class="position-center">
                           <form @submit.prevent="PasswordChange">
                           <div class="form-group">
                              <label for="password">New Password</label>
                              <input type="text" class="form-control" name="password" id="password" v-model="fields.password" />
                              <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
                           </div>
                           
                            <input type="hidden" name="id" v-model="fields.id">
                           <button type="submit" class="btn btn-success sbt-btn">Change</button>
                           <br><br>
                           <div v-if="success" class="alert alert-success mt-3">
                              Password Changed Successfully
                           </div>
                        </form> 
                        </div>
                     </div>
                  </section>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>
            
         </div>
      </div>
      <!-- <modal v-if="showModal" name="edit-modal" :width="900"
            :height="600" :left="500" :top="0" :clickToClose="false" :pivotX="0.8">
            <div class="container">
                <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-danger pull-right" style="margin-top: 35px;" @click="$modal.hide('edit-modal')">Close Window</button>
                    <h1>Edit User Details</h1>
                    <div class="panel-body form-group form-group-sm">
                        <form>
                        <form @submit.prevent="Update">
                            <div class="form-group">
                                <label for="name">User Name</label>
                                <input type="template_title" class="form-control" name="name" id="name" v-model="user.name" />
                                <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input type="firstname" class="form-control" name="firstname" id="firstname" v-model="user.firstname" />
                                <div v-if="errors && errors.firstname" class="text-danger">{{ errors.firstname[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="lastname" class="form-control" name="lastname" id="lastname" v-model="user.lastname" />
                                <div v-if="errors && errors.lastname" class="text-danger">{{ errors.lastname[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" v-model="user.email" />
                                <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="tel" class="form-control" name="phone" id="phone" v-model="user.phone" />
                                <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                            </div>
                             <div class="form-group">
                           <label for="role">User Role</label>
                              <select name="role" class="form-control" id="role" v-model="user.role">
                                 <option value="" disabled> --- Select Role ---</option>
                                 <option value="admin">Admin</option>
                                 <option value="sales">Sales</option>
                              </select>
                              <div v-if="errors && errors.role" class="text-danger">{{ errors.role[0] }}</div>
                           </div>
                            
                            
                            <input type="hidden" name="id" v-model="user.id">
                            <button type="submit" class="btn btn-success">Update</button>
                            <div v-if="success" class="alert alert-success mt-3">
                               Notes's Informations Updated Successfully
                            </div>
                        </form>
                        </form>
                    </div>
                    
                </div>
                </div>
            </div>
        </modal> -->
         <!-- <modal v-if="showModal" name="password-modal" :width="900"
            :height="300" :left="300" :top="0" :clickToClose="false" :pivotX="0.8">
            <div class="container">
                <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-danger pull-right" style="margin-top: 35px;" @click="$modal.hide('password-modal')">Close Window</button>
                    <div class="panel-body form-group form-group-sm">
                      <h2 class="text-center">Change Password</h2>
                        <form @submit.prevent="PasswordChange">
                           <div class="form-group">
                              <label for="password">New Password</label>
                              <input type="text" class="form-control" name="password" id="password" v-model="fields.password" />
                              <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
                           </div>
                           
                            <input type="hidden" name="id" v-model="fields.id">
                           <button type="submit" class="btn btn-success sbt-btn">Change</button>
                           <br><br>
                           <div v-if="success" class="alert alert-success mt-3">
                              Password Changed Successfully
                           </div>
                        </form>
                       
                    </div>
                    
                </div>
                </div>
            </div>
        </modal> -->
   </div>
</template>
<script>
   export default {
       name: 'UserCreate',
       data() {
           return {
               fields: {},
               errors: {},
               success: false,
               loaded: true,
               emailSuccess: null,
               AddNew: null,
               users: [],
               queryField: 'ac_holder_name',
               query:'',
               SingleUserId : null,
                
                pagination: {
                     current_page: 1,
                 },
              user: {

                id : '',
                email : '',
                name : '',
                role: '',
                firstname: '',
                lastname: '',
                phone: '',
                
          },
          showModal : false,   
           }
       },
        watch:{
        query:function(newQuery, oldQuery){
   
           if(newQuery === ''){
              this.loadUsers();
           }else{
   
              this.SearchData();
           }
        }
     },
     created() {
       this.loadUsers();
     },
       methods: {
         loadUsers(){
   
         this.$Progress.start()
        axios.get('/api/v1/user-list?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response.data)
                this.users = response.data.data
                this.pagination = response.data.meta
                  this.showModal = true;
                 this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
       },   
           EmailCheck: function(e){
          let email = e.target.value;
           //console.log(e.target.value);
              axios.post('/api/v1/check-user/'+email) 
                 .then(response => {
                    console.log(response);
                    if(response.data.data == 'yes'){
                       this.emailSuccess = false;
                        $(".sbt-btn").attr("disabled", true);
                       //$(".sbt-btn").attr("disabled", true);
   
                    }else{
                       this.emailSuccess = true;
                      
                    }
                    
                 })
                 .catch(e => {
        
                    console.log(e)
                       this.$Progress.fail()
                 })
   
       },
       AddNewUser(){
   
           this.AddNew = true;
       },
       LoadPrevious(){
           this.AddNew = false;
       },
           submit() {
           if (this.loaded) {
               this.loaded = false;
               this.success = false;
               this.errors = {};
               axios.post('/api/v1/user-create', this.fields).then(response => {
                    this.fields = {}; //Clear input fields.
                   this.loaded = true;
                   this.success = true;
                   location.href= "/user-management";
               }).catch(error => {
                   this.loaded = true;
                   if (error.response.status === 200) {
                       this.errors = error.response.data.errors || {};
                   }
               });
            }
           },
       DeleteUser(id){

          var x = confirm("Are you sure you want to delete?");
            if (x){
               this.$Progress.start()
               axios.post('/api/v1/delete-user/'+id) 
               .then(response => {
                  alert("Deleted Successfully");
                   console.log(response)
                     this.$Progress.finish(); 
                    location.href= "/user-management";
               })
               .catch(e => {
      
                  console.log(e)
                     this.$Progress.fail()
               })
               }     
            else{

             return false;
            }

       },   
       EditUser(id){

           axios.get('/api/v1/edit-user/'+id) 
             .then(response => {
   
               //   this.showModal= true;
               //   this.$modal.show('edit-modal');
                   this.user.id = response.data.data.id
                   this.user.name = response.data.data.name
                   this.user.role = response.data.data.role
                   this.user.email = response.data.data.email
                   this.user.firstname = response.data.data.firstname
                   this.user.lastname = response.data.data.lastname
                   this.user.phone = response.data.data.phone
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
       },
        Update(){

           if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.post('/api/v1/update-users-info', this.user).then(response => {
                   console.log(response);
                    this.user = {}; //Clear input fields.
                    this.loaded = true;
                     this.success = true;
                    this.Reload = true;
                    location.href= "/user-management";
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 200) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }
          
       },
       ChangePassword(id){

            //this.SingleUserId = id;
            this.fields.id = id;
            //this.showModal= true;
           // this.$modal.show('password-modal');

       },
        PasswordChange(){

        if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.post('/api/v1/update-user-password', this.fields, this.profile).then(response => {
                   //console.log(response);
                  this.success= true;
                  setTimeout(function() {
                     location.reload();
                     }, 2000);
                  //  localStorage.removeItem('token');
                  //    localStorage.removeItem('user');
                  //    location.reload();
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 200) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }

      }
       },
       
       }
   
   
</script>