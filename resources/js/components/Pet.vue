<template >
    <v-app>
		<v-container>
			<v-row class="mt-5">
				<v-col class="col-md-12">
					<v-card >
						<v-card-title class="card-header">
							<strong> Pet List</strong>
                            <v-spacer></v-spacer>
                             <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                            ></v-text-field>
						</v-card-title>
                        <v-card-text>
                            <v-card-actions class="card-tool"> 
								<v-btn color="success" v-show="$gate.isAdmin()"  
                                    elevation="2"  @click="newModal">Register Pet <i class="fas fa-plus fa-fw">
                                </i></v-btn>
							</v-card-actions>
                            
                        </v-card-text>
                                  
                              <v-data-table
                                    :headers="headers"
                                    :items="pet.data"
                                    :search="search"
                                    class="elevation-1"
                                >
                               <template v-slot:[`item.actions`]="{ item }">
                                     <v-btn
                                        small
                                        color="primary"
                                        dark
                                        outlined
                                        @click="editModal(item)"
                                        >
                                        <i class="fa fa-edit"></i>   Update
                                    </v-btn>
                                      <v-btn
                                        small
                                        color="red"
                                        dark
                                        outlined
                                       @click="deletePet(item.id)"
                                        >
                                        <i class="fa fa-trash"></i> Archive 
                                    </v-btn>
                                </template>
                                
                             </v-data-table>
							

	
					</v-card>
				</v-col>
			</v-row>


					<!-- Modal -->
			<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content modal-lg">
					<div class="modal-header sidebar-blue text-white">
						<h5 class="modal-title" v-show="!editmode" id="addNewLabel">Insert Pet Record</h5>
						<h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Pet Info</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
                    
					<form @submit.prevent="editmode ? updatePet() : createPet()">
						<div class="modal-body">
                             <div class="form-group">
                                    <label for="photo" class="col-sm-12 control-label text-center"><h3>Profile Photo</h3> </label>
                                    <has-error :form="form" field="photo" class="text-center"></has-error>
                                    <div class="widget-user-image">
                                        <img class="img-fluid img-thumbnal mx-auto d-block"  style="border:1px solid black;" width="100" :src="getPetProfilePhoto()" alt="User Avatar">
                                    </div>

                                    <div class="col-sm-12">
                                        <input type="file" style="color:white; border:1px solid black"  @change="updatePetProfile" name="photo" class="form-input"
                                        :class="{ 'is-invalid': form.errors.has('photo') }">
                                    </div>
                                 

                            </div>

                            <div class="form-group">
								<select name="type" v-model="form.user_id" v-show="$gate.isAdminOrisEmployee()" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
									<option value="">Select Owner Name</option>
									<option v-for="owner in client.data" :key="owner.id" :value="owner.id">{{owner.name}}</option>
								</select>
								<has-error :form="form" field="type"></has-error>
							</div>
							<div class="form-group">
								<input v-model="form.Name" type="text" name="Name"
									placeholder="Name"
									class="form-control" :class="{ 'is-invalid': form.errors.has('Name') }">
								<has-error :form="form" field="Name"></has-error>
							</div>
                            <div class="form-group">
								<input v-model="form.species" type="text" name="species"
									placeholder="type of pet"
									class="form-control" :class="{ 'is-invalid': form.errors.has('species') }">
								<has-error :form="form" field="species"></has-error>
							</div>
							<div class="form-group">
								<input v-model="form.color" type="text" name="color"
									placeholder="Pet Color"
									class="form-control" :class="{ 'is-invalid': form.errors.has('color') }">
								<has-error :form="form" field="color"></has-error>
							</div>
                            <div class="form-group">
								<input v-model="form.breed" type="breed" name="breed"
									placeholder="breed type"
									class="form-control" :class="{ 'is-invalid': form.errors.has('breed') }">
								<has-error :form="form" field="breed"></has-error>
							</div>

							<div class="form-group">
								<select name="type" v-model="form.gender" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('gender') }">
									<option value="">Select Gender</option>
									<option value="male">Male</option>
									<option value="female">Female</option>
								
								</select>
								<has-error :form="form" field="gender"></has-error>
							</div>

                             
                            <v-dialog
                                ref="dialog"
                                v-model="modal"
                                :return-value.sync="form.birthday"
                                persistent
                                width="290px"
                                :retain-focus="false"
                                 >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="form.birthday"
                                        label="Choose birthday"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                      <has-error :form="form" field="birthday"></has-error>
                                </template>
                                <v-date-picker
                                v-model="form.birthday"
                                :max="new Date().toISOString().substr(0, 10)"
                                scrollable
                                >
                                <v-spacer></v-spacer>
                                <v-btn
                                    text
                                    color="primary"
                                    @click="modal = false"
                                >
                                    Cancel
                                </v-btn>
                                <v-btn
                                    text
                                    color="primary"
                                    @click="$refs.dialog.save(form.birthday)"
                                >
                                    OK
                                </v-btn>
                                </v-date-picker>
                            </v-dialog>
                                     
						</div>
						<div class="modal-footer">
							<v-btn type="button" color="error" elevation="2" data-dismiss="modal">Close</v-btn>
							<v-btn v-show="editmode" type="submit" color="primary" elevation="2">Update</v-btn>
							<v-btn v-show="!editmode" type="submit" color="success"  elevation="2">SUBMIT</v-btn>
						</div>

					</form>

					</div>
				</div>
			</div>
		</v-container>
	</v-app>
</template>

<script>
    export default {
        data() {
            return {
                headers: [
                { text: 'Pet Name', value: 'Name' },
                { text: 'Owner', value: 'client_data.name' },
                { text: 'Species', value: 'species' },
                { text: 'Breed', value: 'breed' },
                { text: 'Color', value: 'color' },
                { text: 'Gender', value: 'gender' },
                { text: '', value: 'actions' , sortable: false }
                ],
                editmode: false,
                modal: false,
                pet : [],
                client:[],
                search:'',
                length: '',
             
                form: new Form({
                    user_id:'',
                    id:'',
                    Name : '',
                    species :'',
                    gender:'',
                    breed: '',
                    birthday:'',
                    photo: '',
                    color:''
                   
                })
            }
        },
        methods: {
            loadClient(){
                    axios.get("api/client").then((data) => (this.client = data, console.log(data)));
                
            },
            loadPet(){
                    axios.get("api/pet").then((data) => (this.pet = data));
                
            },
            getPetProfilePhoto(){
                if(this.form.photo != ''){
                    return (this.form.photo.length > 200) ? this.form.photo : "/image/pet/"+ this.form.photo ;
                }else{
                    return "/image/dog.png"
                }
                
            },
            updatePetProfile(e){
                    let file = e.target.files[0];
                    let reader = new FileReader();
                    let limit = 1024 * 1024 * 2;

                    if(file['size'] > limit){
                        swal.fire({
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
            },
            getcomming(){
                  return "image/coming.jpg"
            },
           
            updatePet(){

                this.$Progress.start();
                console.log(this.form.id);
                this.form.put('api/pet/'+this.form.id)
                .then(() => {
                    $('#addNew').modal('hide');
                     swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                        )
                        this.$Progress.finish();
                         Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            editModal(user){
               console.log(user)
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deletePet(id){
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                         if (result.value) {
                                this.form.delete('api/pet/'+id).then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'Your file has been archived.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wrong.", "warning");
                                });
                         }
                    })
            },
            createPet(){
                console.log(this.form.user_id)
                this.$Progress.start();
                this.form.post('api/pet')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                     Toast.fire({
                                icon: 'success',
                                title: 'New pet added'
                                
                                })
                    this.$Progress.finish();
                })
                .catch(()=>{
                })
            }
        },
        created() {
    
           this.loadPet();
           this.loadClient();
           Fire.$on('AfterCreate',() => {
               this.loadPet();
           });
        }
    }
</script>
