<template >
    <v-app>
		<v-container>
			<v-row class="mt-5">
				<v-col class="col-md-12">
					<v-card >
						<v-card-title class="card-header">
							<strong> Pet List</strong>
                            <v-spacer></v-spacer>
							<v-card-actions class="card-tools">
								<v-btn color="success"
                                    elevation="2"  @click="newModal">New <i class="fas fa-plus fa-fw"></i></v-btn>
							</v-card-actions>
						</v-card-title>


							<v-simple-table class=" table-hover elevation-1">
								<thead >
									<tr class="text-center ">
										<td>ID</td>
                                        <td>Owner</td>
										<td>Name</td>
										<td>Species</td>
										<td>Breed</td>
                                        <td>Action</td>
									</tr>
								</thead>
								<tbody>
									<!-- <tr v-if="users.data.length == 0">
										<td colspan="7" class="text-center"> <h3>No Data Available</h3> </td>
									</tr> -->
									<tr  v-for="petDetails in pet.data" :key="petDetails.id" class="text-center">
										<td>{{petDetails.id}}</td>
                                        <td>{{petDetails.client_data.name}}</td>
                                        <td>{{petDetails.Name}}</td>
                                        <td>{{petDetails.species}}</td>
                                        <td>{{petDetails.breed}}</td>
                                       
										<td>
											<button class="btn btn-primary  btn-sm"  @click="editModal(petDetails)">
												<i class="fa fa-edit"></i> Update
											</button>

												<button class="btn btn-danger  btn-sm"  @click="deletePet(petDetails.id)">
												<i class="fa fa-trash"></i> Delete
											</button>
										</td>
									</tr>
								</tbody>
							</v-simple-table>

						<v-card-title class="ma-0">
							<pagination  :data="pet" @pagination-change-page="getResults"></pagination>
						</v-card-title>
					</v-card>
				</v-col>
			</v-row>


					<!-- Modal -->
			<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
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
                                    <label for="photo" class="col-sm-2 control-label">Profile Photo</label>
                                    <div class="col-sm-12">
                                        <input type="file" @change="updatepetProfile" name="photo" class="form-input">
                                    </div>

                            </div>

                            <div class="form-group">
								<select name="type" v-model="form.client_id" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
									<option value="">Select Owner Name</option>
									<option v-for="owner in client.data" :key="owner.id" :value="owner.id">{{owner.name}}</option>
								
								</select>
								<has-error :form="form" field="type"></has-error>
							</div>
							<div class="form-group">
								<input v-model="form.name" type="text" name="name"
									placeholder="Name"
									class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
								<has-error :form="form" field="name"></has-error>
							</div>
                            <div class="form-group">
								<input v-model="form.species" type="text" name="species"
									placeholder="type of pet"
									class="form-control" :class="{ 'is-invalid': form.errors.has('species') }">
								<has-error :form="form" field="species"></has-error>
							</div>
							<div class="form-group">
								<input v-model="form.breed" type="breed" name="breed"
									placeholder="breed type"
									class="form-control" :class="{ 'is-invalid': form.errors.has('breed') }">
								<has-error :form="form" field="breed"></has-error>
							</div>

							<div class="form-group">
								<input v-model="form.gender" name="gender" id="gender"
								placeholder="gender"
								class="form-control" :class="{ 'is-invalid': form.errors.has('gender') }">
								<has-error :form="form" field="gender"></has-error>
							</div>

                            <div class="form-group">
								<input v-model="form.birthday" name="birthday" id="birthday"
								placeholder="Pet's birthdate"
								class="form-control" :class="{ 'is-invalid': form.errors.has('birthday') }">
								<has-error :form="form" field="birthday"></has-error>
							</div>

                        


				
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
                editmode: false,
                pet : {},
                client:{},
                length: '',
                form: new Form({
                    client_id:'',
                    id:'',
                    name : '',
                    species :'',
                    breed: '',
                    birthday: '',
                    photo: '',
                   
                })
            }
        },
        methods: {
            getResults(page = 1) {
                        axios.get('api/pet?page=' + page)
                            .then(response => {
                                this.pet = response.data;
                            });
                },
            loadClient(){
                    axios.get("api/client").then(({ data }) => (this.client = data));
                
            },
            updatepetProfile(){
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
            updatePet(){
                this.$Progress.start();
                console.log('Editing data');
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
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                         if (result.value) {
                                this.form.delete('api/pet/'+id).then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wrong.", "warning");
                                });
                         }
                    })
            },
            loadPet(){
                    axios.get("api/pet").then(({ data }) => (this.pet = data));
                
            },
            createPet(){
                this.$Progress.start();
                this.form.post('api/pet')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    toast({
                        type: 'success',
                        title: 'User Created in successfully'
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
