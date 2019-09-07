<template>
   <div>
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#" v-on:click="loadIncomesPrevious()">Note Manager</a></li>
            <li class="breadcrumb-item" v-if="!AddNew"><a href="#">List of All Notes</a></li>
            <li class="breadcrumb-item" v-if="AddNew"><a href="#">Add New Note</a></li>
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
               <a class="btn btn-danger" title="Click Here to add new income data" href="#" v-on:click="AddNewNote()" >+ Add New Note</a>
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
                           <option value="note_title">Title</option>
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
                     Notes
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
                        <th scope="col">Date</th>
                        <th scope="col">Ttile</th>
                        <th scope="col">Details</th>
                        <th v-if="user.role=='admin'" scope="col">Settings</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-show="notes.length" v-for="(note, index) in notes" :key="note.id">
                        <th scope="row">{{index + 1}}</th>
                         <td>{{ note.note_date }}</td>
                         <td>{{ note.note_title }}</td>
                        <td>{{ note.note_details }}</td>
                        <td v-if="user.role=='admin'">
                           <!-- <a href="" class="btn btn-info" title="View Note"><i class="fa fa-files-o"></i> View</a> -->
                           <!-- <a  href="javascript:;" v-on:click="EditNotes(note.id)" class="btn btn-success" title="Edit"><i class="fa fa-edit"></i></a> -->

                            <button v-if="user.role == 'admin'" type="button" v-on:click="EditNotes(note.id)" class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i></button>


                           <a  href="javascript:;" v-on:click="DeleteNotes(note.id)" class="btn btn-danger" title="Delete"><i class="fa fa-trash-o"></i></a>
                        </td>
                     </tr>
                     <tr v-show="!notes.length">
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
                     New Note Details
                  </header>
                  <div class="panel-body">
                     <div class="position-center">
                        <form @submit.prevent="submit">
                            <div class="form-group" data-date-format="yyyy/mm/dd">
                                <label for="note_date">Date</label>
                                <!-- <input type="date" class="form-control" name="income_date" id="income_date" v-model="fields.income_date" /> -->
                                
                                    <input type="date" class="form-control" name="note_date" id="note_date" v-model="fields.note_date">
                     
                                <div v-if="errors && errors.note_date" class="text-danger">{{ errors.note_date[0] }}</div>
                            </div>

                            <div class="form-group">
                                <label for="note_title">Notes Title</label>
                                <input type="note_title" class="form-control" name="note_title" id="note_title" v-model="fields.note_title" />
                                <div v-if="errors && errors.note_title" class="text-danger">{{ errors.note_title[0] }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="note_details">Note Detials</label>
                                <textarea name="note_details"  class="form-control" id="note_details" v-model="fields.note_details" cols="30" rows="10"></textarea>
                                <div v-if="errors && errors.income_note" class="text-danger">{{ errors.income_note[0] }}</div>
                            </div>
                           
                            <button type="submit" class="btn btn-round btn btn-info">Add Note</button>
                           
                            <div v-if="success" class="alert alert-success mt-6" id="success-box">
                                Note Created Successfully
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
      <!-- <modal v-if="showModal" name="edit-modal" :width="900"
            :height="600" :left="500" :top="0" :clickToClose="false" :pivotX="0.8">
            <div class="container">
                <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-danger pull-right" style="margin-top: 35px;" @click="$modal.hide('edit-modal')">Close Window</button>
                    <h1>Edit Note Details</h1>
                    <div class="panel-body form-group form-group-sm">
                        <form>
                        <form @submit.prevent="Update">
                             <div class="form-group" data-date-format="yyyy/mm/dd">
                                <label for="note_date">Date</label>
                              
                                    <input type="date" class="form-control" name="note_date" id="note_date" v-model="note.note_date">
                     
                                <div v-if="errors && errors.note_date" class="text-danger">{{ errors.note_date[0] }}</div>
                            </div>

                            <div class="form-group">
                                <label for="note_title">Notes Title</label>
                                <input type="note_title" class="form-control" name="note_title" id="note_title" v-model="note.note_title" />
                                <div v-if="errors && errors.note_title" class="text-danger">{{ errors.note_title[0] }}</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="note_details">Note Detials</label>
                                <textarea name="note_details"  class="form-control" id="note_details" v-model="note.note_details" cols="30" rows="10"></textarea>
                                <div v-if="errors && errors.note_details" class="text-danger">{{ errors.note_details[0] }}</div>
                            </div>
                            <input type="hidden" name="id" v-model="note.id">
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
               <h4 class="modal-title"> Edit Note Details</h4>
            </div>
            <div class="modal-body">
                  <section class="panel">
                     <!-- <header class="panel-heading">
                        Edit Customer Details
                     </header> -->
                     <div class="panel-body">
                        <div class="position-center">
                             <form @submit.prevent="Update">
                                 <div class="form-group" data-date-format="yyyy/mm/dd">
                                    <label for="note_date">Date</label>
                                    
                                          <input type="date" class="form-control" name="note_date" id="note_date" v-model="note.note_date">
                           
                                    <div v-if="errors && errors.note_date" class="text-danger">{{ errors.note_date[0] }}</div>
                                 </div>

                                 <div class="form-group">
                                    <label for="note_title">Notes Title</label>
                                    <input type="note_title" class="form-control" name="note_title" id="note_title" v-model="note.note_title" />
                                    <div v-if="errors && errors.note_title" class="text-danger">{{ errors.note_title[0] }}</div>
                                 </div>
                                 
                                 <div class="form-group">
                                    <label for="note_details">Note Detials</label>
                                    <textarea name="note_details"  class="form-control" id="note_details" v-model="note.note_details" cols="30" rows="10"></textarea>
                                    <div v-if="errors && errors.note_details" class="text-danger">{{ errors.note_details[0] }}</div>
                                 </div>
                                 <input type="hidden" name="id" v-model="note.id">
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
     name: 'NoteManager',
     data() {
       return {
         
         notes: [],
         query:'',
         AddNew: null,
         queryField: 'note_title',
         fields: {},
         errors: {},
         success: false,
         loaded: true,
         pagination: {
   
             current_page: 1,
         },
         note: {

                note_date:'',
                note_title : '',
                note_details : '',
                id : '',
                
         },
           showModal: false,
           user: JSON.parse(localStorage.getItem('user')),
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
        axios.get('/api/v1/note-list?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response)
                 this.notes = response.data.data
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
             axios.get('/api/v1/search-notes/'+this.queryField+'/'+this.query+'/?page='+this.pagination.current_page) 
             .then(response => {
   
                 //console.log(response)
                 this.notes = response.data.data
                 this.pagination = response.data.meta
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })

       },
       AddNewNote(){
         // this.$Progress.start()
          this.AddNew = true;
       },
       submit() {
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.post('/api/v1/note-create', this.fields).then(response => {
                    this.fields = {}; //Clear input fields.
                    this.loaded = true;
                    this.success = true;
                    location.href="/note-manager";
                   
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
       EditNotes(id){
          
          //this.$Progress.start()
               axios.get('/api/v1/edit-note/'+id) 
               .then(response => {
                  
               this.showModal = true;
               //  this.$modal.show('edit-modal');
                this.note.note_date = response.data.data.note_date
                this.note.note_title = response.data.data.note_title
                this.note.note_details = response.data.data.note_details
                this.note.id = response.data.data.id
                  
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
                axios.post('/api/v1/update-note-info', this.note).then(response => {
                   console.log(response);
                    this.company = {}; //Clear input fields.
                    this.loaded = true;
                     this.success = true;
                    this.Reload = true;
                    location.href= "/note-manager";
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 200) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }
          
       },
       DeleteNotes(id){
          var x = confirm("Are you sure you want to delete?");
            if (x){
               this.$Progress.start()
               axios.get('/api/v1/delete-note/'+id) 
               .then(response => {
                  alert("Deleted Successfully");
                  // console.log(response)
                     this.$Progress.finish(); 
                    location.href= "/note-manager";
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
     },
     filters: {
       
     },
     
   }
   
</script>