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
                                    :items="petRecord"
                                    :search="search"
                                    class="elevation-1"
                                >
                                <template v-slot:[`item.status`]="{ item }">
                      
                                    <span class="overline" :class="item.status == 'accept' ? 'text-success' : item.status == 'declined' ? 'text-danger' : 'text-orange' ">{{item.status}}</span>
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
                { text: 'Pet Name', value: 'pet_data.Name' },
                { text: 'Findings', value: 'details' },
                // { text: 'Date', value: 'due_date' },
                { text: 'Treatment Done', value: 'service_data.name' },
                { text: 'Vet Incharge Name', value: 'client_data.name' },
                { text: 'status', value: 'status' },
                ],
                petRecord : [],
                search:'',
            }
        },
        methods: {
           
            loadRecord(){
                    axios.get("api/ownerPet").then(({data}) => {
                        this.petRecord = data;
                        console.log(this.petRecord);
                        });
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
