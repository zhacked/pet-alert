<template >
    <v-app>
		<v-container>
			<v-row class="mt-5">
				<v-col class="col-md-12">
					<v-card >
						<v-card-title class="card-header">
							<strong> Report Detials</strong>
                            <v-spacer></v-spacer>
                            <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search Report..."
                                single-line
                                hide-details
                            ></v-text-field> 
						</v-card-title>
                              <v-data-table
                                    :headers="headers"
                                    :items="report"
                                    :search="search"
                                    class="elevation-1 text-center"
                                >
                                 <template v-slot:[`item.status`]="{ item }">
                      
                                    <span class="overline" :class="item.status == 'accept' ? 'text-success' : item.status == 'declined' ? 'text-danger' : 'text-orange' ">{{item.status}}</span>
                                </template>
                               <template v-slot:[`item.actions`]="{ item }">
                                    <v-btn
                                        small
                                        color="primary"
                                        dark
                                        outlined
                                        @click="editModal(item)"
                                        >
                                        <i class="fa fa-edit">
                                            Accept
                                        </i>
                                    </v-btn>
                                    <v-btn
                                        small
                                        color="red"
                                        dark
                                        outlined
                                        @click="editModal(item)"
                                        >
                                        <i class="fa fa-edit">
                                            Declined
                                        </i>
                                    </v-btn>
                                        
                                </template>
                                
                             </v-data-table>
							
					</v-card>
				</v-col>
			</v-row>
		</v-container>
	</v-app>
</template>

<script>
    export default {
        data() {
            return {
                headers: [
                { text: 'Name', value: 'client_data.name' },
                { text: 'Pet', value: 'pet_data.Name' },
                { text: 'Procedure', value: 'service_data.name' },
                { text: 'Notes(other treatement/impormatant operation)', value: 'details' },
                // { text: 'Next Due date', value: 'due_date ' },
                { text: 'status', value: 'status' },
                { text: 'Actions', value: 'actions', sortable: false }
                ],
                editmode: false,
                pet : [],
                search:'',
                client:{},
                employees:{},
                service:[],
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
            // loadClient(){
            //         axios.get("api/client").then((data) => (this.client = data));
            // },
            // loadEmployees(){
            //         axios.get("api/custom_employee").then((data) => ( this.employees = data,console.log(data)));
            // },
            loadReport(){
                 axios.get("api/schedule").then(({data}) => ( this.report = data,console.log(data)));
            },
            // loadService(){
            //      axios.get("api/service").then((data) => (this.service = data,console.log(data)));
            // },
            // ClienttBase(){
            //     console.log('tag',this.form.client_id)
            //      axios.get("api/petOwnerBase/" + this.form.client_id).then((data) => (this.pet = data,console.log(data)));
            // },
        },
        created() {
            // this.loadClient();
            // this.loadEmployees();
            this.loadReport();
            // this.loadService();
            Fire.$on('AfterCreate',() => {
               this.loadReport();
           });
        }
    }
</script>
