<style >
    .widget-user-header{
        background-position : center center;
        background-size: cover;
    }
</style>
<template>
    <div class="container">
        <div class="row ">
            <div class="col-md-8">
            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header text-white" style="background: url('./images/photo1.png') center center;">
                <h3 class="widget-user-username text-right">{{this.form.name}}</h3>
                <h5 class="widget-user-desc text-right">{{this.form.type}}</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">3,200</h5>
                      <span class="description-text">SALES</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">13,000</h5>
                      <span class="description-text">FOLLOWERS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">35</h5>
                      <span class="description-text">PRODUCTS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link " href="#timeline" data-toggle="tab">Timeline</a></li>
                  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
            </div>
            <!-- /.widget-user -->
            <div class="tab-content" >
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="timeline">
                    <!-- The timeline -->
                    <div class="timeline timeline-inverse">
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-danger">
                          10 Feb. 2014
                        </span>
                      </div>
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-envelope bg-primary"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> 12:05</span>

                          <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                          <div class="timeline-body">
                            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                            weebly ning heekya handango imeem plugg dopplr jibjab, movity
                            jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                            quora plaxo ideeli hulu weebly balihoo...
                          </div>
                          <div class="timeline-footer">
                            <a href="#" class="btn btn-primary btn-sm">Read more</a>
                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <div>
                        <i class="far fa-clock bg-gray"></i>
                      </div>
                    </div>
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane active" id="settings">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-12 col-form-label">Name</label>
                            <div class="col-sm-10">
                            <input type="email" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Name">
                            <has-error :form="form" field="name"></has-error>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail" class="col-sm-12 col-form-label">Email</label>
                            <div class="col-sm-10">
                            <input type="email" v-model="form.email" class="form-control"  :class="{ 'is-invalid': form.errors.has('email') }" placeholder="Email">
                            <has-error :form="form" field="email"></has-error>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputExperience" class="col-sm-12 col-form-label">Experience </label>
                            <div class="col-sm-10">
                            <textarea class="form-control" v-model="form.bio" :class="{ 'is-invalid': form.errors.has('bio') }"  placeholder="Experience"></textarea>
                            <has-error :form="form" field="bio"></has-error>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="photo" class="col-sm-4 col-form-label">Profile Photo</label>
                            <div class="col-sm-6">
                            <input type="file" accept="image/*" @change="updateProfile" class="form-input" />

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputExperience" class="col-sm-12 col-form-label">Password(leave empty if not changing)</label>
                            <div class="col-sm-10">
                            <input class="form-control" v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" type="password" placeholder="Password"/>
                            <has-error :form="form" field="password"></has-error>
                            </div>
                        </div>
                      <div class="form-group row">
                        <div class="offset-sm-12 col-sm-10">
                          <button type="submit" @click.prevent="updateInfo" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{

                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    type: '',
                    bio: '',
                    photo: '',
                    password: ''
                })
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods:{

            getProfilePhoto(){
                //grab the current photo picked
                let photo = (this.form.photo.length > 200) ? this.form.photo : "images/profile/"+ this.form.photo ;
                return photo;

            },
            updateInfo(){
                this.$Progress.start();
                this.form.put('api/profile/')
                .then(() =>{
                    Fire.$emit('AfterCreate');
                    this.$Progress.finish();
                })
                .catch(() =>{
                    this.$Progress.fail();
                })
            },
            updateProfile(e){
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

            }
        },
        created(){
            axios.get("api/profile")
            .then(({ data }) => (this.form.fill(data)));
        }
    }
</script>
