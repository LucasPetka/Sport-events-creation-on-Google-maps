<template>

<div id="alertas">
<div id="evSuc" class="alert alert-success m-4" style="display:none;" role="alert">
  Event was successfuly created !
</div>

</div>
           
</template>

<script>

export default {

    components: {
    
    },
    
    data(){
        return{
            
        }
    },

    created(){
        

    },

    methods: {

        eventCreated(){

            $('#alertas').show(); 
            $('#evSuc').show();

            setTimeout(() => { 

                $("#evSuc").animate({opacity:0}, 1500, function(){
                $('#evSuc').hide();
                $('#alertas').hide();
                });


            }, 2000); 

    },


}
}
</script>

