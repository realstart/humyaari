<template>
   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:;" v-on:click="LoadPrevious()">Bank Management</a></li>
            <!-- <li class="breadcrumb-item"><a href="#">Customer</a></li> -->
            <li class="breadcrumb-item"><a href="#">Add New Bank</a></li>
         </ol>
      </nav>
      <div class="">
         <vue-progress-bar></vue-progress-bar>
         <div class="row" v-if="!AddNew">
            <div class="col-md-12">
               <a class="btn btn-danger" title="+ Add New Bank" href="#" v-on:click="AddNewBank()" >+ Add New Bank</a>
            </div>
         </div>
         <br>
         <div v-if="AddNew" class="row">
            <div class="col-lg-12">
               <section class="panel">
                  <header class="panel-heading">
                     Add New Bank
                  </header>
                  <div class="panel-body">
                     <div class="position-center">
                        <form @submit.prevent="submit" class="col-md-8">
                           <div class="form-group">
                              <label for="bank_name">Bank Name</label>
                              <input type="text" class="form-control" name="bank_name" id="bank_name" v-model="fields.bank_name" required/>
                              <div v-if="errors && errors.bank_name" class="text-danger">{{ errors.bank_name[0] }}</div>
                           </div>
                           <div class="form-group">
                              <label for="bank_details">Bank Detials</label>
                              <textarea name="bank_details"  class="form-control" id="bank_details" v-model="fields.bank_details" cols="30" rows="10"></textarea>
                              <div v-if="errors && errors.bank_details" class="text-danger">{{ errors.bank_details[0] }}</div>
                           </div>
                           <button type="submit" class="btn btn-round btn btn-info">Create</button>
                           <div v-if="success" class="alert alert-success mt-3">
                              New Bank Created Successfully
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
                     Bank List
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
                              <th scope="col">Bank Name</th>
                              <th scope="col">Bank Details</th>
                              <th scope="col">Settings</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr v-show="banks.length" v-for="(bank, index) in banks" :key="bank.id">
                              <th scope="row">{{index + 1}}</th>
                              <td>{{ bank.bank_name }}</td>
                              <td>{{ bank.bank_details }}</td>
                              <!-- <td>{{ bank.created_at | moment("MMMM Do YYYY") }}</td>
                                 <td>{{ bank.updated_at | moment("MMMM Do YYYY") }}</td> -->
                              <td>
                                 <!-- <a href="" class="btn btn-info" title="View Account"><i class="fa fa-files-o"></i> View</a> -->
                                 <!-- <a href="#" v-on:click="EditBank(bank.id)" class="btn btn-success" title="Edit"><i class="fa fa-edit"></i></a> -->

                                  <button type="button" v-on:click="EditBank(bank.id)" class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i></button>


                                 <a href="#"  v-on:click="DeleteBank(bank.id)" class="btn btn-danger" title="Delete"><i class="fa fa-trash-o"></i></a>
                              </td>
                           </tr>
                           <tr v-show="!banks.length" >
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
      <!-- <modal v-if="showModal" name="edit-modal" :width="900"
         :height="600" :left="500" :top="0" :clickToClose="false" :pivotX="0.8">
         <div class="container">
            <div class="row">
               <div class="col-md-6">
                  <button class="btn btn-danger pull-right" style="margin-top: 35px;" @click="$modal.hide('edit-modal')">Close Window</button>
                  <h1>Edit Bank Details</h1>
                  <div class="panel-body form-group form-group-sm">
                     <form>
                     <form @submit.prevent="Update">
                        <div class="form-group">
                           <label for="bank_name">Bank Name</label>
                           <input type="bank_name" class="form-control" name="bank_name" id="bank_name" v-model="bankd.bank_name" required/>
                           <div v-if="errors && errors.bank_name" class="text-danger">{{ errors.bank_name[0] }}</div>
                        </div>
                        <div class="form-group">
                           <label for="bank_details">Bank Detials</label>
                           <textarea name="bank_details"  class="form-control" id="bank_details" v-model="bankd.bank_details" cols="30" rows="10"></textarea>
                           <div v-if="errors && errors.bank_details" class="text-danger">{{ errors.bank_details[0] }}</div>
                        </div>
                        <input type="hidden" name="id" v-model="bankd.id">
                        <button type="submit" class="btn btn-success">Update</button>
                        <div v-if="success" class="alert alert-success mt-3">
                           Banks's Informations Updated Successfully
                        </div>
                     </form>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </modal> -->

      <div  class="modal fade" id="myModal" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Edit Bank Details</h4>
            </div>
            <div class="modal-body">
                  <section class="panel">
                     <div class="panel-body">
                        <div class="position-center">
                            <form @submit.prevent="Update">
                        <div class="form-group">
                           <label for="bank_name">Bank Name</label>
                           <input type="bank_name" class="form-control" name="bank_name" id="bank_name" v-model="bankd.bank_name" required/>
                           <div v-if="errors && errors.bank_name" class="text-danger">{{ errors.bank_name[0] }}</div>
                        </div>
                        <div class="form-group">
                           <label for="bank_details">Bank Detials</label>
                           <textarea name="bank_details"  class="form-control" id="bank_details" v-model="bankd.bank_details" cols="30" rows="10"></textarea>
                           <div v-if="errors && errors.bank_details" class="text-danger">{{ errors.bank_details[0] }}</div>
                        </div>
                        <input type="hidden" name="id" v-model="bankd.id">
                        <button type="submit" class="btn btn-success">Update</button>
                        <div v-if="success" class="alert alert-success mt-3">
                           Banks's Informations Updated Successfully
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
   </div>
</template>
<script>
   export default {
       name: 'BankManagement',
       data() {
           return {
               fields: {},
               errors: {},
               success: false,
               loaded: true,
               emailSuccess: null,
               AddNew: null,
               banks: [],
               queryField: 'ac_holder_name',
               query:'',
                
                pagination: {
                     current_page: 1,
                 },
              bankd: {
   
                id : '',
                bank_name : '',
                bank_details : '',
                
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
        axios.get('/api/v1/bank-list?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response.data)
                this.banks = response.data.data
                this.pagination = response.data.meta
                  this.showModal = true;
                 this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
       },   
      //      EmailCheck: function(e){
      //     let email = e.target.value;
      //      //console.log(e.target.value);
      //         axios.post('/api/v1/check-user/'+email) 
      //            .then(response => {
      //               console.log(response);
      //               if(response.data.data == 'yes'){
      //                  this.emailSuccess = false;
      //                   $(".sbt-btn").attr("disabled", true);
      //                  //$(".sbt-btn").attr("disabled", true);
   
      //               }else{
      //                  this.emailSuccess = true;
                      
      //               }
                    
      //            })
      //            .catch(e => {
        
      //               console.log(e)
      //                  this.$Progress.fail()
      //            })
   
      //  },
       AddNewBank(){
   
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
               axios.post('/api/v1/bank-create', this.fields).then(response => {
                    this.fields = {}; //Clear input fields.
                   this.loaded = true;
                   this.success = true;
                   location.href= "/bank-management";
                  //console.log(response)
               }).catch(error => {
                   this.loaded = true;
                   if (error.response.status === 200) {
                       this.errors = error.response.data.errors || {};
                   }
               });
            }
           },
       DeleteBank(id){
   
          var x = confirm("Are you sure you want to delete?");
            if (x){
               this.$Progress.start()
               axios.post('/api/v1/delete-bank/'+id) 
               .then(response => {
                  alert("Deleted Successfully");
                   console.log(response)
                     this.$Progress.finish(); 
                    location.href= "/bank-management";
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
       EditBank(id){
   
           axios.get('/api/v1/edit-bank/'+id) 
             .then(response => {
   
                 this.showModal= true;
                 this.$modal.show('edit-modal');
                  console.log(response)
                   this.bankd.id = response.data.data.id
                   this.bankd.bank_name = response.data.data.bank_name
                   this.bankd.bank_details = response.data.data.bank_details
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
                axios.post('/api/v1/update-bank-info', this.bankd).then(response => {
                   console.log(response);
                    this.bankd = {}; //Clear input fields.
                    this.loaded = true;
                     this.success = true;
                    this.Reload = true;
                    location.href= "/bank-management";
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 200) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }
          
       },
       },
       
       }
   
   
</script>