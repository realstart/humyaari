<template>
   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#" v-on:click="loadPrevious()">Company Settings</a></li>
            <li class="breadcrumb-item" v-if="!AddNew"><a href="#">Companaies Basic Informations</a></li>
            <!-- <li class="breadcrumb-item" v-if="AddNew"><a href="#" >Add New Template</a></li> -->
         </ol>
      </nav>
     <div class="container-fluid">
        <div class="row">
            <div class='col-md-12'>
         <vue-progress-bar></vue-progress-bar>
         <!-- <div class="row" v-if="!AddNew">
            <div class="col-md-8" style="background-color:#fff;padding-bottom:30px;padding-left:20px !important">
                <h3 class=""><b>Basic Information:</b></h3> 
                <span v-for="(official_info, index) in official_infos" :key="official_info.id">
                      <p><span style="font-size:18px; font-weight:bold"> Company Logo:</span> <div class="zoom"><img style="width: 100px;" :src="'/uploads/logo/'+ official_info.company_logo" /></div></p>
                      <p><span style="font-size:18px; font-weight:bold"> Company Name:</span> <span style="font-size:18px">{{ official_info.company_name }}</span></p>
                      <p><span style="font-size:18px; font-weight:bold"> Company Address1:</span> <span style="font-size:18px">{{ official_info.company_address }} </span></p>
                      <p><span style="font-size:18px; font-weight:bold"> Company Address2:</span> <span style="font-size:18px">{{ official_info.company_address2 }}</span></p>
                      <p><span style="font-size:18px; font-weight:bold"> Phone 01:</span> <span style="font-size:18px">{{ official_info.company_phone_one }}</span></p>
                      <p><span style="font-size:18px; font-weight:bold"> Phone 02:</span> <span style="font-size:18px">{{ official_info.company_phone_two}}</span></p>
                      <p><span style="font-size:18px; font-weight:bold"> Company Email:</span> <span style="font-size:18px">{{ official_info.company_email }}</span></p>
                

                 <button type="button" v-on:click="Edit(official_info.id)" class="btn btn-success" data-toggle="modal" data-target="#myModal">Update Infos</button>
                </span>
            </div>
         </div> -->
          <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-12">
            <div class="well well-sm">
                <div class="row" v-for="(official_info, index) in official_infos" :key="official_info.id">
                    <div class="col-sm-6 col-md-4">
                        <img class="img-rounded img-responsive" :src="'/uploads/logo/'+ official_info.company_logo" />
                    </div>
                    <div class="col-sm-6 col-md-8">
                       
                            <p><span style="font-size:18px; font-weight:bold">Company Name:</span> <br><span style="font-size:18px">{{ official_info.company_name }}</span></p>
                            <p><span style="font-size:18px; font-weight:bold"> Company Name:</span> <br><span style="font-size:18px">{{ official_info.company_name }}</span></p>
                            <p><span style="font-size:18px; font-weight:bold"> Company Address1:</span> <br><span style="font-size:18px">{{ official_info.company_address }} </span></p>
                            <p><span style="font-size:18px; font-weight:bold"> Company Address2:</span> <br><span style="font-size:18px">{{ official_info.company_address2 }}</span></p>
                            <p><span style="font-size:18px; font-weight:bold"> Phone 01:</span> <br><span style="font-size:18px">{{ official_info.company_phone_one }}</span></p>
                            <p><span style="font-size:18px; font-weight:bold"> Phone 02:</span> <br><span style="font-size:18px">{{ official_info.company_phone_two}}</span></p>
                            <p><span style="font-size:18px; font-weight:bold"> Company Email:</span> <br><span style="font-size:18px">{{ official_info.company_email }}</span></p>
                        </div>
                        
                    </div>
                    <button type="button" v-on:click="Edit(official_info.id)" class="btn btn-success" data-toggle="modal" data-target="#myModal">Update Infos</button>
                </div>
            </div>
        </div>
    </div>
      </div>
           <div  class="modal fade" id="myModal" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title"> Update Company Infos</h4>
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
                                <label for="company_name">Company Name</label>
                                <input type="text" class="form-control" name="company_name" id="company_name" v-model="company.name" required/>
                                <div v-if="errors && errors.company_name" class="text-danger">{{ errors.company_name[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="company_address">Address1</label>
                                <input type="text" class="form-control" name="company_address" id="company_address" v-model="company.address" required/>
                                <div v-if="errors && errors.company_address" class="text-danger">{{ errors.company_address[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="company_address2">Address2</label>
                                <input type="text" class="form-control" name="company_address2" id="company_address2" v-model="company.address2" required/>
                                <div v-if="errors && errors.company_address2" class="text-danger">{{ errors.company_address2[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="company_phone_one">Phone 1</label>
                                <input type="text" class="form-control" name="company_phone_one" id="company_phone_one" v-model="company.phone1" required/>
                                <div v-if="errors && errors.company_phone_one" class="text-danger">{{ errors.company_phone_one[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="company_phone_two">Phone 2</label>
                                <input type="text" class="form-control" name="company_phone_two" id="company_phone_two" v-model="company.phone2" required/>
                                <div v-if="errors && errors.company_phone_two" class="text-danger">{{ errors.company_phone_two[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="company_email">Email</label>
                                <input type="text" class="form-control" name="company_email" id="company_email" v-model="company.email" required/>
                                <div v-if="errors && errors.company_email" class="text-danger">{{ errors.company_email[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="company_logo">
                                   <input ref="company_logo" id="company_logo" name="company_logo" class="form-control" type="file" accept="image/*">
                                </label>
                            </div>
                            <input type="hidden" name="id" v-model="company.id">
                            <button type="submit" class="btn btn-success">Update</button>
                            <div v-if="success" class="alert alert-success mt-3">
                            Company Informations Updated Successfully
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
     </div>
   </div>
</template>
<script type="text/ecmascript-6">
   export default {
     name: 'CompanyInfo',
     data() {
       return {
         
         official_infos: [],
         showModal: false,
         AddNew: false,
         Reload: false,
         fields: {},
         errors: {},
         success: false,
         loaded: true,  
         company: {

                name:'',
                address : '',
                address2 : '',
                phone1: '',
                phone2: '',
                email : '',
                id : ''
         },       
         
       }
     },
  
     created() {
       this.loadUsers();
      
     },
     methods: {
        
       loadUsers(){
   
         this.$Progress.start()
        axios.get('/api/v1/companny-info') 
             .then(response => {
   
                 console.log(response.data)
                 this.official_infos = response.data.data
                   this.showModal = true;
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

                Object.keys(this.company).forEach((key) => {
                    bodyFormData.append(key, this.company[key]);
                });
                let imageFile = this.$refs.company_logo.files[0];
                bodyFormData.append('company_logo', imageFile);

                axios({
                    method: 'post',
                    url: '/api/v1/update-company-info',
                    data: bodyFormData,
                    config: { headers: {'Content-Type': 'multipart/form-data' }}
                })

                .then((response) => {

                        this.company = {}; //Clear input fields.
                        this.loaded = true;
                        this.success = true;
                        location.href= "/company-settings";
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
       Edit(id){
           //alert(id);
           axios.get('/api/v1/edit-company-info/'+id) 
             .then(response => {
                 this.showModal = true;
                 //console.log(response.data.data[0].company_name)
                this.$modal.show('edit-modal');
                this.company.name = response.data.data[0].company_name
                this.company.address = response.data.data[0].company_address
                this.company.address2 = response.data.data[0].company_address2
                this.company.phone1 = response.data.data[0].company_phone_one
                this.company.phone2 = response.data.data[0].company_phone_two
                this.company.email = response.data.data[0].company_email
                this.company.id = response.data.data[0].id
              
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
       },
       GetCompanyLogo(){

          return "storage/app/logo/"+this.photo;
       },
     },
     filters: {
       
     },
     
     
   }
   
</script>
<style scoped>
 img{
    border: 1px solid;
 }
.zoom {
  padding: 50px;
  transition: transform .2s; /* Animation */
  width: 200px;
  height: 200px;
  margin: 0 auto;
}

.zoom:hover {
  transform: scale(2.0); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
}
</style>
