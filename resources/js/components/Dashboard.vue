<template>
    <div class="container">
        <v-row class=" justify-content-center">
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box ">
                <div class="inner">
                    <h3>{{count.client}}</h3>

                    <p>Clients</p>
                </div>
                <div class="icon">
                   <i class="fas fa-users"></i>
                </div>
                <a href="/clients" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
             <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box">
                <div class="inner">
                    <h3>{{count.report}}</h3>

                    <p>Treatments</p>
                </div>
                <div class="icon">
                    <i class="fas fa-notes-medical"></i>
                </div>
                <a href="/report" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
             <div class="col-lg-4 col-6 " id="clock">
                <!-- small box -->
                <div class="small-box sidebar-blue text-white">
                  <div class="inner">
                    <h1 class="time text-center mt-4">{{ time  }}</h1>
                      <h4 class="date text-center mb-4">{{ date }}</h4>
                  </div>
                  <div class="icon">
                      <i class="ion ion-bag"></i>
                  </div>
                  
                </div>
            </div>


        </v-row>
        <v-row>
          <div class="col-md-3">
            <div class="sticky-top mb-3">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Employees</h4>
                </div>
                <div class="card-body">
                  <!-- the events -->
                  <div id="external-events">
                    <div class="external-event bg-success">Sharlene</div>
                    <div class="external-event bg-warning">Ann Canimo</div>
                    <div class="external-event bg-info">Janel PEralta</div>
                    <div class="external-event bg-primary">Nathan Belza</div>
                   

                  </div>
                </div>
                <!-- /.card-body -->
              </div>
            </div>
          </div>
             <div class="col-md-9">
            <div class="sticky-top mb-3">
              <div class="card">
               
                <div class="card-body">
                  <!-- the events -->
                   <full-calendar
                      id="calendar"
                      :options="calendarOptions"
                     
                  ></full-calendar>
                </div>
                <!-- /.card-body -->
              </div>
            </div>
          </div>
          <!-- /.col -->
        </v-row>
      
    </div>
</template>

<script>
import moment from 'moment';
import { formatDate } from '@fullcalendar/vue';
import { dayGridPlugin,
    interactionPlugin,
    listPlugin
    } from '../fullcalendarPlugins'


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
      
          data() {
            return {
                date: '',
                time: '',
                count:{},
                calendarOptions: {
                  plugins: [dayGridPlugin, interactionPlugin, listPlugin],
                  initialView: "dayGridMonth",
                  headerToolbar: {
                      left: "prev,next today",
                      center: "title",
                      right: "dayGridMonth,listWeek",
                  },
                    eventDidMount: function (info) {
                  
                        info.el.style.backgroundColor = info.event.extendedProps.bg;
                        info.el.style.color = "#111";

                        // Change color of dot marker
                        const dotEl =
                            info.el.getElementsByClassName("fc-event-dot")[0];
                        if (dotEl) {
                            dotEl.style.backgroundColor = "white";
                        }
                },

                  dayMaxEvents: true,
                  dayMaxEventRows: true,
                  eventSources: [{events: eventData}],
             
            
            },
            }
        },
        methods: {
         printDate: function () {
            return  moment().format('MMMM Do YYYY');
          },
          printTime: function () {
            return new Date().toLocaleTimeString();
          },
          counting(){
              axios.get("api/countdata").then(({ data }) => (
                this.count = data

                ));
                
            },
        test(){
          let str = formatDate(new Date(), {
            month: 'long',
            year: 'numeric',
            day: 'numeric'
          });

        console.log(str);

        }         
            
       
        },
        created() {
         this.date = this.printDate();
          this.time = this.printTime();
          this.counting();
          
        }
    }

    
</script>