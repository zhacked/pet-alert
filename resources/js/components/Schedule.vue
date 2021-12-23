<template>
    <v-app>
        <calendar :viewing="false"></calendar>
    </v-app>
</template>
<script>

import Calendar from './Calendar.vue';

import moment from "moment";
import tinycolor from "tinycolor2";

export default {
    components: {

        Calendar
    },
    data() {
        return {
        
        };
    },
 
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
            const details = this.details;

            console.log(details)

            const start = `${this.evt.start}T${this.time}`;

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
                    details
                })
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");
                    this.loadEvents();
                });

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


};
</script>

