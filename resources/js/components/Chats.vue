<template>

<div class="row">

    <div class="col-8">
        <div class="card card-default">
            <div class="card-header">Messages</div>
        
            <div class="card-body p-0">
                <ul class="list-unstyled" id="message_list" style="height:300px; overflow-y:scroll">
                    <li class="p-2" v-for="message in messages"  :key="message.id">
                        <strong>{{ message.user.name }}</strong>
                        {{ message.message }}
                    </li>
                </ul>
            </div>

            <input @keyup.enter="sendMessage" v-model="newMessage" type="text" name="message" placeholder="Enter your message..." class="form-control">
        </div>

        <span class="text-muted">user is typing...</span>
    </div>

    <div class="col-4">
        <div class="card card-default">
            <div class="card-header">Users online</div>
            <div class="card-body p-0">
                <ul>
                    <li class="py-2" v-for="user in users" :key="user.id"> {{ user.name }} </li>
                </ul>
            </div>
        </div>
    </div>

</div>
    
</template>

<script>
export default {

    props:['user', 'place'],

    data(){
        return{
            messages: [],
            newMessage: '',
            users: [],
        }
    },


    created(){

        this.fetchMessages();
        Echo.join('place.' + this.place.id)
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
    },


    methods: {

        fetchMessages(){
            axios.get('../messages/' + this.place.id).then(response =>{
                this.messages = response.data;
            })
        },

        sendMessage(){
            this.messages.push({
                user: this.user,
                message: this.newMessage
            })

            axios.post('../messages', {message: this.newMessage, place_id: this.place.id });

            this.newMessage = '';

        }

    }



}
</script>