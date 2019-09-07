<template>
    <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
             <li class="breadcrumb-item"><router-link :to="{ name: 'CustomerList' }">Customer</router-link></li>
            <!-- <li class="breadcrumb-item"><a href="#">Customer</a></li> -->
            <li class="breadcrumb-item"><a href="#">Add New Customer</a></li>
         </ol>
      </nav>
      <div class="">
          <div class="row">
              <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Customer Create Form
                        </header>
                        <div class="panel-body">
                            <div class="position-center">
                            <form @submit.prevent="submit" >
                                <div class="form-group">
                                    <label for="name">Customer Name</label>
                                    <input type="text" class="form-control" name="name" id="name" v-model="fields.name" required/>
                                    <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                                 </div>
                                  <div class="form-group">
                                <label for="address1">Address </label>
                                <input type="text" class="form-control" name="address1" id="address1" v-model="fields.address1" required/>
                                <div v-if="errors && errors.address1" class="text-danger">{{ errors.address1[0] }}</div>
                            </div>

                            <div class="form-group">
                                <label for="address2">Address Line 2</label>
                                <input type="text" class="form-control" name="address2" id="address2" v-model="fields.address2" />
                                <div v-if="errors && errors.address2" class="text-danger">{{ errors.address2[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Contact Number</label>
                                <input type="text" class="form-control" name="phone" id="phone" v-model="fields.phone" required/>
                                <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" v-model="fields.email" required v-on:keyup="CheckEmailCustomer" />
                                <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                            </div>
                            <button type="submit" class="btn btn-round btn btn-info" id="createBtn">Create Customer</button>

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
        name: 'CustomerCreate',
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
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                if(!this.fields.address2){
                    this.fields.address2 = null;
                }
                axios.post('/api/v1/customer-create', this.fields).then(response => {
                    this.fields = {}; //Clear input fields.
                    this.loaded = true;
                    this.success = true;
                    location.href = '/customer-list';
                    console.log(response);
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 200) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }
            },
            CheckEmailCustomer:function(e){
            let email = e.target.value;
             axios.post('/api/v1/customer-email-check/'+email).then(response => {

                    if(response.data.data == true){

                        alert("THis email is already existed");
                         $("#createBtn").attr("disabled", true);
                    }
                    else{

                         $("#createBtn").attr("disabled", false);
                    }
                
                }).catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
                 })
           

            },

        },
        
        }
    
    
</script>
