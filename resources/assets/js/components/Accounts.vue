<template>
   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
             <li><router-link :to="{ name: 'Accounts' }">Account Manager</router-link></li>
            <li class="breadcrumb-item" v-if="!AddNew"><a href="#">List of All Accounts</a></li>
            <li class="breadcrumb-item" v-if="AddNew"><a href="#" >Add New Account</a></li>
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
               <a class="btn btn-danger" title="+ Add New Account" href="#" v-on:click="AddNewAccount()" >+ Add New Account</a>
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
                           <option value="ac_holder_name">Account Holder Name</option>
                           <option value="ac_no">Account No.</option>
                           <option value="bank_name">Bank Name</option>
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
                     Account Holders List
                     <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                        <!-- <a href="javascript:;" class="fa fa-cog"></a> -->
                        <!-- <a href="javascript:;" class="fa fa-times"></a> -->
                     </span>
                  </header>
                  <div class="panel-body" style="display: block;">
                     <table class="table table-hover general-table">
                         <thead class="">
                     <tr>
                        <th scope="col">#</th>
                        <th scope="col">Account Number</th>
                        <th scope="col">A/C Type</th>
                        <th scope="col">Balance</th>
                        <th scope="col">Account Holder Name</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-show="accounts.length" v-for="(account, index) in accounts" :key="account.id">
                        <th scope="row">{{index + 1}}</th>
                         <td>{{ account.ac_no }}</td>
                         <td style="text-transform:uppercase">{{ account.ac_type }}</td>
                         <td>{{ account.opening_balance }}</td>
                        <td>{{ account.ac_holder_name }}</td>
                     </tr>
                     <tr v-show="!accounts.length" >
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
         <div class="row d-flex" v-if="AddNew">
            <div class="col-lg-12">
               <section class="panel">
                    <header class="panel-heading">
                            Accounts Create Form
                    </header>
                    <div class="panel-body">
                        <div class="position-center">
                        <form @submit.prevent="submit">
                            <div class="form-group">
                                <label for="ac_holder_name">Account Holder Name</label>
                                <input type="text" class="form-control" name="ac_holder_name" id="ac_holder_name" v-model="fields.ac_holder_name" />
                                <div v-if="errors && errors.ac_holder_name" class="text-danger">{{ errors.ac_holder_name[0] }}</div>
                            </div>

                            <!-- <div class="form-group">
                                <label for="bank_name">Bank</label>
                                <input type="bank_name" class="form-control" name="bank_name" id="bank_name" v-model="fields.bank_name" />
                                <div v-if="errors && errors.bank_name" class="text-danger">{{ errors.bank_name[0] }}</div>
                            </div> -->
                             <div class="form-group">
                                <label for="bank_name">Bank</label>
                                <select name="bank_id" class="form-control" id="bank_id" v-model="fields.bank_id">
                                       <option value=" " disabled> --- Select Bank ---</option>
                                        <option
                                          v-for="bank in banks"
                                          :key="bank.id"
                                          :value="bank.id"
                                       >{{ bank.bank_name }}</option>
                                       

                                    </select>
                                <div v-if="errors && errors.ac_type" class="text-danger">{{ errors.ac_type[0] }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="ac_no">Account Number</label>
                                <input type="text" class="form-control" name="ac_no" id="ac_no" v-model="fields.ac_no" />
                                <div v-if="errors && errors.ac_no" class="text-danger">{{ errors.ac_no[0] }}</div>
                            </div>
                            <div class="form-group">
                                <label for="ac_type">Account Type</label>
                                <select name="ac_type" class="form-control" id="ac_type" v-model="fields.ac_type">
                                       <option value=" " disabled> --- Select A/C type ---</option>
                                       <option value="bank">Bank</option>
                                       <option value="petty">Petty</option>

                                    </select>
                                <div v-if="errors && errors.ac_type" class="text-danger">{{ errors.ac_type[0] }}</div>
                            </div>

                            <div class="form-group">
                                <label for="opening_balance">Opening Balance</label>
                                <input type="text" class="form-control" name="opening_balance" id="opening_balance" v-model="fields.opening_balance" />
                                <div v-if="errors && errors.opening_balance" class="text-danger">{{ errors.opening_balance[0] }}</div>
                            </div>
                            
                            <button type="submit" class="btn btn-round btn btn-info">Submit</button>

                            <div v-if="success" class="alert alert-success mt-3">
                                Data Insertd Successfully,  <a href="#" v-on:click="loadAccountsPrevious()">Go Back To Account</a>
                            </div>
                        </form>
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
   </div>
</template>
<script type="text/ecmascript-6">
   export default {
     name: 'Accounts',
     data() {
       return {
         
         accounts: [],
         banks: [],
         query:'',
         AddNew: null,
         queryField: 'ac_holder_name',
         fields: {},
         errors: {},
         success: false,
         loaded: true,
         pagination: {
   
             current_page: 1,
         }
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
       this.BankList();
     },
     methods: {
      
       loadUsers(){
   
         this.$Progress.start()
        axios.get('/api/v1/account-list?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response)
                 this.accounts = response.data.data
                 this.pagination = response.data.meta
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
       },
       SearchData(){

           this.$Progress.start()
             axios.get('/api/v1/search-accounts/'+this.queryField+'/'+this.query+'/?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response)
                 this.accounts = response.data.data
                 this.pagination = response.data.meta
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })

       },
       AddNewAccount(){
         // this.$Progress.start()
          this.AddNew = true;
       },
       submit() {
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.post('/api/v1/account-create', this.fields).then(response => {
                    this.fields = {}; //Clear input fields.
                    this.loaded = true;
                    this.success = true;
                    location.href = '/account-manager';
                  //console.log(response);
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 200) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }
       },
       loadAccountsPrevious(){
           this.$Progress.start()
           this.AddNew = false;
           

       },
       BankList(){

          axios.get('/api/v1/list-of-all-banks') 
             .then(response => {
   
                 //console.log(response)
                 this.banks = response.data.data
                
                  
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })

       }
     },
     filters: {
       
     },
     
   }
   
</script>