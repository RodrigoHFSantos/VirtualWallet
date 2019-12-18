<template>
     <v-app id="inspire">
    <v-content>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
              <v-toolbar color="primary" dark flat>
                <v-toolbar-title>Register an Income</v-toolbar-title>
                <v-spacer />
              </v-toolbar>
              <v-card-text>
                <v-form id="check-login-form">
                    <p v-if="errors.length">
                        <b>Please correct the following error(s):</b>
                        <ul>
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </p>

                    <v-text-field
                        label="Email"
                        name="email"
                        type="email"
                        id="email"
                        v-model="data.email"
                    />

                    <v-text-field
                        id="value"
                        label="Value (From 0,01€ up to 5000,00€)"
                        name="value"
                        type="value"
                        v-model="data.value"
                    />
                     <v-flex xs12 sm6 d-flex>
                        <v-select
                            :items="items"
                            label="Type of Payment"
                            outline
                            v-model="typeOfPayment"
                        ></v-select>
                    </v-flex>

                    <v-text-field
                        v-if="ibanActive"
                        id="iban"
                        label="IBAN (2 capital letters followed by 23 digits)"
                        name="iban"
                        type="iban"
                        v-model="data.iban"
                    />

                    <v-text-field
                        id="source_description"
                        label="Source Description"
                        name="source_description"
                        type="source_description"
                        v-model="data.source_description"
                    />
                <br>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer />
                <v-btn color="primary" form="check-login-form" @click="registerIncome">Register Income</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  export default {
    name: 'registerIncome',

    data: function() {
        return {
            data: {
                email: null,
                value: null,
                type_payment: null,
                source_description: null,
                iban: null,
            },
            items: ['Cash', 'Bank Transfer'],
            typeOfPayment: null,
            iban_active: false,
            errors: []
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
            if(this.checkForm()){
                axios.post('api/movements/register/income', this.data)
                .then(response => {
                    console.log(response);
                    this.data.email = '';
                    this.data.value = '';
                    this.data.type_payment = '';
                    this.data.source_description = '';
                    this.data.iban = '';
                    // this.$router.push({ name: "home" });
                })
                .catch(error => {
                    console.log(error.response.data.message);
                })
            }
        },
        checkForm: function () {
            if (this.data.email && this.data.value && this.typeOfPayment && this.data.source_description) {
                if(0>=this.data.value>5000){
                    this.errors.push('Value must be between 0 and 5000.')
                }else{
                    if(this.typeOfPayment == 'Bank Transfer'){
                        this.data.type_payment = 'mb';
                        if(this.data.iban){
                            return true;
                        }
                    }
                    this.data.type_payment = 'c';
                    return true;
                }
                
            }

            if (!this.data.email) {
                this.errors.push('Email required.');
            }

            if (!this.data.value) {
                this.errors.push('Value required.');
            }

            // if(0>=this.data.value>5000){
            //     this.errors.push('Value must be between 0 and 5000.')
            // }

            if (!this.typeOfPayment) {
                this.errors.push('Type of payment required.');
            }

            if(this.typeOfPayment == 'Bank Transfer'){
                if(!this.data.iban){
                    this.errors.push('IBAN requeired.');
                }
            }

            if(!this.data.source_description) {
                this.errors.push('Source Descritpion required.');
            }
        },
    }
  }
</script>