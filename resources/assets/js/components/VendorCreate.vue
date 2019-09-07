<template>
   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="#">Vendor</a></li> -->
            <li>
               <router-link :to="{ name: 'VendorList' }">Vendors</router-link>
            </li>
            <li class="breadcrumb-item"><a href="#">Add New Vendor</a></li>
         </ol>
      </nav>
      <div class="">
         <div class="row">
            <div class="col-lg-12">
              <section class="panel">
                  <header class="panel-heading">
                     Vendor Create Form
                  </header>
                  <div class="panel-body">
                     <div class="position-center">
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                           <div class="form-group">
                              <label for="name">Name</label>
                              <input type="text" class="form-control" name="name" id="name" v-model="fields.name" required/>
                              <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                           </div>
                           <!-- <div class="form-group">
                              <label for="company">Company</label>
                              <input type="text" class="form-control" name="company" id="company" v-model="fields.company" />
                              <div v-if="errors && errors.company" class="text-danger">{{ errors.company[0] }}</div>
                              </div> -->
                           <div class="form-group">
                              <label for="contact_person">Contact Person</label>
                              <input type="text" class="form-control" name="contact_person" id="contact_person" v-model="fields.contact_person" required/>
                              <div v-if="errors && errors.contact_person" class="text-danger">{{ errors.contact_person[0] }}</div>
                           </div>
                           <div class="form-group">
                              <label for="address">Address</label>
                              <input type="text" class="form-control" name="address" id="address" v-model="fields.address" />
                              <div v-if="errors && errors.address" class="text-danger">{{ errors.address[0] }}</div>
                           </div>
                           <div class="form-group">
                              <label for="email">Email</label>
                              <input type="email" class="form-control" name="email" id="email" v-model="fields.email" required/>
                              <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                           </div>
                           <div class="form-group">
                              <label for="phone">Phone</label>
                              <input type="phone" class="form-control" name="phone" id="phone" v-model="fields.phone" required/>
                              <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                           </div>
                           <div class="form-group">
                              <label for="logo">
                              <input ref="logo" id="logo" name="logo" class="form-control" type="file" accept="image/*" >
                              </label>
                           </div>
                           <button type="submit" class="btn btn-round btn btn-info">Submit</button>
                           <div v-if="success" class="alert alert-success mt-3">
                              Data Insertd Successfully
                           </div>
                        </form>
                     </div>
               </div>
              </section>
            </div>
         </div>
      </div>
   </div>
</template>
<script>
   export default {
       name: 'VendorCreate',
       data() {
           return {
               fields: {},
               errors: {},
               success: false,
               loaded: true,
           }
       },
       methods: {
           submit() {
           // if (this.loaded) {
           //     this.loaded = false;
           //     this.success = false;
           //     this.errors = {};
           //     axios.post('/api/v1/vendor-create', this.fields).then(response => {
           //         this.fields = {}; //Clear input fields.
           //         this.loaded = true;
           //         this.success = true;
           //          location.href = '/vendor-list';
           //     }).catch(error => {
           //         this.loaded = true;
           //         if (error.response.status === 200) {
           //             this.errors = error.response.data.errors || {};
           //         }
           //     });
           // }
            if (this.loaded) {
               this.loaded = false;
               this.success = false;
               this.errors = {};
   
               var bodyFormData = new FormData();
   
               Object.keys(this.fields).forEach((key) => {
                   bodyFormData.append(key, this.fields[key]);
               });
               let imageFile = this.$refs.logo.files[0];
               bodyFormData.append('logo', imageFile);
   
               axios({
                   method: 'post',
                   url: '/api/v1/vendor-create',
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
       
       }
   
   
</script>