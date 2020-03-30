<template>

<div>
LABUKAS
</div>

</template>

<script>
import { Bar } from 'vue-chartjs'

export default {

    extends: Bar,

    data(){
        return{
            notifications:[],
        }
    },

    mounted () {
        this.renderChart(data, options)
    },


    created(){

        

    },


    methods: {

        markAsRead(notification){
            var data = {
                id: notification.id
            };
            axios.post('notification/read', data).then(response =>{
                window.location.href = "/home";
            });
        },
        
        

        
    }



}
</script>
<style scoped>




</style>