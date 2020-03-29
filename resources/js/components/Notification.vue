<template>

<div>
<a id="navbarDropdown" class="nav-link white" style="text-transform: capitalize;" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="far fa-bell fa-lg"></i> <span  v-if="notifications.length > 0" class="badge badge-danger"> {{ notifications.length }} </span>
</a>
<div class="dropdown-menu dropdown-menu-right" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

    <a v-for="notification in notifications" :key="notification.data.notification_object.id" v-on:click="markAsRead(notification)" class="dropdown-item">
         "{{ notification.data.notification_object.title }}"
         <span v-if="notification.data.type == 'placeAcc'" > place has been accepted </span>
         <span v-if="notification.data.type == 'placeDec'" > place has been declined </span>
         <span v-if="notification.data.type == 'eventAcc'" > event has been accepted </span>
         <span v-if="notification.data.type == 'eventDec'" > event has been declined </span>
    </a>

    <div class="m-1 text-center" v-if="notifications.length == 0"> No notifications... </div>

</div>
</div>

</template>

<script>


export default {

    props:['user'],

    data(){
        return{
            notifications:[],
        }
    },


    created(){

        axios.get('/notifications/get').then(response => {
            this.notifications = response.data;
        });

        Echo.private('App.User.' + this.user.id).notification((notification) => {
            this.notifications.push(notification);
            console.log(notification);
        });

    },


    methods: {

        markAsRead(notification){
            var data = {
                id: notification.id
            };
            axios.post('notification/read', data).then(response =>{
                window.location.href = "/home";
            });
        }


        
    }



}
</script>