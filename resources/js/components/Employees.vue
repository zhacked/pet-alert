<template >
    <v-app>
		<v-container>
			<v-row class="mt-5">
				<v-col class="col-md-12">
						<v-card >
						<v-card-title class="card-header">
							<strong> Employees</strong>
                            <v-spacer></v-spacer>
							<v-card-actions class="card-tools">
								<v-btn color="success"
                                    elevation="2"  @click="newModal">Register New Employee<i class="fas fa-plus fa-fw"></i></v-btn>
							</v-card-actions>
						</v-card-title>

                              <v-data-table
                                    :headers="headers"
                                    :items="employees.data"
                                    :items-per-page="10"
                                    class="elevation-1 text-center"
                                >
                               <template v-slot:[`item.actions`]="{ item }">
                                    <button class="btn btn-primary  btn-sm"  @click="editModal(item)">
                                            <i class="fa fa-edit"></i> Update
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
						<h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add Employees</h5>
						<h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User's Info</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editmode ? updateEmployees() : createEmployees()">
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
								<input v-model="form.number" name="contact" id="contact"
								placeholder="number"
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
                            <div class="form-group">
								<select name="type" v-model="form.position" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('position') }">
									<option value="">Select position</option>
									<option value="veterinarian">Veterinarian</option>
									<option value="staff">Staff</option>
								
								</select>
								<has-error :form="form" field="position"></has-error>
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
                headers: [
                    { text: 'name', value: 'name' },
                    { text: 'Gender', value: 'gender' },
                    { text: 'Position', value: 'position' },
                    { text: 'address', value: 'address' },
                    { text: 'Email', value: 'email' },
                    { text: 'Contact Info', value: 'number' },
                    { text: 'Actions', value: 'actions', sortable: false },
                ],
                editmode: false,
                client : {},
                pet:{},
                employees:[],
                client:{},
                length: '',
                form: new Form({
                    id:'',
                    name : '',
                    address:'',
                    email: '',
                    number: '',
                    gender: '',
                    position:'',
                   
                })
            }
        },
        methods: {
            loadClient(){
                    axios.get("api/client").then(({ data }) => (this.client = data));
                
            },
            updateEmployees(){
                this.$Progress.start();
                console.log('Editing data');
                this.form.put('api/employeess/'+this.form.id)
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
            deleteEmployees(id){
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
                                this.form.delete('api/employeess/'+id).then(()=>{
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
            loadEmployees(){
                    axios.get("api/employeess").then((data) => ( this.employees = data));
                
            },
            createEmployees(){
                this.$Progress.start();
                this.form.post('api/employeess')
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
           this.loadEmployees();
           Fire.$on('AfterCreate',() => {
               this.loadEmployees();
           });
        }
    }
</script>
