<template>
    <v-app>
        <calendar :viewing="false" :status="'accept'"></calendar>
    </v-app>
</template>
<script>

import Calendar from './Calendar.vue';

import moment from "moment";


export default {
    components: {

        Calendar
    },
    data() {
        return {
        
        };
    },
 
    methods: {
      

       
    },


};
</script>

