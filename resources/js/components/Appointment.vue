<template>
    <v-app>
        <calendar :viewing="false" :users="users" :status="'pending'"></calendar>
    </v-app>
</template>
<script>
import Calendar from './Calendar.vue';
import tinycolor from 'tinycolor2';

    export default {
        components: {Calendar},
    data() {
            return {
                users:{}
            }
        },
    methods: {
        isLight: function (hex) {
        const color1 = tinycolor(hex);
            console.log(color1.isLight())
        return color1.isLight();
        },
        user(){
            axios.get("api/userLoginDetails").then(({data}) => {
                this.users = data;
                console.log('isLogin',data);
            });
        }
    
    },
    created() {
        this.user();
    }
    }

    
</script>
