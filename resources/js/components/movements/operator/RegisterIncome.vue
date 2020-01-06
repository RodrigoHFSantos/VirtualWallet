<template>
    <v-card class="elevation-12 card">
        <v-toolbar color="blue-grey darken-4" flat>
            <v-toolbar-title>Register Income</v-toolbar-title>
        </v-toolbar>
            <v-card-text background-color="dark">
                <v-form id="check-registerIncome-form" enctype='multipart/form-data' v-model="valid" lazy-validation>
                    <v-text-field
                        color="blue-grey darken-1"
                        background-color="dark"
                        label="Email"
                        name="email"
                        type="email"
                        id="email"
                        v-model.trim="data.email"
                        :rules="[$v.data.email.required || 'Email is required']"
                    />

                    <v-text-field
                        color="blue-grey darken-1"
                        background-color="dark"
                        id="value"
                        label="Value (From 0,01€ up to 5000,00€)"
                        name="value"
                        type="value"
                        v-model="data.value"
                        :rules="[$v.data.value.required || 'Value is required', $v.data.value.numeric || 'Value can only have digits!']"
                    />
                     <v-flex xs12 sm6 d-flex>
                        <v-select
                            color="blue-grey darken-1"
                            background-color="dark"
                            :items="items"
                            label="Type of Payment"
                            outline
                            v-model="typeOfPayment"
                            :rules="[inputRules || 'Type of Payment is required']"
                        ></v-select>
                    </v-flex>

                    <v-text-field
                        color="blue-grey darken-1"
                        background-color="dark"
                        v-if="ibanActive"
                        id="iban"
                        label="IBAN (2 capital letters followed by 23 digits)"
                        name="iban"
                        type="iban"
                        v-model="data.iban"
                        :rules = "[$v.data.iban.alpha || 'Must have 2 capital letters followed by 23 digits', $v.data.iban.maxLength || 'Can not be more than 25 characters']"
                        />

                    <v-text-field
                        color="blue-grey darken-1"
                        background-color="dark"
                        id="source_description"
                        label="Source Description"
                        name="source_description"
                        type="source_description"
                        v-model="data.source_description"
                        :rules="[$v.data.source_description.required || 'Description is required']"
                    />
                <br>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-btn block  color="blue-grey darken-1" form="check-registerIncome-form" @click="registerIncome">Register Income</v-btn>
            </v-card-actions>
    </v-card>
</template>

<script>
import  {required,numeric, helpers, maxLength} from 'vuelidate/lib/validators';
const alpha = helpers.regex('alpha', /^[A-Z]{2}[0-9]{23}/);
  export default {
    name: 'registerIncome',

    data: function() {
        return {
            data: {
                email: "",
                value: "",
                type_payment: "",
                source_description: "",
                iban: "",
            },
            items: ['Cash', 'Bank Transfer'],
            typeOfPayment: 'Cash',
            iban_active: false,
            errors: [],
            valid: false,
        }
    },
    validations: {
        data: {
            email: {
                required,
            },
            value: {
                required,
                numeric,
            },
            source_description: {
                required
            },
            iban: {
                alpha,
                maxLength: maxLength(25),
            }
        }      
    },
    computed: {
     ibanActive: function(){
         if(this.typeOfPayment == 'Bank Transfer'){
              return this.iban_active = true;
         }
         return this.iban_active = false;
      
     }
   },
    methods: {
        registerIncome: function() {
            this.errors = [];
                console.log(this.data);
                axios.post('api/movements/register/income', this.data)
                .then(response => {
                    // console.log(response.data.wallet_id);
                    let wallet_id = response.data.wallet_id;
                    this.$toast.success("Success", {
                            position: "top-right ",
                            timeout: 5000,
                            closeOnClick: true,
                            pauseOnFocusLoss: true,
                            pauseOnHover: true,
                            draggable: true,
                            draggablePercent: 0.6,  
                            hideCloseButton: false,
                            hideProgressBar: false,
                            icon: true
                    });
                    console.log(wallet_id);
                    console.log(this.data.email);
                    console.log(this.$store.state.user.id);
                    this.$socket.emit('income_movement',wallet_id, this.data.email,this.$store.state.user.id);
                    this.$router.push({ name: "home" });
                })
                .catch(error => {
                    console.log(error);
                    if(error.response.status == 404){
                        this.$toast.error(error.response.data.message, {
                            position: "top-right ",
                            timeout: 5000,
                            closeOnClick: true,
                            pauseOnFocusLoss: true,
                            pauseOnHover: true,
                            draggable: true,
                            draggablePercent: 0.6,
                            hideCloseButton: false,
                            hideProgressBar: false,
                            icon: true
                        });
                    }
                })
        },
        inputRules: function(value){
            if(value == 'Cash' || value == 'Bank Transfer'){
                return true;
            }

            return false;
        },
    },
    mounted() {
            if(this.$store.state.token == ''){
				this.$router.push({name: 'login'})
			}
    },
};
</script>

<style lang="scss" scoped>

  .card{
		position: absolute;
		top: 50%;
		left: 50%;
		margin-right: -50%;
		transform: translate(-50%, -50%);
    width: 500px;
  }

</style>