<template>
   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item">
               <router-link :to="{ name: 'IncomeManager' }">Income Manager</router-link>
            </li>
            <!-- <li class="breadcrumb-item"><a href="#" v-on:click="loadIncomesPrevious()">Income Manager</a></li> -->
            <li class="breadcrumb-item" v-if="!AddNew"><a href="#">List of All Incomes</a></li>
            <li class="breadcrumb-item" v-if="AddNew"><a href="#">Add New Income</a></li>
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
               <a class="btn btn-danger" title="Click Here to add new income data" href="#" v-on:click="AddNewIncome()" >+ Add New Income Data</a>
               <!-- <button class="btn btn-primary">Link Account to sale</button> -->
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
                           <option value="income_note">Note Key Word</option>
                           <option value="income_date">A/C No.</option>
                           <option value="income_amount">Amount</option>
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
                     Income List
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
                              <th scope="col">Date</th>
                              <th scope="col">Account No</th>
                              <th scope="col">Amount</th>
                              <th scope="col">Note</th>
                              <th scope="col" v-if="user.role == 'admin'">Settings</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr v-show="incomes.length" v-for="(account, index) in incomes" :key="account.id">
                              <th scope="row">{{index + 1}}</th>
                              <td>{{ account.income_date }}</td>
                              <td>{{ account.account_no }}</td>
                              <td>{{ account.income_amount }}</td>
                              <td>{{ account.income_note }}</td>
                              <td>
                                 <a v-if="user.role == 'admin'" href="javascript:;" v-on:click="Delete(account.id)" class="btn btn-danger" title="Delete"><i class="fa fa-trash-o"></i></a>
                              </td>
                           </tr>
                           <tr v-show="!incomes.length">
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
                     New Income Details
                  </header>
                  <div class="panel-body">
                     <div class="position-center normal-border">
                        <form @submit.prevent="submit">
                           <div class="form-group" data-date-format="yyyy/mm/dd">
                              <label for="income_date" class="control-label col-lg-4">Date</label>
                              <div class="col-lg-8 date mdate m-b-20">                                 
                                 <input type="text"
                                    class="form-control"
                                    name="income_date"
                                    id="income_date"
                                    v-model="fields.income_date"
                                    data-date-format="yyyy/mm/dd"
                                    data-provide="datepicker"
                                    autocomplete="off"
                                    v-on:focusout="setDate('income_date', fields, 'income_date')"
                                    required>
                              </div>
                              <div v-if="errors && errors.income_date" class="text-danger">{{ errors.income_date[0] }}</div>
                           </div>
                           <div class="form-group ">
                              <label for="tr_ac" class="control-label col-lg-4">Account</label>
                              <div class="col-lg-8 m-b-20">                                 
                                 <select name="transaction_account" class="form-control" id="transaction_account" v-model="fields.transaction_account" required>
                                 <option value=" " disabled> --- Select type ---</option>
                                 <option
                                    v-for="vendor in account_numbers"
                                    :key="vendor.id"
                                    :value="vendor.ac_no"
                                    >{{ vendor.ac_no }}</option>
                                 </select>
                              </div>
                              <div v-if="errors && errors.transaction_account" class="text-danger">{{ errors.transaction_account[0] }}</div>
                           </div>
                           
                           <div class="form-group ">
                              <label for="income_amount" class="control-label col-lg-4">Amount</label>
                              <div class="col-lg-8 m-b-20">                                 
                                 <input type="income_amount" class="form-control" name="income_amount" id="income_amount" v-model="fields.income_amount"  required/>
                              </div>
                              <div v-if="errors && errors.income_amount" class="text-danger">{{ errors.income_amount[0] }}</div>
                           </div>
                           
                           <div class="form-group ">
                              <label for="income_amount" class="control-label col-lg-4">Note</label>
                              <div class="col-lg-8 m-b-20">                                
                                 <input type="text" class="form-control" name="income_note" id="income_note" v-model="fields.income_note" />
                              </div>
                              <div v-if="errors && errors.income_note" class="text-danger">{{ errors.income_note[0] }}</div>
                           </div>
                           <div class="form-group text-center">
                              <button type="submit" class="btn btn_submit">Submit</button>
                           </div>
                           
                           <div v-if="success" class="alert alert-success mt-6" id="success-box">
                              Data Insertd Successfully
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
   import helpers from '../mixins/helpers';
   export default {
     name: 'IncomeManager',
     data() {
       return {
         
         incomes: [],
         account_numbers:[],
         query:'',
         AddNew: null,
         queryField: 'income_amount',
         fields: {},
         errors: {},
         success: false,
         loaded: true,
         pagination: {
   
             current_page: 1,
         },
         user: JSON.parse(localStorage.getItem('user')),
       }
     },
     mixins: [helpers],
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
       this.Accouts();
     },
     methods: {
      
       loadUsers(){
   
         this.$Progress.start()
         axios.get('/api/v1/income-list?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response)
                 this.incomes = response.data.data
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
             axios.get('/api/v1/search-incomes/'+this.queryField+'/'+this.query+'/?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response)
                 this.incomes = response.data.data
                 this.pagination = response.data.meta
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
   
       },
       AddNewIncome(){
         // this.$Progress.start()
          this.AddNew = true;
       },
       submit() {
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.post('/api/v1/income-create', this.fields).then(response => {
                    this.fields = {}; //Clear input fields.
                    this.loaded = true;
                    this.success = true;
                    location.href= "/income-manager";
                  //console.log(response)
                   
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 200) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }
       },
       loadIncomesPrevious(){
           this.$Progress.start()
           this.AddNew = false;
           
   
       },
       Accouts(){
   
          axios.post('/api/v1/all-account-holder-list') 
               .then(response => {
      
                  console.log(response.data.data)
                  this.account_numbers = response.data.data;
                  
               })
               .catch(e => {
      
                  console.log(e)
                     this.$Progress.fail()
               })
   
       },
       Delete(id){
   
           var x = confirm("Are you sure you want to delete?");
            if (x){
               this.$Progress.start()
               axios.delete('/api/v1/delete-income/'+id) 
               .then(response => {
                   alert("Deleted Successfully");
                  // console.log(response)
                     this.$Progress.finish(); 
                    location.href= "/income-manager";
               })
               .catch(e => {
      
                  console.log(e)
                     this.$Progress.fail()
               })
               }     
            else{
   
             return false;
            }
       }
     },
     filters: {
       
     },
     
   }
   
</script>