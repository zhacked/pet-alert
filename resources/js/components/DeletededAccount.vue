
<template >
    <v-app>
		<v-container>
			<v-row class="mt-5" v-if="$gate.isAdmin()">
				<v-col class="col-md-12">
						<v-card-title class="card-header">
							<strong> User Archived Account</strong>
                            <v-spacer></v-spacer>							
						</v-card-title>

                            <v-card class="mt-5">
                                <v-data-table
                                    :headers="headers"
                                    :items="users.data"
                                    :items-per-page="3"
                                    :search="search"
                                    class="elevation-1"
                                >
                               <template v-slot:[`item.actions`]="{ item }">
                                    <v-btn
                                        small
                                        color="green"
                                        dark
                                        outlined
                                        @click="editModal(item)"
                                        >
                                        <i class="fa fa-edit"></i>   Activated
                                    </v-btn>

                                    <v-btn
                                        small
                                        color="red"
                                        dark
                                        outlined
                                        @click="deleteUser(item.id)"
                                        >
                                        <i class="fa fa-trash"></i> Delete Permently
                                    </v-btn>
                                    

					
                                </template>
                                
                                </v-data-table>
                            </v-card>
				</v-col>
                <v-col class="col-md-12">
						<v-card-title class="card-header">
							<strong> Pet Archived Account</strong>
                            <v-spacer></v-spacer>							
						</v-card-title>

                            <v-card class="mt-5">
                                <v-data-table
                                    :headers="Petheaders"
                                    :items="pet"
                                    :items-per-page="3"
                                    :search="search"
                                    class="elevation-1"
                                >
                               <template v-slot:[`item.actions`]="{ item }">
                                    <v-btn
                                        small
                                        color="green"
                                        dark
                                        outlined
                                        @click="activtePet(item)"
                                        >
                                        <i class="fa fa-edit"></i>   Activated
                                    </v-btn>

                                    <v-btn
                                        small
                                        color="red"
                                        dark
                                        outlined
                                        @click="deletePet(item.id)"
                                        >
                                        <i class="fa fa-trash"></i> Delete Permently
                                    </v-btn>
                                    

					
                                </template>
                                
                                </v-data-table>
                            </v-card>
				</v-col>
			</v-row>


					<!-- Modal -->
			<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
						<h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User's Info</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editmode ? updateUser() : createUser()">
						<div class="modal-body">
							<div class="form-group">
								<input v-model="form.name" type="text" name="name"
									placeholder="Name"
									class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
								<has-error :form="form" field="name"></has-error>
							</div>

							<div class="form-group">
								<input v-model="form.email" type="email" name="email"
									placeholder="Email Address"
									class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
								<has-error :form="form" field="email"></has-error>
							</div>

							<div class="form-group">
								<textarea v-model="form.bio" name="bio" id="bio"
								placeholder="Short bio for user (Optional)"
								class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
								<has-error :form="form" field="bio"></has-error>
							</div>


							<div class="form-group">
								<select name="type" v-model="form.type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
									<option value="">Select User Role</option>
									<option value="admin">Admin</option>
									<option value="employee">Veterinarian</option>
									<option value="employee">Assitant Veterinarian </option>
								</select>
								<has-error :form="form" field="type"></has-error>
							</div>

							<div class="form-group">
								<input v-model="form.password" type="password" name="password" id="password"
								class="form-control"  placeholder="change password" :class="{ 'is-invalid': form.errors.has('password') }">
								<has-error :form="form" field="password"></has-error>
							</div>

						</div>
						<div class="modal-footer">
							<v-btn type="button" color="error" elevation="2" data-dismiss="modal">Close</v-btn>
							<v-btn v-show="editmode" type="submit" color="primary" elevation="2">Update</v-btn>
							<v-btn v-show="!editmode" type="submit" color="success"  elevation="2">Create</v-btn>
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
                { text: 'ID', value: 'id' },
                { text: 'Name', value: 'name' },
                { text: 'Email', value: 'email' },
                { text: 'Gender', value: 'gender' },
                { text: 'Modify', value: 'created_at' },
                { text: 'Actions', value: 'actions', sortable: false },
                ],
                Petheaders: [
                { text: 'Pet Name', value: 'Name' },
                { text: 'Owner', value: 'client_data.name' },
                { text: 'Species', value: 'species' },
                { text: 'Breed', value: 'breed' },
                { text: 'Color', value: 'color' },
                { text: 'Gender', value: 'gender' },
                { text: '', value: 'actions' , sortable: false }
                ],
                editmode: false,
                users : [],
                pet:[],
                length: '',
                search:'',
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
        methods: {
            loadarchivePet(){
                    axios.get("api/petarchived").then((data) => (this.pet = data.data));
                
            },
            activtePet(user){
                  this.form.reset();
                  swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Activate it!'
                    }).then((result) => {
                         if (result.value) {
                              axios.get('api/petactivate/'+user.id).then(()=>{
                                        swal.fire(
                                        'Activated!',
                                        'Your file has been active again.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wrong.", "warning");
                                });
                         }
                    })
            },
            editModal(user){
              
                this.form.reset();
                  swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Activate it!'
                    }).then((result) => {
                         if (result.value) {
                              axios.get('api/useractivate/'+user.id).then(()=>{
                                        swal.fire(
                                        'Activated!',
                                        'Your file has been active again.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wrong.", "warning");
                                });
                         }
                    })
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
                                axios.get('api/petdelete/'+id).then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'Your file has been Delete Permently.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wrong.", "warning");
                                });
                         }
                    })
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteUser(id){
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
                              axios.get('api/userdelete/'+id).then(()=>{
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
            loadUsers(){
                if(this.$gate.isAdmin()){
                    axios.get("api/userdeleted").then((data) => (this.users = data));
                }
            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/user')
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
            
           this.loadUsers();
           this.loadarchivePet();
           Fire.$on('AfterCreate',() => {
               this.loadUsers();
               this.loadarchivePet();
           });
        }
    }
</script>
