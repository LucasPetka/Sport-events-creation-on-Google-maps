<template>
  <div>
      <br>
    <div v-if="!paidFor">
      <h4>Advertisment for this place - ${{ product.price }} gbp</h4>
      <p>{{ product.description }}</p>
    </div>

    <div v-for="product_checked in products"  :key="product_checked.id">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="product" :value="product_checked" v-model="product" :id="'product' + product_checked.id">
          <label class="form-check-label" :for="'product' + product_checked.id">
            {{ product_checked.description }}
          </label>
        </div>
    </div>


    <div v-if="paidFor">
      <h1>Nice, you bought a advertisment!</h1>
    </div>

    <div ref="paypal"></div>

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
            {id: 1, price: 3, description: "advertisment for 1 month"},
            {id: 2, price: 5.50, description: "advertisment for 2 months"},
            {id: 3, price: 8, description: "advertisment for 3 months"},
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
        script.src =
        "https://www.paypal.com/sdk/js?client-id=AUNTb6pouQsWb7VMzm0i1zQ4PmSC6AxNKD9JWz3ohJivtsFssOvR1E-xyVvL4Mpa03Np-lCS3u_t5j-B&currency=GBP";
        script.addEventListener("load", this.setLoaded);
        document.body.appendChild(script);
    },

    methods: {

      changeOrder: function(value){

        console.log(value);
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
</style>