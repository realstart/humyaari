<template>
   <div>
       
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#" v-on:click="loadPrevious()">SMS Manager</a></li>
            <li class="breadcrumb-item" v-if="!AddNew"><a href="#">List of All Templates</a></li>
            <li class="breadcrumb-item" v-if="AddNew"><a href="#" >Add New Template</a></li>
         </ol>
      </nav>
      <!-- <div class="text-center">
         <pagination v-if="pagination.last_page > 1"
         :pagination="pagination"
         :offset="5"
         @paginate="loadUsers()"     
         ></pagination>
         </div> -->
      <div class=''>
         <vue-progress-bar></vue-progress-bar>
         <div class="row" v-if="!AddNew">
            <div class="col-md-12">
               <a class="btn btn-danger" title="+ Add New Template" href="#" v-on:click="AddNewTemplate()" >+ Add New Template</a>
            </div>
         </div>
         <br>
         <div class="row" v-if="!AddNew">
            <div class="col-md-12">
               <div class="mb-3" style="margin-bottom: 15px">
                  <div class="row">
                     <div class="col-md-2">
                        <strong>Search By</strong>
                     </div>
                     <div class="col-md-2">
                        <select v-model="queryField" class="form-control" name="" id="fileds">
                           <option value="template_serial">SMS Serial</option>
                           <option value="template_category">SMS Category</option>
                           <option value="template_status">Status</option>
                           <option value="template_title">Title</option>
                        </select>
                     </div>
                     <div class="col-md-5">
                        <input v-model="query" class="form-control" type="text" placeholder="Enter Some Text">
                     </div>
                     
                  </div>
               </div>
            </div>
             <div class="col-md-12 table-responsive">
               <section class="panel">
                  <header class="panel-heading">
                     SMS Templates
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
                        <th scope="col">SMS ID</th>
                        <th scope="col">SMS Type</th>
                        <th scope="col">Status</th>
                        <th scope="col">Title</th>
                        <th scope="col">Body</th>
                        <th scope="col">Created At</th>
                        <th scope="col" v-if="user.role=='admin'">Settings</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-show="sms_list.length" v-for="(sms, index) in sms_list" :key="sms.id">
                        <th scope="row">{{index + 1}}</th>
                         <td>{{ sms.template_serial }}</td>
                         <td>{{ sms.template_category }}</td>
                         <td style="text-transform:uppercase">{{ sms.template_status }}</td>
                        <td>{{ sms.template_title }}</td>
                        <td>{{ sms.template_body }}</td>
                        <td>{{ sms.created_at }}</td>
                        <td v-if="user.role=='admin'">
                           <!-- <a href="" class="btn btn-info" title="View Account"><i class="fa fa-files-o"></i> View</a> -->
                           <!-- <a href="javascript:;" v-on:click="EditTemplate(sms.id)" class="btn btn-success" title="Edit SMS Template"><i class="fa fa-edit"></i></a> -->

                            <button v-if="user.role == 'admin'" type="button" v-on:click="EditTemplate(sms.id)" class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i></button>

                           <a href="javascript:;" v-on:click="DeleteTemplate(sms.id)" class="btn btn-danger" title="Delete SMS Template"><i class="fa fa-trash-o"></i></a>
                        </td>
                     </tr>
                     <tr v-show="!sms_list.length" >
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
         <div class="row d-flex justify-content-center" v-if="AddNew">
            <div class="col-lg-12">
                <section class="panel">
                  <header class="panel-heading">
                    Create New SMS Template
                  </header>
                  <div class="panel-body">
                     <div class="">
                           <form @submit.prevent="submit" class="col-md-8">
                                 <div class="form-group">
                                    <label for="template_category">SMS Type</label>
                                    <select name="template_category" class="form-control" id="template_category" v-on:change="SMSType"  v-model="fields.template_category">
                                       <option value=""> --- Select type ---</option>
                                       <option value="due">For Due</option>
                                       <option value="paid">For Paid</option>
                                    </select>
                                    <div v-if="errors && errors.template_category" class="text-danger">{{ errors.template_category[0] }}</div>
                                 </div>
                                 <div class="form-group">
                                    <label for="template_status">SMS Status</label>
                                    <select name="template_status" class="form-control" id="template_status"  v-model="fields.template_status">
                                       <option value=""> --- Select Status ---</option>
                                       <option value="active">Active</option>
                                       <option value="deactive">Inactive</option>
                                    </select>
                                    <div v-if="errors && errors.template_category" class="text-danger">{{ errors.template_category[0] }}</div>
                                 </div>
                                 <div class="form-group">
                                    <label for="template_title">Template Title</label>
                                    <input type="text" class="form-control" name="template_title" id="template_title" v-model="fields.template_title" required/>
                                    <div v-if="errors && errors.template_title" class="text-danger">{{ errors.template_title[0] }}</div>
                                 </div>
                                 <div class="form-group">
                                    <label for="template_body">Template Body</label>
                                    <textarea cols="30" rows="10" class="form-control" name="template_body" id="template_body" v-model="fields.template_body"></textarea>
                                    <div v-if="errors && errors.template_body" class="text-danger">{{ errors.template_body[0] }}</div>
                                 </div>
                                 <button type="submit" class="btn btn-primary sbt-btn">Submit</button>
                                 <div v-if="success" class="alert alert-success mt-3">
                                    Template Created Successfully,  <a href="#" v-on:click="loadPrevious()">Go Back To Template List</a>
                                 </div>
                              </form>
                              <div class="col-md-4" style="border-left: 1px solid red;">
                                 <h3>SMS Demo Template For Due:</h3>
                                 <span>
                                 Dear {customer_name}, <br>
                                 Your due amount for Invoice {invoice_no}. Here is Tk. {amount}. <br>
                                 Thank you.
                                 </span>
                                 <h3>SMS Demo Template For Paid:</h3>
                                 <span>
                                 Dear {customer_name}, <br> 
                                 the amount {amount} for invoice no. {invoice_no} is paid <br>
                                 Thanks
                                 </span>
                              </div>
                    </div>
                  </div>
                </section>
            </div>
         <div>
   </div>
</div>
      </div>
      <div class="text-center" v-if="!AddNew">
         <pagination v-if="pagination.last_page > 1"
            :pagination="pagination"
            :offset="5"
            @paginate="query === '' ? loadUsers() : SearchData()"   
            ></pagination>
      </div>
   <!-- <modal v-if="showModal" name="edit-modal" :width="900"
            :height="600" :left="500" :top="0" :clickToClose="false" :pivotX="0.8">
            <div class="container">
                <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-danger pull-right" style="margin-top: 35px;" @click="$modal.hide('edit-modal')">Close Window</button>
                    <h1>Edit SMS Details</h1>
                    <div class="panel-body form-group form-group-sm">
                        <form>
                        <form @submit.prevent="Update">
                            <div class="form-group">
                                <label for="template_title">SMS Title</label>
                                <input type="template_title" class="form-control" name="template_title" id="template_title" v-model="template.template_title" />
                                <div v-if="errors && errors.template_title" class="text-danger">{{ errors.template_title[0] }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="template_body">SMS Body</label>
                                <textarea name="template_body"  class="form-control" id="template_body" v-model="template.template_body" cols="30" rows="10"></textarea>
                                <div v-if="errors && errors.template_body" class="text-danger">{{ errors.template_body[0] }}</div>
                            </div>
                            <input type="hidden" name="id" v-model="template.id">
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
       <div  class="modal fade" id="myModal" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title"> Edit SMS Template</h4>
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
                                    <label for="template_title">SMS Title</label>
                                    <input type="template_title" class="form-control" name="template_title" id="template_title" v-model="template.template_title" />
                                    <div v-if="errors && errors.template_title" class="text-danger">{{ errors.template_title[0] }}</div>
                                 </div>
                                 
                                 <div class="form-group">
                                    <label for="template_body">SMS Body</label>
                                    <textarea name="template_body"  class="form-control" id="template_body" v-model="template.template_body" cols="30" rows="10"></textarea>
                                    <div v-if="errors && errors.template_body" class="text-danger">{{ errors.template_body[0] }}</div>
                                 </div>
                                 <input type="hidden" name="id" v-model="template.id">
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
   </div>
</template>
<script type="text/ecmascript-6">
   export default {
     name: 'SMSTemplates',
     data() {
       return {
         
         sms_list: [],
         query:'',
         AddNew: null,
         queryField: 'template_serial',
         fields: {},
         errors: {},
         success: false,
         loaded: true,
         transfer:false,
         isLoading: false,
         TypeCheckTrue: null,
         
         pagination: {
   
             current_page: 1,
         },
         template: {

                template_title : '',
                template_body : '',
                id : ''
                
         },
          user: JSON.parse(localStorage.getItem('user')),
         showModal: false,
         
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
        axios.get('/api/v1/template-list?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response.data)
                this.sms_list = response.data.data
                this.pagination = response.data.meta
                  this.showModal = true;
                 this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
       },
       SearchData(){

           this.$Progress.start()
             axios.get('/api/v1/search-sms/'+this.queryField+'/'+this.query+'/?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response)
                 this.sms_list = response.data.data
                 this.pagination = response.data.meta
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })

       },
       submit() {
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.post('/api/v1/template-create', this.fields).then(response => {
                   console.log(response);
                    this.fields = {}; //Clear input fields.
                    this.loaded = true;
                    this.success = true;
                    location.href = '/sms-manager';
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 200) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }
       },
       loadPrevious(){
           this.$Progress.start()
           this.AddNew = false;
           

       },
       
        AddNewTemplate(){
         // this.$Progress.start()
          this.AddNew = true;
          //this.$parent.refresh();
          
         
       },
       EditTemplate(id){

           axios.get('/api/v1/edit-template/'+id) 
             .then(response => {
   
                 this.showModal= true;
                 this.$modal.show('edit-modal');
                   this.template.id = response.data.data.id
                   this.template.template_title = response.data.data.template_title
                   this.template.template_body = response.data.data.template_body
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
                axios.post('/api/v1/update-template-info', this.template).then(response => {
                   console.log(response);
                    this.company = {}; //Clear input fields.
                    this.loaded = true;
                     this.success = true;
                    this.Reload = true;
                    location.href= "sms-manager";
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 200) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }
          
       },
         DeleteTemplate(id){
          var x = confirm("Are you sure you want to delete?");
            if (x){
               this.$Progress.start()
               axios.post('/api/v1/delete-sms-template/'+id) 
               .then(response => {
                  alert("Deleted Successfully");
                   console.log(response)
                     this.$Progress.finish(); 
                    location.href= "/sms-manager";
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
       SMSType: function(e){
           let sms_type = e.target.value;
        
               axios.post('/api/v1/check-sms-exists/'+sms_type) 
                  .then(response => {
         
                     if(response.data.data == 'yes'){
                        alert("One sms already created for "+sms_type);
                        $(".sbt-btn").attr("disabled", true);

                     }else{

                        $(".sbt-btn").attr("disabled", false);
                     }
                     
                  })
                  .catch(e => {
         
                     console.log(e)
                        this.$Progress.fail()
                  })

        },
     },
     filters: {
       
     },
     
     
   }
   
</script>