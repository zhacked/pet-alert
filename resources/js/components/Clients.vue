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
                                    elevation="2"  @click="newModal">Register Client <i class="fas fa-user-plus fa-fw"></i></v-btn>
							</v-card-actions>
						</v-card-title>
                        
                        <v-data-table
                                    :headers="headers"
                                    :items="client.data"
                                    :search="search"
                                    class="elevation-1"
                                >
                             
                                <template v-slot:top>
                                    <v-row>
                                        <v-col>

                                        </v-col>
                                        <v-col>
                                            <v-text-field
                                            v-model="search"
                                            label="Search Client.."
                                            class="mx-4"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                </template>
                                <template v-slot:[`item.actions`]="{ item }">
                                    <button class="btn btn-success btn-sm"  @click="openModelPet(item.id)">
												<i class="fa fa-eye"></i> pet details
									</button>
                                   <button class="btn btn-primary  btn-sm"  @click="editModal(item)">
										<i class="fa fa-edit"></i> Update
									</button>

									<button class="btn btn-danger  btn-sm"  @click="deleteClient(item.id)">
										<i class="fa fa-trash"></i> Delete
									</button>
                                </template>
                       </v-data-table>
					
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
								<input v-model="form.number" name="number" id="number"
								placeholder="contact"
								class="form-control" :class="{ 'is-invalid': form.errors.has('number') }">
								<has-error :form="form" field="number"></has-error>
							</div>


							<div class="form-group">
								<select name="type" v-model="form.gender" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('gender') }">
									<option value="">Select Gender</option>
									<option value="male">Male</option>
									<option value="female">Female</option>
								
								</select>
								<has-error :form="form" field="gender"></has-error>
							</div>
                         <p><v-icon class="text-danger">mdi-alert-decagram</v-icon><span class="text-danger">Noted : </span><span>The Default password is  <span class="text-success">petalert123</span> </span> </p>
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
                                    :headers="Pet_headers"
                                    :items="petView.data"
                                    :items-per-page="3"
                                    :search="search"
                                    class="elevation-1"
                                >
                               <template v-slot:[`item.actions`]="{ item }">
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
                                                <span> <strong> Name : </strong></span> <span>{{petname}}</span> 
                                                <br>
                                                <span> <strong>Species </strong> : <span>{{species}}</span> </span> 
                                                <br>
                                                <span> <strong> Breed </strong> : <span>{{breed}}</span></span> 
                                                <br>
                                                <span> <strong> Color </strong> : <span>{{color}}</span></span> 
                                                <br>
                                                <span><strong> Gender </strong> :<span>{{gender}}</span> </span> 
                                                <br>
                                                <span><strong> Birth date  </strong> : <span>{{bday}}</span></span>

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
                { text: 'Name', value: 'name' },
                { text: 'Email', value: 'email' },
                { text: 'Gender', value: 'gender' },
                { text: 'Actions', value: 'actions', sortable: false },
                ],
                Pet_headers: [
                { text: 'Name', value: 'Name' },
                { text: 'Breed', value: 'breed' },
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
                petView: {},
                search: '',
                length: '',
                form: new Form({
                    id:'',
                    name : '',
                    address:'',
                    email: '',
                    number: '',
                    gender: '',
                    password:'petalert123',
                    type:'client',
                   
                })
            }
        },
        methods: {
            viewpet(){
                axios.get("api/viewpet").then(({ data }) => (this.pet = data));
            },
            ViewpetDetails(item){
                this.reveal = true ;
                console.log(item)
                axios.get('api/viewpetinfo/'+ item.id)
                .then(({data}) => {
                    console.log(data);
                    this.petname = data.Name;
                    this.species = data.species;
                    this.breed = data.breed;
                    this.gender = data.gender;
                    this.photo = data.photo;
                    this.bday = data.birthday;
                    this.color = data.color;
                  
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
                    axios.get("api/client").then((data) => (this.client = data,console.log(data)));
                
            },
            openModelPet($id){
            
                this.form.reset();
                $('#petModel').modal('show');
                this.reveal = true ;
                axios.get('api/showpet/'+ $id)
                .then((data) => {
                    this.petView = data;
                    console.log(data)
                 
                })
                .catch(() => {
                    this.$Progress.fail();
                });
                this.reveal = false ;
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
