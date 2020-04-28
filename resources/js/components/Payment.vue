<template>
  <div>
      <br>
    <div v-if="!paidFor">
      <h4>Advertisment for this place </h4>
      <hr>
      <p class="mb-2 text-secondary">Select plan</p>
        <div class="row">
            <div v-for="product_checked in products"  :key="product_checked.id" class="col-md-4 col-lg-4 col-sm-4">
              <label>
                <input type="radio" name="product" class="card-input-element" :value="product_checked" v-model="product" :id="'product' + product_checked.id" />
                  <div class="card card-input">
                    <div class="card-body p-2">
                      {{ product_checked.description }}
                      <hr class="m-2">
                       <span class="float-right font-weight-bold">{{ product_checked.price }} <i class="fas fa-pound-sign"></i></span>
                    </div>
                  </div>
              </label>
            </div>
        </div>

        <p class="text-center h5 font-weight-bold text-secondary m-4">{{ product.description }} <span v-if="product.price != ''"> - {{ product.price }} <i class="fas fa-pound-sign"></i></span></p>

      <div v-show="product.price != ''" class="mt-5" ref="paypal"></div>
    </div>


    <div v-if="paidFor">
      <h1>Nice, you bought a advertisment!</h1>
    </div>

    

  </div>
</template>

<script>
// import image from "../assets/lamp.png"
export default {

    props:['user', 'place'],

    data: function() {
        return {
        loaded: false,
        paidFor: false,
        products: [
            {id: 1, price: 3, description: "1 month of advertisment"},
            {id: 2, price: 5.50, description: "2 months of advertisment"},
            {id: 3, price: 8, description: "3 months of advertisment"},
        ],
        product:{
          price: '', description:''
        },
        payment:{
          person_id:'',
          place_id:'',
          status:'',
          payer_name:'',
          payer_email:'',
          name:'',
          amount:'',
          highlight_valid:''
        }
        };
    },

    mounted: function() {
        const script = document.createElement("script");
        script.src = "https://www.paypal.com/sdk/js?client-id=AUNTb6pouQsWb7VMzm0i1zQ4PmSC6AxNKD9JWz3ohJivtsFssOvR1E-xyVvL4Mpa03Np-lCS3u_t5j-B&currency=GBP";
        script.addEventListener("load", this.setLoaded);
        document.body.appendChild(script);
    },

    methods: {

      changeOrder: function(value){

      },


        setLoaded: function() {
        this.loaded = true;
        window.paypal
            .Buttons({
            createOrder: (data, actions) => {
                return actions.order.create({
                purchase_units: [
                    {
                    description: this.product.description,
                    amount: {
                        currency_code: "GBP",
                        value: this.product.price
                    }
                    }
                ]
                });
            },
            onApprove: async (data, actions) => {
                const order = await actions.order.capture();
                this.data;
                this.paidFor = true;

                this.payment.person_id=this.user.id;
                this.payment.place_id=this.place.id;
                this.payment.status=order.status;
                this.payment.payer_name= order.payer.name.given_name + ' ' + order.payer.name.surname;
                this.payment.payer_email= order.payer.email_address;
                this.payment.name= order.purchase_units[0].description;
                this.payment.amount= order.purchase_units[0].amount.value + ' ' + order.purchase_units[0].amount.currency_code;
                var expire_date = new Date(); expire_date.setMonth(expire_date.getMonth()+ this.product.id);
                this.payment.highlight_valid = expire_date;

                const Response = await fetch('../api/purchase/complete', {
                    method: 'post',
                        body: JSON.stringify(this.payment),
                        headers: {
                            'Accept': 'application/json',
                            'content-type': 'application/json'
                    }     
                });

            },
            onError: err => {
                console.log(err);
            }
            })
            .render(this.$refs.paypal);
        }
    }
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}

/**
  Component
**/

.card-input-element {
    display: none;
}

.card-input{
  transition: all .2s ease-in-out;
}

.card-input:hover {
    cursor: pointer;
    background-color: rgb(241, 241, 241);
    transform: scale(1.05);
}

.card-input-element:checked + .card-input {
     box-shadow: 0 0 3px 5px #F39448;
}

.card-input-element:checked + .card-header {
     background-color: 0 0 3px 1px rgb(72, 89, 243);
}

</style>

