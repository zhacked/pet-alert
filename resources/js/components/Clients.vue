<template >
    <v-app>
		<v-container>
			<v-row class="mt-5">
				<v-col class="col-md-12">
					<v-card >
						<v-card-title class="card-header">
							<strong> Client List</strong>
                            <v-spacer></v-spacer>
							<v-card-actions class="card-tools">
								<v-btn color="success"
                                    elevation="2"  @click="newModal">New <i class="fas fa-user-plus fa-fw"></i></v-btn>
							</v-card-actions>
						</v-card-title>


							<v-simple-table class=" table-hover elevation-1">
								<thead >
									<tr class="text-center ">
										<td>ID</td>
										<td>Name</td>
										<td>Email</td>
										<td>Gender</td>
										<td>Pet</td>
                                        <td>Action</td>
									</tr>
								</thead>
								<tbody>
									<!-- <tr v-if="users.data.length == 0">
										<td colspan="7" class="text-center"> <h3>No Data Available</h3> </td>
									</tr> -->
									<tr  v-for="clientData in client.data" :key="clientData.id" class="text-center">
										<td>{{clientData.id}}</td>
                                        <td>{{clientData.name}}</td>
                                        <td>{{clientData.email}}</td>
                                        <td>{{clientData.gender}}</td>
                                        <td>
                                            <button class="btn btn-success btn-sm"  @click="openModelPet()">
												<i class="fa fa-eye"></i> pet details
											</button>
                                        </td>

										<td>
											<button class="btn btn-primary  btn-sm"  @click="editModal(clientData)">
												<i class="fa fa-edit"></i> Update
											</button>

												<button class="btn btn-danger  btn-sm"  @click="deleteClient(clientData.id)">
												<i class="fa fa-trash"></i> Delete
											</button>
										</td>
									</tr>
								</tbody>
							</v-simple-table>

						<v-card-title class="ma-0">
							<pagination  :data="client" @pagination-change-page="getResults"></pagination>
						</v-card-title>
					</v-card>
				</v-col>
			</v-row>


					<!-- Modal client  -->
			<div class="modal fade " id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
					<div class="modal-header sidebar-blue text-white">
						<h5 class="modal-title" v-show="!editmode" id="addNewLabel">ADD CLIENT</h5>
						<h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User's Info</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editmode ? updateClient() : createClient()">
						<div class="modal-body">
							<div class="form-group">
								<input v-model="form.name" type="text" name="name"
									placeholder="Name"
									class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
								<has-error :form="form" field="name"></has-error>
							</div>
                            <div class="form-group">
								<input v-model="form.address" type="text" name="address"
									placeholder="Address"
									class="form-control" :class="{ 'is-invalid': form.errors.has('address') }">
								<has-error :form="form" field="address"></has-error>
							</div>
							<div class="form-group">
								<input v-model="form.email" type="email" name="email"
									placeholder="Email Address"
									class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
								<has-error :form="form" field="email"></has-error>
							</div>

							<div class="form-group">
								<input v-model="form.contact" name="contact" id="contact"
								placeholder="contact"
								class="form-control" :class="{ 'is-invalid': form.errors.has('contact') }">
								<has-error :form="form" field="contact"></has-error>
							</div>


							<div class="form-group">
								<select name="type" v-model="form.gender" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('gender') }">
									<option value="">Select Gender</option>
									<option value="male">Male</option>
									<option value="female">Female</option>
								
								</select>
								<has-error :form="form" field="gender"></has-error>
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


            		<!-- Modal view pet -->
			<div class="modal fade " id="petModel" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
					<div class="modal-header sidebar-blue text-white">
						<h5 class="modal-title" v-show="!editmode" id="addNewLabel">Pet</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form >
						<div class="modal-body">
                            <v-card class="mt-5">
                                <v-data-table
                                    :headers="headers"
                                    :items="pet.data"
                                    :items-per-page="5"
                                    class="elevation-1"
                                >
                               <template v-slot:item.actions="{ item }">
                                <v-btn class="btn btn-success btn-sm"  @click="ViewpetDetails(item)">
                                        	<i class="fa fa-paw pr-2">Pet</i>
                                </v-btn>
                                </template>
                                
                                </v-data-table>
                            </v-card>
                            <br>
                            <v-expand-transition class="mt-5">
                                <v-card  
                                    v-if="reveal"
                                    class="transition-fast-in-fast-out v-card--reveal">
                                    <v-card-text>
                                        <v-row>
                                            <v-col>
                                                <v-img 
                                                class="mt-3"
                                                style="border:1px solid black;"
                                                lazy-src="https://picsum.photos/id/11/10/6"
                                                max-height="127"
                                                max-width="220"
                                                src="https://picsum.photos/id/11/500/300"
                                                ></v-img>
                                            </v-col>
                                            <v-col>
                                                <span>Name : <span>{{petname}}</span> </span> 
                                                <br>
                                                <span>Species : <span>{{species}}</span> </span> 
                                                <br>
                                                <span>Breed : <span>{{breed}}</span></span> 
                                                <br>
                                                <span>Color : <span>test</span></span> 
                                                <br>
                                                <span>Gender :<span>{{gender}}</span> </span> 
                                                <br>
                                                <span>Birth date : <span>{{bday}}</span></span>

                                            </v-col>
                                        </v-row>
                                    </v-card-text>
                                </v-card>
                            </v-expand-transition>
						</div>
						<div class="modal-footer">
							
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
                { text: 'Species', value: 'species' },
                { text: 'Actions', value: 'actions', sortable: false },
                ],
                editmode: false,
                petname: '',
                species: '',
                breed:'',
                gender:'',
                photo:'',
                color:'',
                bday:'',
                reveal: false,
                client : {},
                pet:{},
                length: '',
                form: new Form({
                    id:'',
                    name : '',
                    address:'',
                    email: '',
                    contact: '',
                    gender: '',
                   
                })
            }
        },
        methods: {
            getResults(page = 1) {
                        axios.get('api/client?page=' + page)
                            .then(response => {
                                this.client = response.data;
                            });
                },
            viewpet(){
                axios.get("api/viewpet").then(({ data }) => (this.pet = data));
            },
            ViewpetDetails(item){
                this.reveal = true ;
                axios.get('api/viewpetinfo/'+ item.id)
                .then(({data}) => {
                    console.log(data);
                    this.petname = data.Name;
                    this.species = data.species;
                    this.breed = data.breed;
                    this.gender = data.gender;
                    this.photo = data.photo;
                    this.bday = data.birthday;
                  
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            },
            updateClient(){
                this.$Progress.start();
                console.log('Editing data');
                this.form.put('api/client/'+this.form.id)
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
            deleteClient(id){
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
                                this.form.delete('api/client/'+id).then(()=>{
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
            loadClient(){
                    axios.get("api/client").then(({ data }) => (this.client = data));
                
            },
            openModelPet(){
                this.form.reset();
                $('#petModel').modal('show');
            },
            createClient(){
                this.$Progress.start();
                this.form.post('api/client')
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
    
           this.loadClient();
           this.viewpet();
           Fire.$on('AfterCreate',() => {
               this.loadClient();
           });
        }
    }
</script>
