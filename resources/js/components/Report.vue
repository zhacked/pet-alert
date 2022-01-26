<template >
    <v-app>
		<v-container>
			<v-row class="mt-5">
				<v-col class="col-md-12">
					<v-card >
						<v-card-title class="card-header">
							<strong> Report Details</strong>
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
                      
                                    <span class="overline" :class="item.status == 'accepted' ? 'text-success' : item.status == 'declined' ? 'text-danger' : 'text-orange' ">{{item.status === 'remove' ? "Removal request" : item.status}}</span>
                                </template>
                               <template v-slot:[`item.actions`]="{ item }">
                                   <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                  

                                        <v-btn
                                        icon
                                        v-show="item.status=='declined' || item.status=='pending'"
                                        small
                                        color="green"
                                        dark
                                    v-bind="attrs"
                                        v-on="on"
                                        @click="Status(item,'accepted')"
                                        >
                                         <v-icon
                                                    >mdi-calendar-check</v-icon
                                                >
                                    </v-btn>
                                    </template>
                                    <span>Approve Appointment</span>
                                    </v-tooltip>


                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn
                                            icon
                                                v-show="item.status=='remove' || item.status === 'declined'"
                                                small
                                                color="orange"
                                                dark
                                            v-bind="attrs"
                                                v-on="on"
                                                @click="deleteAppointment(item)"
                                                >
                                                <v-icon>mdi-calendar-remove</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Remove Appointment</span>
                                    </v-tooltip>

                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn
                                            icon
                                                v-show="item.status=='accepted' || item.status=='pending'"
                                                small
                                                color="red"
                                                dark
                                            v-bind="attrs"
                                                v-on="on"
                                                @click="Status(item,'declined')"
                                                >
                                                <v-icon
                                                            >mdi-calendar-alert</v-icon
                                                        >
                                            </v-btn>
                                        </template>
                                        <span>Decline Appointment</span>
                                    </v-tooltip>

                                        
                                </template>
                                
                             </v-data-table>
							
					</v-card>
				</v-col>
			</v-row>
		</v-container>
	</v-app>
</template>

<script>
import moment from 'moment';
    export default {
        data() {
            return {
                headers: [
                { text: 'id', value: 'appointHash' },
                { text: 'Date', value: 'start' },
                { text: 'Name', value: 'client_data.name' },
                { text: 'Pet', value: 'pet_data.Name' },
                { text: 'Veterinarian', value: 'employee_data.name' },
                { text: 'Procedure', value: 'service_data.name' },
                { text: 'Notes', value: 'details' },               
                { text: 'Status', value: 'status' },
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
            loadReport(){
                 axios.get("api/schedule").then(({data}) => {
                     let newSet = [];
                     data.forEach(d => {
                        
                         newSet.push({ ...d,start: moment(d.start).format("LL LT")})
                     })
                     this.report = newSet;
                    //  this.report = {
                    //      ...data,
                    //      start: moment(data.start).format("LL LT")
                    //  }
                 } );
            },
            Status(data,status){

                const date = this.$moment(data.start, 'LT LT').format('MMM DD');
                const time = this.$moment(data.start, 'LT LT').format('ka');
                const petName = data.pet_data.Name
                const clientNumber = data.client_data.number

                const trimmedPetName = petName.substring(0, 27);
        
                const message = `Good day!\nThis is a reminder that ${trimmedPetName}'s appointment is on ${date} at ${time} -Pet Alert`;

                if(status === 'accepted') {
                    axios.post("api/smsSend",{
                        clientNumber,
                        message
                    }).then(() => (console.log('Message sent')));

                    axios.post('api/emailsend',{
                        data 
                    }).then(()=>{
                        console.log('Email sent')
                    }); 
                }
            

                axios.get('api/reportAcceptance/'+data.id+'/'+status).then(({data})=>{
                        Fire.$emit("AfterCreate");
                    
                            Toast.fire({
                                    icon: 'success',
                                    title: 'Appointment Successfully  ' + status
                            })
                    });
            },
                 
        deleteAppointment(item) {
            console.log(item)
            axios
                .post("api/destroySched", {
                    appointHash: item.appointHash,
                })
                .then(() => {
                    this.dialogDelete = false;
                });
                 Toast.fire({
                                icon: 'success',
                                title: 'Appointment Removed' 
                        })
                Fire.$emit('AfterCreate')
        },
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
