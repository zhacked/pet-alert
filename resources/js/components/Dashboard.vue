<template>
    <div class="">
        <v-row class=" justify-content-center">
           <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box">
                <div class="inner">
                  
                  <h3 class="card-header" id="employee">Employees</h3>
                  <div style="height:80px;  overflow-x: hidden;  overflow-y: scroll;">
                    <div v-for="item in this.employees" :key="item.id" class="external-event" :style="{backgroundColor: item.color, color: isLight(item.color) ? '#000' : '#fff'}">{{item.name}}</div>
                  </div>
                  
                </div>
      
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box ">
                <div class="inner">
                    <p>Number of Client/s</p>
                    <h3>{{count.client}}</h3>
                </div>

                <div class="icon">
                   <i class="fas fa-users"></i>
                </div>
                <a href="/clients" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

             <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box">
                <div class="inner">
                    <p>Number of Today's Appointment</p>
                    <h3>{{count.report}}</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-notes-medical"></i>
                </div>
                  <a href="/report" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
             <div class="col-lg-3 col-6 " id="clock">
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
             <div class="col-md-12">
            <div class="sticky-top mb-3">
              <div class="card">
                <div class="card-body">
                  <calendar :viewing="true"></calendar>
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
import Calendar from './Calendar.vue';

import moment from "moment";
import tinycolor from 'tinycolor2';

    export default {
        components: {
        Calendar
    },
          data() {
            return {
                date: '',
                time: '',
                count:{},
                employees: []
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
  
            loadEmployees() {
            axios.get("api/employeess").then(({data}) => (this.employees = data));
          },

          isLight: function (hex) {
            const color1 = tinycolor(hex);
             console.log(color1.isLight())
            return color1.isLight();
          }
            
        },
        created() {
         this.date = this.printDate();
          this.time = this.printTime();
          this.counting();
          this.loadEmployees();
        }
    }
</script>