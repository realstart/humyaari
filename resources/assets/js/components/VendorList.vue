<template>
   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Vendor</a></li>
            <li class="breadcrumb-item"><a href="#">List of All Vendors</a></li>
         </ol>
      </nav>
      <div class=''>
         <vue-progress-bar></vue-progress-bar>
         <div class="row" v-if="!AddNew">
            <div class="col-md-12">
               <router-link :to="{ name: 'VendorCreate' }" class="btn btn-danger">+ Add New Vendor</router-link >
            </div>
         </div>
         <br>
         <div class="row">
            <div class="col-md-12">
               <div class="mb-3" style="margin-bottom: 15px">
                  <div class="row">
                     <div class="col-md-2">
                        <strong>Search By</strong>
                     </div>
                     <div class="col-md-2">
                        <select v-model="queryField" class="form-control" name="" id="fileds">
                           <option value="">-- Select Option --</option>
                           <option value="name">Name</option>
                           <option value="phone">Phone</option>
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
                     Vendor List
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
                              <th scope="col">Logo</th>
                              <th scope="col">Name</th>
                              <th scope="col">Phone</th>
                              <th scope="col">Contact Person</th>
                              <th scope="col">Address</th>
                              <th scope="col">Email</th>
                              <th scope="col" v-if="user.role == 'admin'">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr v-show="vendors.length" v-for="(vendor, index) in vendors" :key="vendor.id">
                              <th scope="row">{{index + 1}}</th>
                              <td><img style="width: 50px;" :src="'/uploads/vendors/'+ vendor.logo" /></td>
                              <td>{{ vendor.name }}</td>
                              <td>{{ vendor.phone }}</td>
                              <td>{{ vendor.contact_person }}</td>
                              <td>{{ vendor.address }}</td>
                              <td>{{ vendor.email }}</td>
                              <td>
                                 <!-- <a v-if="user.role == 'admin'" href="#" class="btn btn-success" title="Edit" v-on:click="EditVendor(vendor.id)"><i class="fa fa-edit"></i></a> -->
                                  <button v-if="user.role == 'admin'" type="button" v-on:click="EditVendor(vendor.id)" class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i></button>
                                 <a v-if="user.role == 'admin'" href="#" class="btn btn-danger" title="Delete" v-on:click="DeleteVendor(vendor.id)"><i class="fa fa-trash-o"></i></a>
                              </td>
                           </tr>
                           <tr v-show="!vendors.length">
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
      </div>
      <div class="text-center">
         <pagination v-if="pagination.last_page > 1"
            :pagination="pagination"
            :offset="5"
            @paginate="query === '' ? loadUsers() : SearchData()"  
            ></pagination>
      </div>
      <!-- <modal v-if="showModal" name="edit-modal" :width="900"
         :height="650" :left="500" :top="0" :clickToClose="false" :pivotX="0.8">
         <div class="container">
            <div class="row">
               <div class="col-md-6">
                  <button class="btn btn-danger pull-right" style="margin-top: 35px;" @click="$modal.hide('edit-modal')">Close Window</button>
                  <h1>Edit Vendor Info</h1>
                  <div class="panel-body form-group form-group-sm">
                     <form @submit.prevent="submit" enctype="multipart/form-data">
                        <div class="form-group">
                           <label for="name">Name</label>
                           <input type="text" class="form-control" name="name" id="name" v-model="vendor.name" required/>
                           <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                        </div>
                        <div class="form-group">
                           <label for="address1">Address </label>
                           <input type="text" class="form-control" name="address1" id="address1" v-model="vendor.address" required/>
                           <div v-if="errors && errors.address" class="text-danger">{{ errors.address[0] }}</div>
                        </div>
                        <div class="form-group">
                           <label for="contact_person">Contact Person</label>
                           <input type="text" class="form-control" name="contact_person" id="contact_person" v-model="vendor.contact_person" required/>
                           <div v-if="errors && errors.contact_person" class="text-danger">{{ errors.contact_person[0] }}</div>
                        </div>
                        <div class="form-group">
                           <label for="phone">Phone </label>
                           <input type="text" class="form-control" name="phone" id="phone" v-model="vendor.phone" required/>
                           <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                        </div>
                        <div class="form-group">
                           <label for="email">Email</label>
                           <input type="text" class="form-control" name="email" id="email" v-model="vendor.email" required/>
                           <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                        </div>
                        <img style="width: 100px;" :src="'/uploads/vendors/'+ vendor.logo" />
                        <div class="form-group">
                           <label for="logo">
                           <input ref="logo" id="logo" name="logo" class="form-control" type="file" accept="image/*" >
                           </label>
                        </div>
                        <input type="hidden" name="hiddenLogo" v-model="vendor.hiddenLogo">
                        <input type="hidden" name="id" v-model="vendor.id">
                        <button type="submit" class="btn btn-success">Update</button>
                        <div v-if="success" class="alert alert-success mt-3">
                           vendor's Informations Updated Successfully
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </modal> -->
       <!-- Modal -->
      <div  class="modal fade" id="myModal" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title"> Edit Vendor Details</h4>
            </div>
            <div class="modal-body">
                  <section class="panel">
                     <!-- <header class="panel-heading">
                        Edit Customer Details
                     </header> -->
                     <div class="panel-body">
                        <div class="position-center">
                              <form @submit.prevent="submit" enctype="multipart/form-data">
                                 <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" v-model="vendor.name" required/>
                                    <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                                 </div>
                                 <div class="form-group">
                                    <label for="address1">Address </label>
                                    <input type="text" class="form-control" name="address1" id="address1" v-model="vendor.address" required/>
                                    <div v-if="errors && errors.address" class="text-danger">{{ errors.address[0] }}</div>
                                 </div>
                                 <div class="form-group">
                                    <label for="contact_person">Contact Person</label>
                                    <input type="text" class="form-control" name="contact_person" id="contact_person" v-model="vendor.contact_person" required/>
                                    <div v-if="errors && errors.contact_person" class="text-danger">{{ errors.contact_person[0] }}</div>
                                 </div>
                                 <div class="form-group">
                                    <label for="phone">Phone </label>
                                    <input type="text" class="form-control" name="phone" id="phone" v-model="vendor.phone" required/>
                                    <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                                 </div>
                                 <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" name="email" id="email" v-model="vendor.email" required/>
                                    <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                                 </div>
                                 <img style="width: 100px;" :src="'/uploads/vendors/'+ vendor.logo" />
                                 <div class="form-group">
                                    <label for="logo">
                                    <input ref="logo" id="logo" name="logo" class="form-control" type="file" accept="image/*" >
                                    </label>
                                 </div>
                                 <input type="hidden" name="hiddenLogo" v-model="vendor.hiddenLogo">
                                 <input type="hidden" name="id" v-model="vendor.id">
                                 <button type="submit" class="btn btn-success">Update</button>
                                 <div v-if="success" class="alert alert-success mt-3">
                                    vendor's Informations Updated Successfully
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
     name: 'list',
     data() {
       return {
         
         vendors: [],
         query:'',
         queryField: 'name',
         pagination: {
   
             current_page: 1,
         },
         Reload: false,
         fields: {},
         errors: {},
         success: false,
         loaded: true,  
         vendor: {
   
                name:'',
                address : '',
                company : '',
                email: '',
                phone: '',
                id : '',
                contact_person:'',
                hiddenLogo:''
         },
         showModal: false,
         user: JSON.parse(localStorage.getItem('user')),
         // hiddenLogo: '',
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
   
        //    axios.get("/api/v1/vendor-list").then(({ data }) => (this.users = data.data));
         this.$Progress.start()
        axios.get('/api/v1/vendor-list?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response)
                 this.vendors = response.data.data
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
             axios.get('/api/v1/search-vendor/'+this.queryField+'/'+this.query+'/?page='+this.pagination.current_page) 
             .then(response => {
   
                
                 this.vendors = response.data.data
                 this.pagination = response.data.meta
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
       },
       DeleteVendor(id){
          var x = confirm("Are you sure you want to delete?");
            if (x){
               this.$Progress.start()
               axios.get('/api/v1/soft-delete-vendor/'+id) 
               .then(response => {
                  alert("Deleted Successfully");
                  // console.log(response)
                     this.$Progress.finish(); 
                     location.href= "/vendor-list";
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
       EditVendor(id){
   
           this.$Progress.start()
               axios.get('/api/v1/edit-vendor/'+id) 
               .then(response => {
                  
                //this.showModal = true;
               // this.$modal.show('edit-modal');
                this.vendor.name = response.data.data.name
                this.vendor.address = response.data.data.address
                this.vendor.company = response.data.data.company
                this.vendor.contact_person = response.data.data.contact_person
                this.vendor.phone = response.data.data.phone
                this.vendor.email = response.data.data.email
                this.vendor.id = response.data.data.id
                this.vendor.logo = response.data.data.logo
                this.vendor.hiddenLogo = response.data.data.logo
                  
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
   
                var bodyFormData = new FormData();
   
                Object.keys(this.vendor).forEach((key) => {
                    bodyFormData.append(key, this.vendor[key]);
                });
                let imageFile = this.$refs.logo.files[0];
                bodyFormData.append('logo', imageFile);
   
                axios({
                    method: 'post',
                    url: '/api/v1/update-vendor-info',
                    data: bodyFormData,
                    config: { headers: {'Content-Type': 'multipart/form-data' }}
                })
   
                .then((response) => {
   
                        this.company = {}; //Clear input fields.
                        this.loaded = true;
                        this.success = true;
                       location.href = '/vendor-list';
                        //console.log(response)
                      
                    })
                    .catch(function (response) {
                        this.loaded = true;
   
                        if (error.response.status === 200) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
   
               
            }
       },
     },
     filters: {
       
     },
     
   }
   
</script>