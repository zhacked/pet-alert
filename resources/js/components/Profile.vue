
<style>
.widget-user-header{
    background-position: center center;
    background-size: cover;
    height: 250px !important;
}
.widget-user .card-footer{
    padding: 0;
}
</style>


<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3">
                
                <div class="card card-widget widget-user">
                <div class="widget-user-header text-bold text-white" style="background-image:url('./image/bgpets.jpg')">
                    <h1 class="widget-user-username text-dark" 
                        style="
                            font-family: Aclonica, sans-serif;
                            text-transform: uppercase;
                            text-align: center;
                            font-size: 35px;
                            color: rgb(0, 41, 41);
                            text-shadow: rgb(254, 255, 250) 3px 4px 8px;
                        ">{{this.form.name}}</h1>
                    <h5 class="widget-user-desc"
                        style="
                            font-family: Aclonica, sans-serif;
                            text-transform: uppercase;
                            text-align: center;
                            font-size: 15px;
                            color: rgb(0, 41, 41);
                            text-shadow: rgb(254, 255, 250) 3px 4px 8px;
                        ">{{this.form.type}}</h5>
                </div>

                <div class="widget-user-image">
                       <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
                </div>
                <div class="card-footer" >
                    <!-- <div class="row">
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                        <h5 class="description-header">{{import_count}}</h5>
                        <span class="description-text">On Going</span>
                        </div>
                    </div>
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                        <h5 class="description-header">{{arena}}</h5>
                        <span class="description-text">Arena</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="description-block">
                        <h5 class="description-header">{{user}}</h5>
                        <span class="description-text">Users</span>
                        </div>
                    </div>
                    </div> -->
                </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                      
                        <li class="nav-item"><a class="nav-link active show" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div>
                    <div class="card-body" style="background-color: #BFBFBF;">
                        <div class="tab-content">
                            <div class="tab-pane  w-100" id="activity">
                                    <v-img
                                        class="mx-auto"
                                        lazy-src="https://picsum.photos/id/11/10/6"
                                        max-height="500"
                                        max-width="2500"
                                        :src="getcomming()"
                                    ></v-img>
                            </div>

                            <div class="tab-pane active show" id="settings">
                                <div class="form-group">
                                    <label for="photo" class="profile"> Change Profile Photo</label>
                                    <div class="col-sm-12">

                                        <label class="addphoto"> Add Photo <i class="fas fa-plus fa-fw"></i>
                                            <input type="file" size="60" @change="updateProfile" name="photo" class="form-input" >
                                        </label> 
                                       
                                    </div>
                                </div>
                                <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="inputName" class="profile">Name</label>

                                    <div class="col-sm-12 pass">
                                    <input  v-model="form.name" class="form-control" id="inputName" placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }" >
                                     <has-error :form="form" field="name"></has-error>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail" class="profile">Email</label>

                                    <div class="col-sm-12 pass">
                                    <input type="text" v-model="form.email" class="form-control" id="inputEmail" placeholder="Email"  :class="{ 'is-invalid': form.errors.has('email') }">
                                     <has-error :form="form" field="email"></has-error>
                                    </div>
                                </div>
                               
                                <div class="input-group">
                                    <label for="password" class="profile">Change Password (leave empty if not changing)</label>
                                    <div class="pass">
                                        <input :type="show == false ? 'password' : 'text'"
                                            v-model="form.password"
                                            class="form-control"
                                            id="password"
                                            placeholder="Password"
                                            :class="{ 'is-invalid': form.errors.has('password') }"
                                        >
                                        <has-error :form="form" field="password"></has-error>  
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2" @click="showPassword"><i :class="show == true ? 'fa fa-eye' : 'fa fa-eye-slash'" aria-hidden="true"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-12">
                                    <button @click.prevent="updateInfo" type="submit" class="btn btn-success text-white">Update</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                arena:'',
                import_count:'',
                user:'',
                show:false,
                form: new Form({
                    id:'',
                    name : '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: ''
                })
            }
        },
        methods:{
            getProfilePhoto(){
                if(this.form.photo != null){
                    return (this.form.photo.length > 200) ? this.form.photo : "/image/profile/"+ this.form.photo ;
                }else{
                    return "/image/profile.png"
                }
                
            },
            getcomming(){
                  return "image/coming.jpg"
            },
            showPassword(){
                if(this.show === true){
                     this.show = false;
                }else{
                     this.show = true;
                }
               
            },  
            updateInfo(){
                this.$Progress.start();
                if(this.form.password == ''){
                    this.form.password = undefined;
                }
                this.form.put('api/profile')
                .then((data)=>{
                    
                    toast.fire({
                        icon: 'success',
                        title: 'Profile Update successfully'
                        })
                    Fire.$emit('AfterCreate');
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            updateProfile(e){
                let file = e.target.files[0];
                let reader = new FileReader();
                let limit = 1024 * 1024 * 2;
                if(file['size'] > limit){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You are uploading a large file',
                    })
                    return false;
                }
                reader.onloadend = (file) => {
                    this.form.photo = reader.result;
                }
                reader.readAsDataURL(file);
            }
        },
        created() {
            axios.get("api/profile")
                .then(({ data }) => (this.form.fill(data)));
        },
        mounted() {
            if(!this.$gate.isAdmin()){
               $('#inputName').attr('readonly',true);
            }
           
        }
    }
</script>
