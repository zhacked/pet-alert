<template>
    <v-app>
        <v-row class="pa-10">
            <v-col>
                <v-sheet>
                    <v-toolbar flat>
                        <v-btn
                            outlined
                            class="mr-4"
                            color="grey darken-2"
                            @click="setToday"
                        >
                            Today
                        </v-btn>
                        <v-btn
                            fab
                            text
                            small
                            color="grey darken-2"
                            @click="prev"
                        >
                            <v-icon small> mdi-chevron-left </v-icon>
                        </v-btn>
                        <v-btn
                            fab
                            text
                            small
                            color="grey darken-2"
                            @click="next"
                        >
                            <v-icon small> mdi-chevron-right </v-icon>
                        </v-btn>
                        <v-toolbar-title v-if="$refs.calendar">
                            {{ $refs.calendar.title }}
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-menu bottom right>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    outlined
                                    color="grey darken-2"
                                    v-bind="attrs"
                                    v-on="on"
                                >
                                    <span>{{ typeToLabel[type] }}</span>
                                    <v-icon right> mdi-menu-down </v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item @click="type = 'day'">
                                    <v-list-item-title>Day</v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="type = 'week'">
                                    <v-list-item-title>Week</v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="type = 'month'">
                                    <v-list-item-title>Month</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-toolbar>
                </v-sheet>
                <v-card height="80%">
                    <v-calendar
                        ref="calendar"
                        v-model="focus"
                        color="primary"
                        :events="events"
                        :event-text-color="isLight"
                        :type="type"
                        :min-date="new Date()"
                        @click:event="showEvent"
                        @click:more="viewDay"
                        @click:date="showAppointment"
                        @mousedown:event="startDrag"
                    ></v-calendar>
                    <v-menu
                        v-model="selectedOpen"
                        :close-on-content-click="false"
                        :activator="selectedElement"
                        offset-x
                    >
                        <v-card color="grey lighten-4" min-width="350px" flat>
                            <v-toolbar
                                :color="selectedEvent.color"
                                :dark="
                                    isLight(selectedEvent) === '#fff'
                                        ? true
                                        : false
                                "
                                dense
                                flat
                            >
                                <v-toolbar-title
                                    v-html="
                                        `${moment(selectedEvent.start).format(
                                            'h A'
                                        )} ${selectedEvent.name}`
                                    "
                                ></v-toolbar-title>
                                <v-spacer></v-spacer>

                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            v-bind="attrs"
                                            v-on="on"
                                            icon
                                            @click="viewDay(selectedEvent)"
                                        >
                                            <v-icon>mdi-view-day</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>View Day</span>
                                </v-tooltip>
                            </v-toolbar>
                            <v-card-text>
                                {{ selectedEvent.details }}
                            </v-card-text>
                            <v-card-actions v-show="checkUser(selectedEvent)">
                                <v-spacer></v-spacer>

                                <v-btn
                                    v-show="selectedEvent.status !== 'remove'"
                                    icon
                                    color="primary"
                                    @click="showEditAppointment(selectedEvent)"
                                >
                                    <v-icon>mdi-calendar-edit</v-icon>
                                </v-btn>
                                
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">

                                        <v-btn
                                            v-show="selectedEvent.status !== 'remove'"
                                            v-bind="attrs"
                                            v-on="on"
                                            icon
                                            @click="dialogDelete = true"
                                            color="red"
                                        >
                                            <v-icon>mdi-calendar-remove</v-icon>
                                        </v-btn>
                                        <v-btn small v-show="selectedEvent.status === 'remove'" disabled>{{selectedEvent.status === 'remove' && "Waiting for admin to remove request"}}</v-btn>
                                    </template>
                                    <span>Remove Appointment</span>
                                </v-tooltip>
                            </v-card-actions>
                        </v-card>
                    </v-menu>
                </v-card>
            </v-col>
        </v-row>

        <!-- DIALOG DELETE APPOINTMENT -->
        <v-dialog v-model="dialogDelete" max-width="320">
            <v-card>
                <v-card-title class="text-h6">
                    {{ `Remove schedule for ${selectedEvent.pet && selectedEvent.pet.Name}?` }}
              
                </v-card-title>

                <v-card-text>
                    <v-list dense>
                        <v-subheader>Appointment</v-subheader>

                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>
                                    <v-chip class="ma-2" small> Client </v-chip>
                                    {{
                                        selectedEvent.client &&
                                        selectedEvent.client.name
                                    }}
                                </v-list-item-title>
                                <v-list-item-title>
                                    <v-chip class="ma-2" small> Pet </v-chip>
                                    {{
                                        selectedEvent.pet &&
                                        selectedEvent.pet.Name
                                    }}
                                </v-list-item-title>
                                <v-list-item-title>
                                    <v-chip class="ma-2" small>
                                        Service
                                    </v-chip>
                                    {{
                                        selectedEvent.service &&
                                        selectedEvent.service.name
                                    }}
                                </v-list-item-title>
                                <v-list-item-title>
                                    <v-chip class="ma-2" small> Date </v-chip>
                                    {{
                                        moment(selectedEvent.start).format("LL")
                                    }}
                                </v-list-item-title>
                                <v-list-item-title>
                                    <v-chip class="ma-2" small> Time </v-chip>
                                    {{
                                        moment(selectedEvent.start).format("LT")
                                    }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn
                        color="green darken-1"
                        text
                        @click="dialogDelete = false"
                    >
                        Disagree
                    </v-btn>

                    <v-btn
                       
                        color="green darken-1"
                        text
                        @click="removingAppointment(selectedEvent)"
                    >
                        Agree
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- DIALOG DELETE APPOINTMENT -->

        <v-overlay
            :z-index="zIndex"
            :value="overlay"
            style="overflow-y: hidden !important; height: 100vh !important"
        >
            <v-card
                :loading="loading"
                class="mx-auto"
                width="90%"
                light
                :disabled="loading"
                height="90%"
                style="overflow-y: auto !important;"
               
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
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-select
                                        dense
                                        v-show="$gate.isAdminOrisEmployee()"
                                        v-model="selectClient"
                                        :items="client.data"
                                        name="client"
                                        item-text="name"
                                        :rules="[
                                            (v) => !!v || 'Client is required',
                                        ]"
                                        label="Client"
                                        required
                                        return-object
                                        @change="petsOwner(selectClient.id)"
                                    ></v-select>

                                    <v-select
                                        dense
                                        v-model="selectPet"
                                        :items="pets"
                                        name="pet"
                                        item-text="Name"
                                        :rules="[
                                            (v) => !!v || 'Client is required',
                                        ]"
                                        label="Pet"
                                        required
                                        return-object
                                    ></v-select>

                                    <v-select
                                        dense
                                        v-model="selectVet"
                                        :items="vets"
                                        name="vets"
                                        item-text="name"
                                        :rules="[
                                            (v) => !!v || 'Item is required',
                                        ]"
                                        label="Veterinarian"
                                        required
                                        return-object
                                    ></v-select>

                                    <v-select
                                        dense
                                        v-model="selectService"
                                        :items="services"
                                        name="services"
                                        item-text="name"
                                        :rules="[
                                            (v) => !!v || 'Item is required',
                                        ]"
                                        label="Service"
                                        required
                                        return-object
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-card-title
                                        >Today's&nbsp;availability</v-card-title
                                    >

                                    <v-chip-group
                                        active-class="primary--text"
                                        column
                                        v-model="time"
                                        v-if="selectVet"
                                    >
                                        <v-chip
                                            
                                            small
                                            filter
                                            v-for="(item, index) in this.timeAvail(selectVet && selectVet.schedFrom, selectVet && selectVet.schedTo, this.eventsAll, selectedDate)"
                                            :key="index"
                                            :value="item"
                                            @click="selectTime(item)"
                                        >
                                            {{ item }}
                                        </v-chip>
                                    </v-chip-group>
                                </v-col>
                            </v-row>
                            <v-divider class="mx-2"></v-divider>
                            <v-row class="mt-2">
                                   <v-textarea
                            v-model="details"
                            outlined
                            name="description"
                            label="Description..."
                            rows="2"
                        ></v-textarea>
                            </v-row>
                        </v-container>
                    </v-card-text>
                  
                    <v-card-actions class="my-0">
                        <v-spacer></v-spacer>
                        
                        <v-btn
                            
                            class="btn-success"
                            color="#fff"
                            text
                            
                            @click="
                                editAppoint
                                    ? updateAppointment()
                                    : makeAppointment()
                            "
                            
                        >
                            {{
                                editAppoint
                                    ? `Update Appointment`
                                    : "Make Appointment"
                            }}
                        </v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-overlay>
    </v-app>
</template>
<script>
import moment from "moment";
import tinycolor from "tinycolor2";
export default {
    props: {
        viewing: Boolean,
        users: Object,
        status: String,
    },
    data() {
        return {
            moment,
            focus: "",
            type: "month",
            typeToLabel: {
                month: "Month",
                week: "Week",
                day: "Day",
            },
            selectedEvent: {},
            selectedElement: null,
            selectedOpen: false,
            events: [],
            eventsAll: [],
            overlay: false,
            dialogDelete: false,
            //-----------------------------------//
            selection: "4:00 PM",
            zIndex: 99999,
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

            selectClient: {},
            selectVet: {},
            selectService: {},
            selectPet: {},
            selectedDate: "",
            details: "",
            client: {},
            vets: {},
            services: [],
            pets: [],
            time: "",
            evt: {
                name: "",
                start: "",
                end: "",
                color: "",
                timed: "",
                textColor: "",
                details: "",
            },
            timeAvailable: [],
            editAppoint: false,
            isDisabled: true
        };
    },

    methods: {
        viewDay(info) {
            this.focus = info.start || info.date;
            this.type = "day";
            this.selectedOpen = false;
        },
        startDrag({ event, timed }) {
            if (event && timed) {
                this.dragEvent = event;
                this.dragTime = null;
                this.extendOriginal = null;
            }
        },
        timeAvail(start, end, events, date, filterTime = true) {
            const locale = "en"; // or whatever you want...
            const hours = [];
            let existingTime = [];
            const s = parseInt(start)
            const e = parseInt(end)
    

            const checkTimeForDay = events.filter((e) => {
                return e.start.includes(date);
            });

            checkTimeForDay.forEach((ctfd) => {
                existingTime.push(
                    moment({ hour: ctfd.start.split("T")[1] }).format("h:mm A")
                );
            });

            moment.locale(locale); // optional - can remove if you are only dealing with one locale

            for (let hour = s; hour <= e; hour++) {
                hours.push(moment({ hour }).format("h:mm A"));
            }

            const newSetTime = filterTime
                ? hours.filter((t) => !existingTime.includes(t))
                : hours;
            return newSetTime;
        },

        showAppointment(info) {
            this.petsOwner();
            if (!this.viewing && (info.present || info.future)) {
                this.overlay = !this.overlay;
                this.evt.start = info.date;

                console.log("INFODATE", info.date);

                this.selectedDate = info.date
                // this.timeAvailable = this.timeAvail(
                //     8,
                //     17,
                //     this.eventsAll,
                //     info.date
                // );
            } else {
                this.focus = info.start || info.date;
                this.type = "day";
            }
        },
        getEventColor(event) {
            this.evt.textColor = event.color;
            return event.color;
        },
        setToday() {
            this.focus = "";
        },
        prev() {
            this.$refs.calendar.prev();
        },
        next() {
            this.$refs.calendar.next();
        },
        showEvent({ nativeEvent, event }) {
            const open = () => {
                this.selectedEvent = event;

                requestAnimationFrame(() =>
                    requestAnimationFrame(() => (this.selectedOpen = true))
                );
            };

            if (this.selectedOpen) {
                this.selectedOpen = false;
                requestAnimationFrame(() =>
                    requestAnimationFrame(() => open())
                );
            } else {
                open();
            }

            nativeEvent.stopPropagation();
        },

        rnd(a, b) {
            return Math.floor((b - a + 1) * Math.random()) + a;
        },

        //---------------------------------//

        selectTime(time) {
            this.time = moment(time, "h:mm A").format("HH:mm:ss");
        },
        timeEnd(time) {
            return moment(time, "h:mm A").add(1, "h").format("HH:mm:ss");
        },
        makeAppointment() {
            this.loading = true;
            const clientId = this.selectClient?.id || this.users.id;
            const clientNumber = this.selectClient?.number || this.users.number;
            const employeeId = this.selectVet.id;
            const petId = this.selectPet.id;
            const serviceId = this.selectService.id;
            const details = this.details;
            const startTime = moment(this.time, "LT").format("HH:mm:ss");
            const timeEnd = this.timeEnd(this.time);

            const dateTime = `${this.evt.start}T${startTime}`

            let appointStartEvent = [];
            let appointEndEvent = [];

            const trimmedServiceName = this.selectService.name.substring(0, 15);
      
            const message = `Good day!\nThis is a reminder that ${this.selectPet.Name}'s appointment is on ${moment(
               this.evt.start
            ).format("MMM DD")} at ${moment(startTime, "HH:mm:ss").format("ka")} -Pet Alert`;

          
            axios.post("api/smsSend",{
                clientNumber,
                message
            }).then(() => (console.log('Message sent')));

            const cyrb53 = function (str, seed = 0) {
                let h1 = 0xdeadbeef ^ seed,
                    h2 = 0x41c6ce57 ^ seed;
                for (let i = 0, ch; i < str.length; i++) {
                    ch = str.charCodeAt(i);
                    h1 = Math.imul(h1 ^ ch, 2654435761);
                    h2 = Math.imul(h2 ^ ch, 1597334677);
                }
                h1 =
                    Math.imul(h1 ^ (h1 >>> 16), 2246822507) ^
                    Math.imul(h2 ^ (h2 >>> 13), 3266489909);
                h2 =
                    Math.imul(h2 ^ (h2 >>> 16), 2246822507) ^
                    Math.imul(h1 ^ (h1 >>> 13), 3266489909);
                return 4294967296 * (2097151 & h2) + (h1 >>> 0);
            };

            const appointHash = Math.random(
                moment(this.evt.start).format("x") +
                    cyrb53(this.selectService.name)
            )
                .toString(36)
                .substring(2);
       
            if(this.selectService.count > 0) {
                const countingSched = (parseInt(this.selectService.due_date) < 1 && parseInt(this.selectService.count) > 1) ? 1 : this.selectService.count
                 for (let i = 0; i <= parseInt(countingSched - 1); ++i) {
                console.log('tag', i);
                const a = parseInt(this.selectService.due_date) * i;
                const start = moment(this.evt.start)
                    .add(a, "w")
                    .format("YYYY-MM-DD");
                const appointStart = `${start}T${startTime}`;
                const appointEnd = `${start}T${timeEnd}`;

                appointStartEvent.push(appointStart);
                appointEndEvent.push(appointEnd);



                axios
                    .post("api/schedule", {
                        appointHash,
                        start: appointStart,
                        end: appointEnd,
                        clientId,
                        clientNumber,
                        employeeId,
                        petId,
                        serviceId,
                        details,
                        status: this.status,
                    })
                    .then((data) => {
                       
                        Fire.$emit("AfterCreate");
                        $("#addNew").modal("hide");
                      
                    });
            }
            }
           

            this.overlay = !this.overlay;
            this.selectClient = null;
            this.selectVet = null;
            this.loading = false;
            this.selectService = null;
            this.time = "";
            this.selectPet = null;
            this.details = "";
        },
        checkFillAppointment(){
            // return  ? true : false
        },
        showEditAppointment(event) {
            console.log(event);
            this.overlay = true;
            this.petsOwner(event.client.id);
            this.evt.start = event.start;
            this.selectClient = event.client;
            this.selectVet = event.vet;
            this.selectService = event.service;
            this.selectPet = event.pet;
            this.timeAvailable = this.timeAvail(
                8,
                17,
                this.eventsAll,
                moment(event.start).format("YYYY-MM-DD"),
                false
            );

            console.log("||", event.start);
            console.log(">>>>>>", moment(event.start).format("LT"));
            this.time = moment(event.start).format("LT");
            this.editAppoint = true;

            // console.log(this.timeAvail(8, 17, this.events, moment(event.start).format('YYYY-MM-DD')), false)
        },
        updateAppointment() {
            const dateStart = moment(this.evt.start).format("YYYY-MM-DD");
            const start = `${dateStart}T${moment(this.time, "LT").format(
                "HH:mm:ss"
            )}`;
            const end = `${dateStart}T${this.timeEnd(this.time)}`;

            axios
                .put("api/schedule/" + this.selectedEvent.eventId, {
                    start,
                    end,
                    client_id: this.selectClient.id,
                    employee_id: this.selectVet.id,
                    service_id: this.selectService.id,
                    details: this.details,
                })
                .then((data) => {
                     
                     if(data.data == 'error'){
                         Toast.fire({
                                icon: 'error',
                                title: 'Schedule already exist'
                                })
                     }
                    Fire.$emit("AfterCreate");
                    this.overlay = false;
            
                });
        },

        removingAppointment(event){
            console.log(event)
            axios
                .put("api/removeSched/" ,{
                    appointHash: this.selectedEvent.appointHash
                })
                .then((data) => {
                   console.log(data)
                    Fire.$emit("AfterCreate");
                   this.dialogDelete = false;
                    
                });
        },
        
        // deleteAppointment(event) {
        //     axios
        //         .post("api/destroySched", {
        //             appointHash: event.appointHash,
        //         })
        //         .then(() => {
        //             this.loadEvents();
        //             this.dialogDelete = false;
        //         });
        // },

        loadClient() {
            axios.get("api/client").then((data) => (this.client = data));
        },

        async loadEmployees() {
            const employees = await axios.get("api/employeess");
            const vets = employees.data.filter(
                (emp) => emp.position === "veterinarian"
            );
            this.vets = vets;
        },

        async loadServices() {
            const services = await axios.get("api/service");

            this.services = services.data;
        },

        async petsOwner(id) {
            if (id) {
                const pets = await axios.get("api/getPet/" + id);
                this.pets = pets.data;
            } else {
                const pets = await axios.get("api/ownerPet/");
                console.log(pets.data);
                this.pets = pets.data;
            }
        },

        async loadEvents() {
            let events = [];
         

            const eventSchedule = await axios.get("api/eventSchedule");

            

            await eventSchedule.data.forEach((event) => {
           
                const allDay = this.rnd(0, 3) === 0;
                const evt = {
                    eventId: event.id,
                    name: `${event.service_data.name} | ${
                        event.status === "pending"
                            ? "PENDING" : event.status === "remove" ? 'Waiting for removal'
                            : event.pet_data?.Name
                    }`,
                    client: event.client_data,
                    pet: event.pet_data,
                    service: event.service_data,
                    vet: event.employee_data,
                    start: event.start,
                    end: event.end,
                    color:
                        event.status === "pending" || event.status === 'remove'
                            ? "#696773"
                            : event.employee_data.color,
                    timed: !allDay,
                    appointHash: event.appointHash,
                    details: event.details,
                    status: event.status,
                };

                events.push(evt);
            });

            const eventsNoDeclined = events.filter(e => (e.status !== 'declined'));
          
         
            const eventFiltered = eventsNoDeclined.filter(e => (e.client.id === this.$gate.getUser().id))
        
            const appoitedEvents = this.$gate.isAdminOrisEmployee() ? events : eventFiltered




            this.events = appoitedEvents;
     
        },

        async loadEventsAll(){
            let eventsAll = [];
            const eventSchedule = await axios.get("api/eventSchedule");

        

             await eventSchedule.data.forEach((event) => {
                const evt = {
                    start: event.start,
                   
                };

                eventsAll.push(evt);
            });

            this.eventsAll = eventsAll;

            console.log(this.eventsAll)
        },

        checkUser(selectedEvent) {
            const isAdminOrisEmployee = this.$gate.isAdminOrisEmployee();
            if (this.selectedOpen) {
                return user.id === selectedEvent.client.id || isAdminOrisEmployee

                    ? true
                    : false;
            }
        },

        closeModalAppoint() {
            this.overlay = !this.overlay;
            this.selectClient = null;
            this.selectVet = null;
            this.selectService = null;
            this.time = "";
            this.selectPet = null;
            this.details = "";
        },
        isLight: function (event) {
            const color1 = tinycolor(event.color);
            return color1.isLight() ? "#000" : "#fff";
        },
    },
    computed: {
        checkAppointFilled(){
            
        }
    },
    created() {
        this.loadClient();
        this.loadServices();
        this.loadEmployees();

        this.loadEvents();
        this.loadEventsAll();
        Fire.$on("AfterCreate", () => {
            this.loadClient();
            this.loadEvents();
        });
    },
};
</script>
<style scoped>
.timeRounded {
    border: 1px solid rgba(0, 0, 0, 0.7) !important;
    border-radius: 50px !important;
    font-size: 10px;
}
</style>
