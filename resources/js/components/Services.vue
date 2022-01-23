<template >
    <v-app>
		<v-container>
			<v-row class="mt-5">
				<v-col class="col-md-12">
					<v-card >
						<v-card-title class="card-header">
							<strong> Service Offered</strong>
                            <v-spacer></v-spacer>
							<v-card-actions class="card-tools">
								<v-btn color="success"
                                    elevation="2"  @click="newModal">New <i class="fas fa-plus fa-fw"></i></v-btn>
							</v-card-actions>
						</v-card-title>

                            <v-card-text>
                                <v-row>
                                    <v-col>

                                    </v-col>
                                    <v-col>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search Service..."
                                    single-line
                                    hide-details
                                ></v-text-field> 
                                    </v-col>
                                </v-row>
                            </v-card-text>

                              <v-data-table
                                    :headers="headers"
                                    :items="service"
                                    :search="search"
                                    class="elevation-1 text-center"
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
                                     <!-- <v-btn
                                        small
                                        color="red"
                                        dark
                                        outlined
                                        @click="deleteService(item.id)"
                                        >
                                        <i class="fa fa-trash"></i> Delete
                                    </v-btn> -->
                                </template>
                                
                             </v-data-table>
							
					</v-card>
				</v-col>
			</v-row>


					<!-- Modal -->
			<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
					<div class="modal-header sidebar-blue text-white">
						<h5 class="modal-title" v-show="!editmode" id="addNewLabel">New Service</h5>
						<h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Service</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editmode ? updateService() : createService()">
						<div class="modal-body">
                             <div class="form-group">
                                <v-row class="no-gutters">
                                    <v-col  class="col-sm-4">
                                        <label for="name" class="col-sm-2 control-label">Service&nbsp;name</label>
                                    </v-col>
                                    <v-col class="col-sm-8">
                                        	<div class="form-group">
                                                <input v-model="form.name" name="serviceName" id="serviceName"
                                                placeholder="Deworming"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">              
                                                <has-error :form="form" field="name"></has-error>
                                            </div>
                                    </v-col>
                                </v-row>
                             
                              
                                <v-row class="no-gutters">
                                    <v-col  class="col-sm-4">
                                        <label for="description" class="col-sm-2 control-label">Description </label>
                                    </v-col>
                                    <v-col class="col-sm-8">
                                        	<div class="form-group">
                                                <textarea v-model="form.description" name="description" id="description"
                                                placeholder="Deworming is an important preventative care regime for reducing parasites..."
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
                                                </textarea>
                                                <has-error :form="form" field="description"></has-error>
                                            </div>
                                    </v-col>
                                </v-row>

                                 <v-row class="no-gutters">
                                    <v-col  class="col-sm-4">
                                        <label for="name" class="col-sm-2 control-label">Week&nbsp;range</label>
                                    </v-col>
                                    <v-col class="col-sm-8">
                                        	<div class="form-group">
                                                <input type="number" min="0" v-model="form.due_date" name="due_date" id="due_date"
                                                placeholder="no. weeks ..."
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('due_date') }" />              
                                                <has-error :form="form" field="due_date"></has-error>
                                            </div>
                                    </v-col>
                                </v-row>
                                <v-row class="no-gutters">
                                    <v-col  class="col-sm-4">
                                        <label for="name" class="col-sm-2 control-label">Count</label>
                                    </v-col>
                                    <v-col class="col-sm-8">
                                        	<div class="form-group">
                                                <input type="number" min="1" default="1" v-model="form.count" name="count" id="count"
                                                placeholder="service count..."
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('count') }"/>              
                                                <has-error :form="form" field="count"></has-error>
                                            </div>
                                    </v-col>
                                </v-row>
                             
                                
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
                { text: 'Name', value: 'name' },
                { text: 'Description', value: 'description' },
                { text: 'Weeks', value: 'due_date' },  
                { text: 'Count', value: 'count' },  
                { text: '', value: 'actions', sortable: false }
                ],
                editmode: false,
                search:'',
                modal:false,
                service: [],
                form: new Form({
                    id:'',
                    name:'',
                   description:'',
                   due_date:'0',
                   count:'1',
                })
            }
        },
        methods: {
            loadService(){
                 axios.get("api/service").then(({data}) => (this.service = data,console.log(data)));
            },
            createService(){
                this.$Progress.start();
                this.form.post('api/service')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Service Save successfully'
                        })
                    this.$Progress.finish();
                })
                .catch((error)=>{
                    console.log(error);
                })
         
            },
             newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            editModal(user){
               console.log(user)
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },
            updateService(){
                this.$Progress.start();
                console.log(this.form.id);
                this.form.put('api/service/'+this.form.id)
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
            deleteService(id){
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
                                this.form.delete('api/service/'+id).then(()=>{
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
        },
        created() {
            this.loadService();
             Fire.$on('AfterCreate',() => {
               this.loadService();
           });
        }
    }
</script>
