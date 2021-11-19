<template >
    <v-app>
		<v-container>
			<v-row class="mt-5">
				<v-col class="col-md-12">
					<v-card >
						<v-card-title class="card-header">
							<strong> My Pet Record</strong>
                            <v-spacer></v-spacer>
                            <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                            ></v-text-field>
						</v-card-title>

                              <v-data-table
                                
                                    :headers="headers"
                                    :items="report.data"
                                    :search="search"
                                    class="elevation-1"
                                >
                             
                                
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
                { text: 'Pet Name', value: 'pet_data.Name' },
                { text: 'Findings', value: 'finding = null ? "yes" : "None"' },
                { text: 'Date', value: 'due_date' },
                { text: 'Treatment Done', value: 'procedure' },
                { text: 'Vet Incharge Name', value: 'client_data.name' },
                ],
                editmode: false,
                modal: false,
                report : [],
                client:[],
                search:'',
                form: new Form({
                    client_id:'',
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
                    axios.get("api/client").then(({data}) => (this.client = data));
            },
            loadRecord(){
                    axios.get("api/record").then((data) => (this.report = data,console.log(data)));

            },
           
        },
        created() {
    
           this.loadRecord();
           Fire.$on('AfterCreate',() => {
               this.loadRecord();
           });
        }
    }
</script>
