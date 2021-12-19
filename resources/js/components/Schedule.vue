<template>
    <v-app>
        <!-- <full-calendar
            id="calendar"
            :options="calendarOptions"
            ref="fullCalendar"
        ></full-calendar> -->

        <v-row class="fill-height">
            <v-col>
                <v-sheet height="64">
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
                <v-sheet height="600">
                    <v-calendar
                        ref="calendar"
                        v-model="focus"
                        color="primary"
                        :events="events"
                        :event-color="getEventColor"
                        :type="type"
                        @click:event="showEvent"
                        @click:more="viewDay"
                        @click:date="makeAppoint"
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
                                dark
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
                        </v-card>
                    </v-menu>
                </v-sheet>
            </v-col>
        </v-row>
        <!-- <calendar :viewing="viewing = false" :olay.sync="this.overlay"></calendar> -->

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
                            return-object
                            @change="petsOwner(selectClient.id)"
                        ></v-select>

                        <v-select
                            v-model="selectPet"
                            :items="pets"
                            name="pet"
                            item-text="Name"
                            :rules="[(v) => !!v || 'Client is required']"
                            label="Pet"
                            required
                            return-object
                        ></v-select>

                        <v-select
                            v-model="selectVet"
                            :items="vets"
                            name="vets"
                            item-text="name"
                            :rules="[(v) => !!v || 'Item is required']"
                            label="Veterinarian"
                            required
                            return-object
                        ></v-select>

                        <v-select
                            v-model="selectService"
                            :items="services"
                            name="services"
                            item-text="name"
                            :rules="[(v) => !!v || 'Item is required']"
                            label="Service"
                            required
                            return-object
                        ></v-select>

                        <v-card-title>Today's availability</v-card-title>

                        <v-btn-toggle
                            tile
                            color="deep-purple accent-3"
                            rounded
                            class="d-flex flex-wrap justify-between"
                        >
                            <v-btn
                                v-for="(item, index) in this.timeAvailable"
                                :key="index"
                                :value="item"
                                @click="selectTime(item)"
                                elevation="0"
                                rounded
                                x-small
                                class="timeRounded m-1"
                            >
                                {{ item }}
                            </v-btn>
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
import {
    dayGridPlugin,
    interactionPlugin,
    listPlugin,
} from "../fullcalendarPlugins";
import Calendar from './Calendar.vue';

import moment from "moment";
import tinycolor from "tinycolor2";

export default {
    components: {
        // FullCalendar, // make the <FullCalendar> tag available
        Calendar
    },
    data() {
        return {
            moment,
            focus: "",
            type: "month",
            typeToLabel: {
                month: "Month",
                week: "Week",
                day: "Day"
            },
            selectedEvent: {},
            selectedElement: null,
            selectedOpen: false,
            events: [],
            viewing: false,
            overlay: false,
            //-----------------------------------//
           
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
            selectService: null,
            selectPet: null,
            client: {},
            vets: {},
            services: [],
            pets: [],
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
                    const color = tinycolor(info.event.extendedProps.bg);
                    info.el.style.backgroundColor = info.event.extendedProps.bg;
                    info.el.style.color = color.isLight() ? "#000" : "#fff";

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
                events: [],
                dateClick: this.handleDateClick,
            },
        };
    },
    // mounted() {
    //     this.$refs.calendar.checkChange();
    // },
    methods: {
        viewDay(info) {
            
            this.focus = info.start || info.date;
            this.type = "day";
            this.selectedOpen = false;
        },
        makeAppoint(info) {
            if(!this.viewing) {
                this.overlay = !this.overlay;
                this.evt.start = info.date;

                // console.log(moment().hour())
                let existingTime = [];

                const checkTimeForDay = this.events.filter((e) => {
                    return e.start.includes(info.date);
                });

                checkTimeForDay.forEach((ctfd) => {
                    existingTime.push(
                        moment({ hour: ctfd.start.split("T")[1] }).format("h:mm A")
                    );
                });

                console.log(existingTime);
                function timeAvail(start = 8, end = 17) {
                    const locale = "en"; // or whatever you want...
                    const hours = [];

                    moment.locale(locale); // optional - can remove if you are only dealing with one locale

                    for (let hour = start; hour <= end; hour++) {
                        hours.push(moment({ hour }).format("h:mm A"));
                    }
                    const newSetTime = hours.filter(
                        (t) => !existingTime.includes(t)
                    );
                    return newSetTime;
                }

                this.timeAvailable = timeAvail();
            }else{
                this.focus = info.start || info.date;
                this.type = "day";
            }
         
        },
        getEventColor(event) {
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
                this.selectedElement = nativeEvent.target;
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
     
        handleDateClick: async function (arg) {
            // this.overlay = !this.overlay;
            // this.evt.start = arg.dateStr;

            // // console.log(moment().hour())
            // let existingTime = [];

            // const checkTimeForDay = this.calendarOptions.events.filter((e) => {
            //     return e.start.includes(arg.dateStr);
            // });

            // checkTimeForDay.forEach((ctfd) => {
            //     existingTime.push(
            //         moment({ hour: ctfd.start.split("T")[1] }).format("h:mm A")
            //     );
            // });

            // console.log(existingTime);
            // function timeAvail(start = 8, end = 17) {
            //     const locale = "en"; // or whatever you want...
            //     const hours = [];

            //     moment.locale(locale); // optional - can remove if you are only dealing with one locale

            //     for (let hour = start; hour <= end; hour++) {
            //         hours.push(moment({ hour }).format("h:mm A"));
            //     }
            //     const newSetTime = hours.filter(
            //         (t) => !existingTime.includes(t)
            //     );
            //     return newSetTime;
            // }

            // this.timeAvailable = timeAvail();
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
            // this.popover({
            //     animation:true,
            //   delay: 300,
            //   content: '<b>Inicio</b>:'+info.start+"<b>Fin</b>:"+info.end,
            //   trigger: 'click'
            // })
            // info.backgroundColor = 'yellow';
            // document.getElementById("calendar").fullCalendar( 'rerenderEvents' );
            // $(this).css('background-color','yellow');
        },
        selectTime(time) {
            this.time = moment(time, "h:mm:ss A").format("HH:mm:ss");
        },

        makeAppointment() {
            const clientId = this.selectClient.id;
            const employeeId = this.selectVet.id;
            const petId = this.selectPet.id;
            const serviceId = this.selectService.id;

            const start = `${this.evt.start}T${this.time}`;
            // const title = `${this.selectClient} - ${this.selectVet}`;
            const timeEnd = moment(this.time, "h:mm:ss A")
                .add(1, "h")
                .format("HH:mm:ss");
            const end = `${this.evt.start}T${timeEnd}`;

            axios
                .post("api/schedule", {
                    start,
                    end,
                    clientId,
                    employeeId,
                    petId,
                    serviceId,
                })
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");
                    this.loadEvents();
                });

            // eventData.push(eventTest)

            this.overlay = !this.overlay;
            this.selectClient = null;
            this.selectVet = null;
        },

        loadClient() {
            axios
                .get("api/client")
                .then(
                    (data) => (
                        (this.client = data), console.log("CLIENT", data)
                    )
                );
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
            const pets = await axios.get("api/petOwnerBase/" + id);
            this.pets = pets.data;
        },
        async loadEvents() {
            let events = [];

            const eventSchedule = await axios.get("api/eventSchedule");
            console.log(eventSchedule);
            await eventSchedule.data.forEach((event) => {
                const allDay = this.rnd(0, 3) === 0;
                const evt = {
                    // title: `Service: ${event.service_data.name} | ${event.pet_data?.Name}(${event.client_data?.name})`,
                    // start: event.start,
                    // extendedProps: {
                    //     bg: event.employee_data.color || '#f1f1f1',

                    // },
                    name: `${event.service_data.name} | ${event.pet_data?.Name}(${event.client_data?.name})`,
                    start: event.start,
                    end: event.end,
                    color: event.employee_data.color || "#f1f1f1",
                    timed: !allDay,
                    details: "test description\nok",
                };

                events.push(evt);
            });

            this.events = events;
            // this.calendarOptions.events = events;
        },

        closeModalAppoint() {
            this.overlay = !this.overlay;
            this.selectClient = null;
            this.selectVet = null;
        },
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
        this.loadServices();
        this.loadEmployees();

        this.loadEvents();
        Fire.$on("AfterCreate", () => {
            this.loadClient();
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
