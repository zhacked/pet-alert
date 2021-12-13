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
                                    label="Search Report..."
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
                                    <button class="btn btn-primary  btn-sm"  @click="editModal(item)">
                                            <i class="fa fa-edit"></i> Update
                                        </button>
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
						<h5 class="modal-title" v-show="!editmode" id="addNewLabel">Consultation/Treatment</h5>
						<h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Consultation/Treatment</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form @submit.prevent="editmode ? updateReport() : createReport()">
						<div class="modal-body">
                             <div class="form-group">
                                <v-row class="no-gutters">
                                     <v-col  class="col-sm-4">
                                            <label for="procedure" class="col-sm-2 control-label">Procedure </label>
                                     </v-col>
                                     <v-col class="col-sm-8">
                                    <select name="type" v-model="form.procedure" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('procedure') }">
                                        <option value="">Select Treatment</option>
                                        <option value="consultation">Consultation</option>
                                        <option value="treatment">treatment</option>
								    </select>
                                    <has-error :form="form" field="procedure"></has-error>
                                     </v-col>
                                </v-row>
                                <v-row class="no-gutters">
                                     <v-col  class="col-sm-4">
                                            <label for="Veterinarian" class="col-sm-2 control-label">Veterinarian </label>
                                     </v-col>
                                     <v-col class="col-sm-8">
                                    <select name="type" v-model="form.employee_id" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('employee_id') }">
                                        <option value="">Select atleast one</option>
                                        <option v-for="employee in employees.data" :key="employee.id" :value="employee.id">{{employee.name}}</option>
								    </select>
                                    <has-error :form="form" field="employee_id"></has-error>
                                     </v-col>
                                </v-row>
                                <v-row class="no-gutters">
                                     <v-col  class="col-sm-4">
                                            <label for="Owner" class="col-sm-2 control-label">Owner </label>
                                     </v-col>
                                     <v-col class="col-sm-8">
                                    <select name="type" v-model="form.client_id" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('client_id') }">
                                        <option value="">Select atleast one</option>
                                        <option v-for="owner in client.data" :key="owner.id" :value="owner.id">{{owner.name}}</option>
								    </select>
                                    <has-error :form="form" field="client_id"></has-error>
                                     </v-col>
                                </v-row>
                                <v-row class="no-gutters">
                                     <v-col  class="col-sm-4">
                                            <label for="pet_id" class="col-sm-2 control-label">Pet </label>
                                     </v-col>
                                     <v-col class="col-sm-8">
                                    <select name="type" v-model="form.pet_id" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                        <option value="">Select atleast one</option>
                                        <option v-for="petData in pet.data" :key="petData.id" :value="petData.id">{{petData.Name}}</option>
								    </select>
                                     <has-error :form="form" field="pet_id"></has-error>
                                     </v-col>
                                </v-row>
                                <v-row class="no-gutters">
                                    <v-col  class="col-sm-4">
                                        <label for="note" class="col-sm-2 control-label">Note </label>
                                    </v-col>
                                    <v-col class="col-sm-8">
                                        	<div class="form-group">
                                                <textarea v-model="form.note" name="note" id="note"
                                                placeholder="note(other treatment/important operation"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('note') }">
                                                </textarea>
                                                <has-error :form="form" field="note"></has-error>
                                            </div>
                                    </v-col>
                                </v-row>
                                <v-row class="no-gutters">
                                    <v-col  class="col-sm-4">
                                        <label for="weight" class="col-sm-2 control-label">Weight </label>
                                    </v-col>
                                    <v-col class="col-sm-8">
                                        	<div class="form-group">
                                                <input v-model="form.weight" name="weight" id="weight"
                                                placeholder="xx cm"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('weight') }">
                                             
                                                <has-error :form="form" field="weight"></has-error>
                                            </div>
                                    </v-col>
                                </v-row>
                                 <v-row class="no-gutters" >
                                    <v-col class="col-sm-4">
                                        <label for="due_date" class="col-sm-2 control-label">Due Date </label>
                                    </v-col>
                                    <v-col class="col-sm-8">
                                        	<div class="form-group">
                                <v-dialog
                                    ref="dialog"
                                    v-model="modal"
                                    :return-value.sync="form.due_date"
                                    persistent
                                    width="290px"
                                    :retain-focus="false"
                                 >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="form.due_date"
                                        label="Picker in dialog"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                      <has-error :form="form" field="due_date"></has-error>
                                </template>
                                <v-date-picker v-model="form.due_date" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" @click="modal = false">
                                    Cancel
                                </v-btn>
                                <v-btn text color="primary" @click="$refs.dialog.save(form.due_date)">
                                    OK
                                </v-btn>
                                </v-date-picker>
                            </v-dialog>
                                        
                                              
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
                { text: 'Pet', value: 'pet_data.Name' },
                { text: 'Weight', value: 'weight' },
                { text: 'Procedure', value: 'procedure' },
                { text: 'Notes(other treatement/impormatant operation)', value: 'note' },
                { text: 'Next Due date', value: 'due_date' },
              
                { text: 'Actions', value: 'actions', sortable: false }
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
           loadPet(){
                    axios.get("api/pet").then(({data}) => (this.pet = data));
            },
            loadClient(){
                    axios.get("api/client").then(({ data }) => (this.client = data));
            },
            loadEmployees(){
                    axios.get("api/custom_employee").then((data) => ( this.employees = data,console.log(data)));
            },
            loadReport(){
                 axios.get("api/report").then(({data}) => ( this.report = data));
            },
            createReport(){
                this.$Progress.start();
                this.form.post('api/report')
                .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    toast({
                        type: 'success',
                        title: 'Report Save successfully'
                        })
                    this.$Progress.finish();
                })
                .catch(()=>{
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
            updateReport(){
                this.$Progress.start();
                console.log(this.form.id);
                this.form.put('api/report/'+this.form.id)
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
        },
        created() {
            this.loadClient();
            this.loadEmployees();
            this.loadPet();
            this.loadReport();
            Fire.$on('AfterCreate',() => {
               this.loadReport();
           });
        }
    }
</script>
