<template>
    <div class="container">
        <div class="row mt-5"  >
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Categories Table</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Category Name</th>
                      <th>Category Level</th>
                      <th>Description</th>
                      <th>Image</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="category in categories.data" :key="category.id">
                      <td>{{category.id}}</td>
                      <td>{{category.category_name}}</td>
                      <td>{{category.parent_id}}</td>
                      <td>{{category.description}}</td>
                      <td>
                        <img class="img-fluid mb-3" :src="'./images/categories/'+category.photo" style="width:70px;" alt="User Avatar">
                      </td>
                      <td>
                          <a href="#" @click="editModal(category)">
                              <i class="fa fa-edit text-blue"></i>
                          </a>
                          /
                           <a href="#" @click="deleteCategory(category.id)">
                              <i class="fa fa-trash text-red"></i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="card-footer">

            </div>
          </div>
        </div>
        <div v-if="!$gate.isAdminOrManager()">
            <not-found></not-found>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New Category</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Category Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent = "editmode ? updateCategory() :createCategory()">
                    <div class="modal-body">
                        <div class="form-group">
                            <input v-model="form.category_name" type="text" name="category_name" placeholder="Category Name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('category_name') }">
                            <has-error :form="form" field="category_name"></has-error>
                        </div>
                        <div class="form-group">
                            <select name="type" v-model="form.parent_id" id="parent_id" class="form-control"
                             :class="{ 'is-invalid': form.errors.has('parent_id') }">
                                <option value="">Select Category Level</option>
                                <option value="0">Main category</option>
                                <option v-for="category in categories.data" :key="category.id" :value="category.id ">
                                    {{category.category_name}}
                                </option>
                            </select>
                            <has-error :form="form" field="parent_id"></has-error>
                        </div>
                        <div class="form-group">
                            <textarea v-model="form.description" type="text" name="description" placeholder="Short Info about the category"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                            <has-error :form="form" field="description"></has-error>
                        </div>
                         <div class="form-group">
                            <input v-model="form.url" type="text" name="url" placeholder="Category Url"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('url') }">
                            <has-error :form="form" field="url"></has-error>
                        </div>
                        <div class="form-group row">
                            <label for="photo" class="col-sm-4 col-form-label">Profile Photo</label>
                            <div class="col-sm-6">
                                <input type="file" accept="image/*" @change="uploadphoto" class="form-input" />
                            </div>
                        </div>
                        <div class="form-check">
                            <input  v-model="form.status" type="checkbox" class="form-check-input" :class="{ 'is-invalid': form.errors.has('status') }" >
                            <label class="form-check-label" for="status">Enable Category</label>
                            <has-error :form="form" field="status"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create Category</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                //check if it's an edit function and switch to the modal
                editmode: false,
                //fetch categories from db using axios
                categories:{},

                // create a new form instance
                form: new Form({
                    id: '',
                    category_name: '',
                    parent_id: '',
                    description: '',
                    url: '',
                    photo: '',
                    status: ''
                })
            }
        },
        methods: {
          // method to GET results from a Laravel endpoint
            getResults(page = 1) {
                axios.get('api/user?page=' + page)
                    .then(response => {
                        this.categories = response.data;
                    });
            },
            updateCategory(){
                this.$Progress.start();
                this.form.put('api/categories/'+this.form.id)
                .then(()=>{
                    //if successfull
                    Swal.fire(
                        'Updated Successfully!',
                        'Your Information has been Updated.',
                        'success'
                    )
                    this.$Progress.finish();
                    $('#addNew').modal('hide')
                    Fire.$emit('AfterCreate');
                })
                .catch(()=>{
                    //else throw an error
                    this.$Progress.fail();
                    swal("Failed!", "There was Something Wrong.", "Warning");
                });
            },
            editModal(category){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(category);
            },
            newModal(){
                this.editmode = false;
                this.form.clear ()
                this.form.reset();
                $('#addNew').modal('show')
            },
            deleteCategory(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                        //send request to the server
                        if (result.value) {
                            this.form.delete('api/categories/'+id).then(()=>{
                                    Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                    )
                                Fire.$emit('AfterCreate');
                            }).catch(()=>{
                                swal("Failed!", "There was Something Wrong.", "Warning");
                            });
                        }
                    })
            },
            loadCategories(){
                axios.get("api/categories").then(({ data }) => (this.categories = data));
            },
            uploadphoto(e){
                //console.log('uploading');
                //grab the file we are uploading
                let file = e.target.files[0];
                console.log(file);
                //convert the file to base63
                let reader = new FileReader();
                if (file['size'] < 2111775) {
                    reader.onloadend = (file) =>{
                        //console.log('RESULT', reader.result);
                        this.form.photo = reader.result;
                    }
                    reader.readAsDataURL(file);
                }else{
                    Swal.fire("Oops...", "You are uploading a large file!", "error");

                }

            },
            createCategory(){
                // [Category.vue specific] When Category.vue is first loaded start the progress bar
                this.$Progress.start();
                this.form.post('api/categories')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    //  [Category.vue specific] When Category.vue is finish loading finish the progress bar
                    $('#addNew').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Category Created Successfully'
                    })
                    this.$Progress.finish();
                })
                .catch(()=>{
                    this.$Progress.fail();

                })
            }
        },
        created() {
            //listen to a search event and send an http request
            Fire.$on('searching', ()=>{
                //fetch data from the root instance of the application <app.js>
                let query = this.$parent.search;
                //use axios to query the db
                axios.get('api/findUser?q='+ query)
                .then((data)=>{
                    this.categories = data.data
                })
                .catch(()=>{

                })

            });
            this.loadCategories();
            /* this method sends http request every three seconds */
            //setInterval(() => this.loadCategories(), 3000);
            Fire.$on('AfterCreate', () =>{
                this.loadCategories();
            });
        }
    }
</script>

