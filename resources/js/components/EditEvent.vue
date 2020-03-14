<template>

<div>

    <notifications group="foo" classes="my-style" position="top left" style="margin-top:55px;" />

    <div v-if="user.id == event.person_id">
        <button type="button" class="btn btn-primary float-right ml-3 mb-2" v-on:click="editEvent(event.id)"> <i class="far fa-edit"></i> </button>
    </div>


    <form @submit.prevent="addEvent">
            <div class="modal fade" :id="'addEvent'+event.id" tabindex="-1" role="dialog" aria-labelledby="addEventCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h5 class="modal-title" id="addEventLongTitle">Edit Event</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Title</label>
                                <input v-model="event_for_sending.title" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter title" required>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">About</label>
                                <textarea v-model="event_for_sending.about" class="form-control" id="exampleFormControlTextarea1" rows="6" required></textarea>
                            </div>


                            <ul class="list-group list-group-horizontal mb-4">
                                <li class="list-group-item"><i class="far fa-calendar-alt"></i> {{ date }}</li>
                                <li class="list-group-item"><i class="far fa-clock"></i> {{ event_time[0] }} -  {{ event_time[1] }}</li>
                            </ul>
                               

                            <vue-slider  :adsorb="true" v-if="event_time != ''" v-on:drag-end="parseDate(1)" class="mr-3 ml-3 mb-5" v-model="event_time" :data="data" :marks="marks" :enable-cross="false"></vue-slider>


                            <div id="time_error"></div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button id="add_event_btn" type="submit" class="btn btn-success float-right" :disabled="isLoading"> Update </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


</div>
    
</template>

<script>

export default {

    props:['event', 'user'],

    data(){
        return{
            isLoading:false,
            event_time:['', ''],
            event_for_sending:[],
            data: [],
            marks:{
                '06:00': '06:00',
                '09:00': '09:00',
                '12:00': '12:00',
                '15:00': '15:00',
                '18:00': '18:00',
                '21:00': '21:00',
                '23:30': '23:30',
            },
            date:'',
            person: {
                place_id:'',
                event_id:'',
            },
        }
    },


    created(){
        this.fillArrayWithTimes();   
        this.event_for_sending = this.event;
    },


    methods: {

         fillArrayWithTimes: function(){
            const hours = 24;
            var times_arr = [];
            for (let i = 6; i <= 23; i++) {
                times_arr.push(('0'+i).slice(-2) + ":" + '00');
                times_arr.push(('0'+i).slice(-2) + ":" + '30'); 
            }
            this.data = times_arr;
        },

        //------------------------Opens edit event creation label-------------------
        async editEvent(id){

            const response = await axios.get('../api/event/'+ id);

            var even = response.data.data;

            this.event_for_sending = even;
            var d = new Date(even.time_from);
            var dat = new Date(even.time_until);

            var dateee = ('0'+d.getHours()).slice(-2) +":"+ ('0'+d.getMinutes()).slice(-2);
            this.event_time[0] = dateee;
            var dateee = ('0'+dat.getHours()).slice(-2) +":"+ ('0'+dat.getMinutes()).slice(-2);
            this.event_time[1] = dateee;

            this.event_time = [this.event_time[0], this.event_time[1]];

            this.getDate(d);

            $('#addEvent'+this.event.id).modal('show');
            
        },

        getDate: function(date){
            var d = new Date(date);
            this.date =  d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
        },


        getCookie(name) {
            var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
            return v ? v[2] : null;
        },

        //-----------------Closes event creation label---------------------------
        closeAddEvent: function(){
            $('#addEvent'+this.event.id).modal('hide');
        },

        //---------------------Parse data in right format to choose when event starts and when ends--------------------
        parseDate(choose){
            if(this.event_time[0] == this.event_time[1]){
                $("#time_error").html("<span class='text-danger'><small>Times should not be equal!</small></span>");
                $("#add_event_btn").attr("disabled", true);
            }
            else if(this.event_time[0] > this.event_time[1]) {
                $("#time_error").html("<span class='text-danger'><small>Second time should be later!</small></span>");
                $("#add_event_btn").attr("disabled", true);
            }
            else{
                $("#time_error").html("");
                $('#add_event_btn').removeAttr("disabled");
            }

            $.ajax({
                type: "GET",
                url: "/validate_time?start="+ this.date +" "+ this.event_time[0]+"&end="+ this.date +" "+ this.event_time[1]+"&place_id="+this.event.place_id,     
                success: function(result){

                    if(!result.found){
                        $("#time_error").html("");
                        $('#add_event_btn').removeAttr("disabled");
                    }  
                    else{
                        $("#time_error").html("<span class='text-danger'><small>Time overlaping with other events</small></span>");
                        $("#add_event_btn").attr("disabled", true);
                    }
                    
                }
            });


        },

        //-----------------------------------------Create new event-----------------------------------------------
        addEvent() {

            this.isLoading = true
            setTimeout(() => {
                this.isLoading = false
            }, 2000);

            this.event_for_sending.time_from = this.date + " " + this.event_time[0];
            this.event_for_sending.time_until = this.date + " " + this.event_time[1];

            (async () => {
                const Response = await fetch('../api/event?api_token=' + this.getCookie("api_token"), {
                    method: 'put',
                        body: JSON.stringify(this.event_for_sending),
                        headers: {
                            'Accept': 'application/json',
                            'content-type': 'application/json'
                        }     
            });

            const content = await Response.json();
            })();

            Vue.notify({
                group: 'foo',
                title: 'Congrats!!',
                type: 'success',
                text: 'You have updated an event !'
            });  

            $('#addEvent'+this.event_for_sending.id).modal('hide');

            this.$emit('fetchCreatedEvents');
            
            this.event_for_sending.id= '';
            this.event_for_sending.place_id = '';
            this.event_for_sending.title = '';
            this.event_for_sending.about = '';
            this.event_for_sending.time_from = '';
            this.event_for_sending.time_until = '';
            this.event_for_sending.person_id = '';
        },


    }



}
</script>

<style>

body.modal-open {
    overflow: visible;
}

</style>