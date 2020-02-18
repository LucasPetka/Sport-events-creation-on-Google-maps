<template>

<div>

<!-- People going MODAL -->
<div class="modal fade" id="people_going" tabindex="-1" role="dialog" aria-labelledby="people_goingTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">People Going</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <ul class="list-group list-group-flush">
            <li class="list-group-item" v-for="user in people_going" :key="user.id">
                <a class="nav-link m-0 p-0" target="_blank" :href="'/user/' + user.person_id.auth_id">
                    <span v-if="user.person_id.provider == null">
                        <img class="rounded-circle" :src="'../../../images/avatars/'+user.person_id.avatar" width="30" height="30" alt="">
                    </span>
                    <span v-else>
                        <img class="rounded-circle" :src="user.person_id.avatar" width="30" height="30" alt="">
                    </span>
                    {{ user.person_id.name }} 
                </a> 
            </li>
        </ul>

      </div>
    </div>
  </div>
</div>


<p><b> <a href="#" data-toggle="modal" data-target="#people_going"> <i class="fas fa-user"></i> {{ people_going.length }} people going </a></b></p>

<div class="row">

    <div class="col-8">
        <div class="card card-default">
            <div class="card-header">Messages</div>
            <div class="card-body p-0">
                <ul class="list-unstyled" id="message_list" style="height:300px; overflow-y:scroll" v-chat-scroll="{smooth: true}">
                    <li class="p-2" v-for="message in messages"  :key="message.id">
                        
                        <span v-if="message.user.provider == null">
                            <img class="rounded-circle" :src="'../../../images/avatars/'+message.user.avatar" width="30" height="30" alt="">
                        </span>
                        <span v-else>
                            <img class="rounded-circle" :src="message.user.avatar" width="30" height="30" alt="">
                        </span>
    
                        <strong>{{ message.user.name }}</strong>
                        {{ message.message }}
                    </li>
                </ul>
            </div>

            <input @keydown="sendTyping" @keyup.enter="sendMessage" v-model="newMessage" type="text" name="message" placeholder="Enter your message..." class="form-control">
        </div>

        <span class="text-muted" v-if="activeUser">{{ activeUser.name }} is typing...</span>
    </div>

    <div class="col-4">
        <div class="card card-default">
            <div class="card-header">Users online</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" v-for="user in users" :key="user.id"> {{ user.name }} </li>
                </ul>
            </div>
        </div>
    </div>

</div>
</div>
    
</template>

<script>
export default {

    props:['user', 'event'],

    data(){
        return{
            messages: [],
            newMessage: '',
            users: [],
            people_going: [],
            activeUser: false,
            typeTime: false
        }
    },


    created(){

        this.fetchPeopleGoing();
        this.fetchMessages();
        Echo.join('event.' + this.event.id)
            .here(user => {
                this.users = user;
            })
            .joining(user => {
                this.users.push(user);
            })
            .leaving(user => {
                this.users = this.users.filter(u => u.id != user.id);
            })
            .listen('MessageSent', (event) => {
                this.messages.push(event.message);
            })
            .listenForWhisper('type', user => {
                this.activeUser = user;

                if(this.typeTime){
                    clearTimeout(this.typeTime)
                }

                this.typeTime = setTimeout(() => {
                    this.activeUser = false;
                }, 3000)
            })
    },


    methods: {

        fetchMessages(){
            axios.get('../messages/' + this.event.id).then(response =>{
                this.messages = response.data;
            })
        },

        sendMessage(){
            this.messages.push({
                user: this.user,
                message: this.newMessage
            })

            axios.post('../messages', {message: this.newMessage, event_id: this.event.id });

            this.newMessage = '';
        },

        sendTyping(){

            Echo.join('event.' + this.event.id)
                .whisper('type', this.user);

        },


        fetchPeopleGoing() {
                fetch('../api/people_going/'+ this.event.id)
                .then(res => res.json())
                .then(res => {
                    this.people_going = res.data;

                    return this.people_going.length;
                })
            
        },

    }



}
</script>