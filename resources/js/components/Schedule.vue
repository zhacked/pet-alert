<template>
    <v-app>
        <button class="btn" @click="toggleWeekends">toggle weekends</button>
        <full-calendar
            id="calendar"
            :options="calendarOptions"
            ref="fullCalendar"
        ></full-calendar>
        <v-overlay :z-index="zIndex" :value="overlay">
          
            <v-card
                :loading="loading"
                class="mx-auto my-12"
                max-width="374"
                light
            >
  
                <template slot="progress">
                    <v-progress-linear
                        color="deep-purple"
                        height="10"
                        indeterminate
                    ></v-progress-linear>
                </template>

                <v-app-bar flat color="rgba(256, 256, 256, 1)">
                    <v-toolbar-title class="text-h5 dark--text pl-0">
                        Create Appointment
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn color="#000" icon @click="closeModalAppoint" small>
                        <v-icon small>mdi-close</v-icon>
                    </v-btn>
                </v-app-bar>

                <v-card-title>Date: {{ evt.start | format }}</v-card-title>
                    
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-card-text>
                        <v-select
                            v-model="selectClient"
                            :items="client.data"
                            name="client"
                            item-text="name"
                            :rules="[(v) => !!v || 'Client is required']"
                            label="Client"
                            required
                        ></v-select>

                 
                          <v-select
                            v-model="selectVet"
                              :items="vets"
                            name="vets"
                            item-text="name"
                            :rules="[(v) => !!v || 'Item is required']"
                            label="Veterinarian"
                            required
                        ></v-select>
                        
                        <v-card-title>Today's availability</v-card-title>
        
                        <v-btn-toggle
                        
                            tile
                            color="deep-purple accent-3"
                            rounded
                            class="d-flex flex-wrap justify-between"
                        >
                            <v-btn v-for="(item, index) in this.timeAvailable" :key="index" :value="item" @click="selectTime(item)" elevation="0" rounded x-small class="timeRounded m-1"> {{item}} </v-btn>

                            
                        </v-btn-toggle>
                    </v-card-text>
                    <v-divider class="mx-4"></v-divider>
                    <v-card-text>
                        <v-textarea
                            outlined
                            name="description"
                            label="Description..."
                            value=""
                            rows="3"
                        ></v-textarea>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            class="btn-success"
                            color="#fff"
                            text
                            @click="makeAppointment"
                        >
                            Make Appointment
                        </v-btn>
                    </v-card-actions>
                </v-form>
           
         
            </v-card>
        
        </v-overlay>
    </v-app>
</template>
<script>
import "@fullcalendar/core/vdom"; // solves problem with Vite
// import FullCalendar from "@fullcalendar/vue";
import { dayGridPlugin,
    interactionPlugin,
    listPlugin
    } from '../fullcalendarPlugins'

import moment from 'moment'
const eventData = [
    {
        title: "1st Vaccine: Aso ni Enan",
        start: "2021-12-12T14:30:00",
        end: "2021-12-12T15:30:00",
        description: "Bakuna para sa aso ni Enan",
        extendedProps: {
            status: "ongoing",
            vet: 'Bill',
            bg: "#FAAA8D"
        },
    },
     {
        title: "2nd vaccine: Aso ni Enan",
        start: "2022-01-01T14:30:00",
        end: "2022-01-01T15:30:00",
        description: "Bakuna para sa aso ni Enan",
        extendedProps: {
            status: "ongoing",
            vet: 'Bill',
            bg: "#FAAA8D"
        },
    },
    {
        title: "Birthday Party",
        start: "2021-12-18T07:00:00",
        backgroundColor: "green",
        borderColor: "green",
    },
];

export default {
    components: {
        // FullCalendar, // make the <FullCalendar> tag available
    },
    data() {
        return {
            overlay: false,
            zIndex: 9999,
            valid: true,
            loading: false,
            value: {
                title: "",
                 description: "",
            },
            titleRules: [
                (v) => !!v || "Title is required",
                (v) =>
                    (v && v.length <= 10) ||
                    "Title must be less than 10 characters",
            ],
           
            selectClient: null,
            selectVet: null,
            client: {},
            vets: {},
            time: null,
            evt: {
                start: "",
                title: "",
                extendedProps: {
                    status: "ongoing",
                },
            },
            timeAvailable: [],
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin, listPlugin],
                initialView: "dayGridMonth",
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "dayGridMonth,listWeek",
                },

                dayMaxEvents: true,
                dayMaxEventRows: true,
                eventDidMount: function (info) {
                    // if (info.event.extendedProps.status === "done") {
                    //     // Change background color of row
                       
                    // }
                        info.el.style.backgroundColor = info.event.extendedProps.bg;
                        info.el.style.color = "#111";

                        // Change color of dot marker
                        const dotEl =
                            info.el.getElementsByClassName("fc-event-dot")[0];
                        if (dotEl) {
                            dotEl.style.backgroundColor = "white";
                        }
                },
                eventAllow: this.eventAllow,
                eventDrop: this.eventDrop,
                eventClick: this.eventClick,
                editable: true,
                // selectable: true,
                // selectMirror: true,
                // dayMaxEvents: true,
                eventSources: [{events: eventData}],
                dateClick: this.handleDateClick,
            },
        };
    },
    methods: {
        toggleWeekends: function () {
            this.calendarOptions.weekends = !this.calendarOptions.weekends; // toggle the boolean!
        },
        handleDateClick: function (arg) {
            this.overlay = !this.overlay;
            this.evt.start = arg.dateStr;
            console.log(arg)
            // console.log(moment().hour())
            function timeAvail(start=8, end=17){
                const locale = 'en'; // or whatever you want...
                const hours = [];

                moment.locale(locale);  // optional - can remove if you are only dealing with one locale

                for(let hour = start; hour <= end; hour++) {
                    hours.push(moment({ hour }).format('h:mm A'));
                    
                }

                return hours
            }

            this.timeAvailable = timeAvail();
     
        },
        eventDrop: (info) => {
            console.log(info.event.title);
            console.log(info.event.start);
        },
        eventAllow: function (dropInfo) {
            if (new Date(dropInfo.startStr).getTime() < new Date().getTime())
                return false;
            return true;
        },
        eventClick: (info) => {
            // let calendar = this.$parent.$parent.$refs.fullCalendar.getApi()
            const evtIndex = eventData.findIndex(e => e.title === info.event.title);
            eventData[evtIndex].status = "done"

            // info.backgroundColor = 'yellow';
            // document.getElementById("calendar").fullCalendar( 'rerenderEvents' );
            // $(this).css('background-color','yellow');
    
           
        },
        selectTime(time){
            this.time = moment(time,"h:mm:ss A").format('HH:mm:ss');
         
        },

        makeAppointment(){
             
            // console.log(this.time, );
            // this.selectClient, "-",this.selectVet
            const start = `${this.evt.start}T${this.time}`;
            const title = `${this.selectClient} - ${this.selectVet}`
            const eventTest = {
                start,
                title,
                extendedProps: {
                    status: "ongoing",
                },
            }

            eventData.push(eventTest)
            this.overlay = !this.overlay;
            this.selectClient = null;
            this.selectVet = null;
        },

         loadClient(){
                    axios.get("api/client").then((data) => (this.client = data, console.log('CLIENT',data)));
                
            },

          async loadEmployees(){
                    const employees = await axios.get("api/employeess")
                    const vets = employees.data.filter(emp => emp.position === 'veterinarian');
                    this.vets = vets
                
            },

            closeModalAppoint(){
                 this.overlay = !this.overlay;
                 this.selectClient = null;
                 this.selectVet = null;
            }
        
    },

    //   computed: {
    //     timeAvail: function(start=8, end=17){
    //         const locale = 'en'; // or whatever you want...
    //         const hours = [];

    //         moment.locale(locale);  // optional - can remove if you are only dealing with one locale

    //         for(let hour = start; hour <= end; hour++) {
    //             hours.push(moment({ hour }).format('h:mm A'));
                
    //         }

    //         return hours
    //     }}

     created() {
    
           this.loadClient();
        this.loadEmployees();
           Fire.$on('AfterCreate',() => {
               this.loadClient();
           });
        }
};
</script>
<style scoped>
    .timeRounded {
        border: 1px solid rgba(0,0,0,0.7) !important;
        border-radius: 50px !important;
        font-size: 10px;
    }
</style>
