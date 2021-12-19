<template lang="">
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
                        @click:day="makeAppoint"
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
</template>
<script>
import moment from "moment";
import tinycolor from "tinycolor2";
export default {
    name: 'calendar',
    props: {
        viewing: Boolean,
        olay: Boolean,
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
            overlay: false,

            //-----------------------------------//
        
        };
    },
    mounted() {
        this.$refs.calendar.checkChange();
    },
     methods: {
        viewDay(info) {
            
            this.focus = info.start || info.date;
            this.type = "day";
            this.selectedOpen = false;
        },
        makeAppoint(info) {
            if(!this.viewing) {
               this.$emit('overlay', !this.overlay)
                // this.evt.start = info.date;

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

   
    },
    created() {
    
        this.loadEvents();
       
    },
}
</script>
<style lang="">
    
</style>