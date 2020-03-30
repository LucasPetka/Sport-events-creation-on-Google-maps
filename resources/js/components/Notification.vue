<template>

<div>
<a id="navbarDropdown" class="nav-link white" style="text-transform: capitalize;" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="far fa-bell fa-lg"></i> <span  v-if="notifications.length > 0" class="badge badge-danger"> {{ notifications.length }} </span>
</a>
<div class="dropdown-menu dropdown-menu-right pb-0 pt-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

    <div v-for="notification in notifications" :key="notification.data.notification_object.id">
    <a v-on:click="markAsRead(notification)" class="notification">

        <span v-if="notification.data.type == 'placeAcc'" > <i class="fas fa-check-square text-success fa-lg"></i> </span>
        <span v-if="notification.data.type == 'placeDec'" > <i class="far fa-times-circle text-danger fa-lg"></i> </span>
        <span v-if="notification.data.type == 'eventAcc'" > <i class="fas fa-check-square text-success fa-lg"></i> </span>
        <span v-if="notification.data.type == 'eventDec'" > <i class="far fa-times-circle text-danger fa-lg"></i> </span>

        <b> "{{ notification.data.notification_object.title }}" </b>

        <span v-if="notification.data.type == 'placeAcc'" > place has been accepted </span>
        <span v-if="notification.data.type == 'placeDec'" > place has been declined </span>
        <span v-if="notification.data.type == 'eventAcc'" > event has been accepted </span>
        <span v-if="notification.data.type == 'eventDec'" > event has been declined </span>

        
    </a>
    <div class="dropdown-divider m-0"></div>
    </div>

    <button v-if="notifications.length != 0" v-on:click="markAsReadAll()" class="btn btn-block btn-sm btn-danger mr-3"> <i class="fas fa-trash-alt"></i> Clear</button>
    

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
        },
        
        markAsReadAll(){
            axios.post('notification/readall').then(response =>{
            });

            this.notifications = [];
        }

        
    }



}
</script>
<style scoped>
.notification{
    display: block;
    width: 100%;
    padding: 0.6rem 1.5rem;
    clear: both;
    font-weight: 400;
    text-align: inherit;
    border: 0;
    cursor: pointer;
}

.notification:hover{
    background-color: rgb(255, 212, 155);
}

.dropdown-menu {
    width: 300px !important;
}



</style>