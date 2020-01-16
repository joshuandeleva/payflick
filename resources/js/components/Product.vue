<template>
    <div class="container">
        <div class="row mt-5"  >
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Products Table</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Product Name</th>
                        <th>Product Code</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="product in products.data" :key="product.id">
                      <td>{{product.category_id}}</td>
                      <td>{{product.product_name}}</td>
                      <td>{{product.product_code}}</td>
                      <td>{{product.description}}</td>
                      <td>{{product.price}}</td>
                      <td>
                        <img class="img-fluid mb-3" :src="'./images/products/'+product.photo" style="width:70px;" alt="User Avatar">
                      </td>
                      <td>
                          <a href="#" @click="editModal(product)">
                              <i class="fa fa-edit text-blue"></i>
                          </a>
                          /
                           <a href="#" @click="deleteProduct(product.id)">
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
        <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="productModalLabel">Add New Product</h5>
                        <h5 class="modal-title" v-show="editmode" id="productModalLabel">Update Product Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent = "editmode ? updateProduct() :createProduct()">
                    <div class="modal-body">
                        <div class="form-group">
                            <input v-model="form.product_name" type="text" name="product_name" placeholder="Product Name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('product_name') }">
                            <has-error :form="form" field="product_name"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="form.product_code" type="text" name="product_code" placeholder="Product Code"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('product_code') }">
                            <has-error :form="form" field="product_code"></has-error>
                        </div>
                        <div class="form-group">
                            <div class="controls">
                                <select name="category_id" v-model="form.category_id" id="category_id" class="form-control" :class="{ 'is-invalid': form.errors.has('category_id') }">
                                    <option value="">Select Category </option>
                                    <option v-for="category in categories.data" :key="category.id " :value="category.id ">{{category.category_name}}</option>
                                </select>
                            </div>
                            <has-error :form="form" field="category_id"></has-error>
                        </div>
                        <div class="form-group">
                            <textarea v-model="form.description" type="text" name="description" placeholder="Short Info about the category"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                            <has-error :form="form" field="description"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="form.price" type="text" name="price" placeholder="Price"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('price') }">
                            <has-error :form="form" field="price"></has-error>
                        </div>
                          <div class="form-group row">
                            <label for="photo" class="col-sm-4 col-form-label">Profile Photo</label>
                            <div class="col-sm-6">
                                <input type="file" accept="image/*" @change="uploadphoto" class="form-input"/>
                            </div>
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
                //fetch products from db using axios
                products:{},
                //fetch categories from the db
                categories:{},
                // create a new form instance
                form: new Form({
                    id: '',
                    product_name: '',
                    product_code: '',
                    category_id: '',
                    description: '',
                    price: '',
                    photo: ''
                })
            }
        },
        methods: {
          // method to GET results from a Laravel endpoint
            getResults(page = 1) {
                axios.get('api/user?page=' + page)
                    .then(response => {
                        this.products = response.data;
                    });
            },
            updateProduct(){
                this.$Progress.start();
                this.form.put('api/products/'+this.form.id)
                .then(()=>{
                    //if successfull
                    Swal.fire(
                        'Updated Successfully!',
                        'Your Information has been Updated.',
                        'success'
                    )
                    this.$Progress.finish();
                    $('#productModal').modal('hide')
                    Fire.$emit('AfterCreate');
                })
                .catch(()=>{
                    //else throw an error
                    this.$Progress.fail();
                    swal("Failed!", "There was Something Wrong.", "Warning");
                });
            },
            editModal(product){
                this.editmode = true;
                $('#productModal').modal('show');
                this.form.fill(product);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#productModal').modal('show')
            },
            deleteProduct(id){
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
                            this.form.delete('api/products/'+id).then(()=>{
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
                axios.get("api/products").then(({ data }) => (this.products = data));
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
            createProduct(){
                // [Product.vue specific] When Product.vue is first loaded start the progress bar
                this.$Progress.start();
                this.form.post('api/products')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    //  [Product.vue specific] When Product.vue is finish loading finish the progress bar
                    $('#productModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Product Created Successfully'
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
                    this.products = data.data
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

