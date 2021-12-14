<template >
    <v-app>
		<v-container>
			<v-row class="mt-5">
				<v-col class="col-md-12">
					<v-card >
						<v-card-title class="card-header">
							<strong> Consultation</strong>
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
                                    :items="report.data"
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
					<form @submit.prevent="editmode ? updateReport() : createReport()">
						<div class="modal-body">
                             <div class="form-group">
                                <v-row class="no-gutters">
                                    <v-col  class="col-sm-4">
                                        <label for="weight" class="col-sm-2 control-label">Service&nbsp;name</label>
                                    </v-col>
                                    <v-col class="col-sm-8">
                                        	<div class="form-group">
                                                <input v-model="form.weight" name="serviceName" id="serviceName"
                                                placeholder="Deworming"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('weight') }">              
                                                <has-error :form="form" field="weight"></has-error>
                                            </div>
                                    </v-col>
                                </v-row>
                             
                              
                                <v-row class="no-gutters">
                                    <v-col  class="col-sm-4">
                                        <label for="note" class="col-sm-2 control-label">Description </label>
                                    </v-col>
                                    <v-col class="col-sm-8">
                                        	<div class="form-group">
                                                <textarea v-model="form.note" name="description" id="description"
                                                placeholder="Deworming is an important preventative care regime for reducing parasites..."
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('note') }">
                                                </textarea>
                                                <has-error :form="form" field="note"></has-error>
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
                { text: ' Name', value: 'client_data.name' },
                { text: 'Description', value: 'pet_data.Name' },
                { text: 'Duration', value: 'weight' },              
                { text: '', value: 'actions', sortable: false }
                ],
                editmode: false,
                pet : [],
                search:'',
                client:{},
                employees:{},
                modal:false,
                report:[],
                form: new Form({
                    id:'',
                    procedure : '',
                    employee_id :'',
                    client_id:'',
                    pet_id: '',
                    note: '',
                    weight: '',
                    due_date:''
                   
                })
            }
        },
        methods: {
         
            createReport(){
             
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
            updateReport(){
              
            },
        },
        created() {
         
        }
    }
</script>
