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
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="well well-sm padding-20 company_setting">
                <div class="row" v-for="(official_info, index) in official_infos" :key="index">
                    <div class="col-sm-12 col-md-3">
                        <h4 style="color:#40509e;"><b>Change Logo</b></h4>
                            <div class="company_info_change">
                                <input type="hidden" ref="company_id" :value="official_info.id">
                                <img style="width:120px;height:75px;margin:15px auto;" class="img-rounded normal-border img-responsive" :src="'/uploads/logo/'+ official_info.company_logo" />
                                <label class="file_style" for="company_logo">
                                    <span>UPLOAD</span>
                                    <input ref="company_logo" id="company_logo" name="company_logo" class="disp_none" type="file" accept="image/*">
                                </label>
                                <button class="btn-company-submit btn-company-logo" @click="update_logo"> 
                                    <svg aria-hidden="true" style="width:15px;" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-check fa-w-16 fa-3x"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z" class=""></path></svg>
                                    <b>CHANGE LOGO</b>
                                </button>
                            </div>
                        <br>
                        <h4 style="color:#40509e;"><b>Change Favicon</b></h4>
                            <div class="company_info_change">
                                <img style="width:120px;height:75px;margin:15px auto;" class="img-rounded normal-border img-responsive" :src="'/uploads/logo/'+ official_info.company_logo" />
                                <label class="file_style" for="tg-photogallery">                                 <span>UPLOAD</span>
                                    <input id="tg-photogallery" class="disp_none" type="file" name="logo_img">
                                </label>
                                <button class="btn-company-submit">
                                    <svg aria-hidden="true" style="width:15px;" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-check fa-w-16 fa-3x"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z" class=""></path></svg>
                                    <b>CHANGE FAVICON</b>
                                </button>
                            </div>
                    </div>
                    
                    <div class="col-sm-12 col-md-9">   
                        <h4 style="float:left;color:#40509e"><b>General Settings View</b></h4>
                        <button type="button" v-on:click="Edit(official_info.id)" class="btn btn-edit" data-toggle="modal" data-target="#myModal" style="float:right;">
                            <svg style="height:15px;" aria-hidden="true" focusable="false" data-prefix="far" data-icon="edit" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-edit fa-w-18 fa-3x"><path fill="currentColor" d="M402.3 344.9l32-32c5-5 13.7-1.5 13.7 5.7V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h273.5c7.1 0 10.7 8.6 5.7 13.7l-32 32c-1.5 1.5-3.5 2.3-5.7 2.3H48v352h352V350.5c0-2.1.8-4.1 2.3-5.6zm156.6-201.8L296.3 405.7l-90.4 10c-26.2 2.9-48.5-19.2-45.6-45.6l10-90.4L432.9 17.1c22.9-22.9 59.9-22.9 82.7 0l43.2 43.2c22.9 22.9 22.9 60 .1 82.8zM460.1 174L402 115.9 216.2 301.8l-7.3 65.3 65.3-7.3L460.1 174zm64.8-79.7l-43.2-43.2c-4.1-4.1-10.8-4.1-14.8 0L436 82l58.1 58.1 30.9-30.9c4-4.2 4-10.8-.1-14.9z" class=""></path></svg>
                            Update Infos</button>
                        <div style="clear:both;"></div>
                        <div class="company_info_change" style="text-align:left;padding:30px;">
                            <div class="row m-t-10 border_bottom">
                                <div class="col-md-6">
                                    <p><span class="normal-font">Company Name:</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="normal-font">{{ official_info.company_name }}</span></p>
                                </div>
                            </div>
                            <div class="row m-t-10 border_bottom">
                                <div class="col-md-6">
                                    <p><span class="normal-font"> Company Address1:</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="normal-font">{{ official_info.company_address }}</span></p>
                                </div>
                            </div>
                            <div class="row m-t-10 border_bottom">
                                <div class="col-md-6">
                                    <p><span class="normal-font"> Company Address2:</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="normal-font">{{ official_info.company_address2 }}</span></p>
                                </div>
                            </div>
                            <div class="row m-t-10 border_bottom">
                                <div class="col-md-6">
                                    <p><span class="normal-font">Phone 01:</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="normal-font">{{ official_info.company_phone_one }}</span></p>
                                </div>
                            </div>
                            <div class="row m-t-10 border_bottom">
                                <div class="col-md-6">
                                    <p><span class="normal-font">Phone 02:</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="normal-font">{{ official_info.company_phone_two }}</span></p>
                                </div>
                            </div>
                            <div class="row m-t-10 border_bottom">
                                <div class="col-md-6">
                                    <p><span class="normal-font">Company Email:</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="normal-font">{{ official_info.company_email }}</span></p>
                                </div>
                            </div>
                        </div>                           
                                       
                        </div>
                        
                    </div>
                   
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
                            <!-- <div class="form-group">
                                <label for="company_logo">
                                   <input ref="company_logo" id="company_logo" name="company_logo" class="form-control" type="file" accept="image/*">
                                </label>
                            </div> -->
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

        update_logo() {

                var bodyFormData = new FormData();

                let imageFile = this.$refs.company_logo[0].files[0]
                let id = this.$refs.company_id[0].value
                bodyFormData.append('company_logo', imageFile);
                bodyFormData.append('id', id);

                axios({
                    method: 'post',
                    url: '/api/v1/update-company-logo',
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
@font-face{
        font-family: Poppins-Regular;
        src: url('/fonts/Poppins-Regular.ttf');
}
.company_setting{font-family: Poppins-Regular;}
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
.file_style {
    width: 100px;
    color: #363b4d;
    font-size: 14px;
    line-height: 17px;
    margin: 0 0 10px;
    cursor: pointer;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -ms-flex-line-pack: center;
    align-content: center;
    margin: auto;
    height: 30px;
    border-radius: 15px;
    background: linear-gradient(90deg, #7d32ff, #c438d9);
    color: white;
}
.disp_none {
    display: none;
}
.btn-company-submit{
    background: linear-gradient(90deg, #7d32ff, #c438d9);
    color: white;
    padding: 8px 15px;
    border-radius: 5px;
    border: 1px solid #7d32ff;
    margin-top:20px;
}
.company_info_change{
   text-align: center;
    padding: 15px;
    border: 1px solid #dedede;
    border-radius: 10px;
}
.btn-edit{
    background: linear-gradient(90deg, #7d32ff, #c438d9);
    border: 1px solid #7d32ff;
    border-radius: 20px;
    height: 30px;
    padding:0px 10px;
    color:white;
    font-weight: 600;
}
.m-t-10{margin-top:10px;}
.padding-20{padding:20px;}
.normal-border{border:1px solid #dedede;}
.border_bottom{border-bottom: 1px solid #dedede;}
.normal-font{font-size: 16px;}
</style>
