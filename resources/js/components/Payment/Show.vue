<template>
  <div>


 <v-dialog
      v-model="dialog"
      max-width="290"
    >
      <v-card>
        <v-card-title class="headline">Você tem certeza que deseja reagendar a coleta pra amanhã?</v-card-title>

        <v-card-text>
          Essa ação não poderá ser revertida
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            color="red darken-1"
            text
            @click="dialog = false"
          >
            Cancelar
          </v-btn>

          <v-btn
            color="blue darken-1"
            text
            @click="returnTomorrow()"
          >
            Confirmar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-card flat
            class="transparent">

            <v-toolbar color="grey darken-3">
          
          <v-btn
            fab
            
            color="red accent-2"
            bottom
            right
            absolute
            @click="dialog = true"
          >
            <v-icon>mdi-calendar-arrow-right</v-icon>
          </v-btn>
         <v-toolbar-title class="white--text">{{local.contract.customer.name}}</v-toolbar-title>
          <v-spacer></v-spacer>
      
        </v-toolbar>

            <v-card-text>


<div class="text-xs-center pa-2">
            <v-avatar size="190">
              <v-img :src="local.contract.customer.photo"></v-img>
            </v-avatar>

         

              <div class="grey--text font-weight-light pa-2">Valor a receber</div>
              <span class="display-1">R$ {{local.amount}} </span>


            </div>




      

 <v-layout class="pa-3" row>

   
              <v-flex xs12 v-if="isConfirmedPayment">
                <v-text-field 
                @change="showConfirmButtom = true"
                :autofocus="true"
                        v-model.lazy="item.amount_paid"
                        v-money="money"
                        type="tel"
                        v-validate="{required: true}"
                        data-vv-validate-on="blur"
                        data-vv-as="valor"
                        data-vv-name="item.amount_paid"
                        :error-messages="errors.first('item.amount_paid')"
 solo flat required></v-text-field>
              </v-flex>
             
            </v-layout>
       

        <v-layout row v-if="local.status == 'Pendente'">
          <v-flex>

            <v-btn v-if="!isConfirmedPayment" @click="isConfirmedPayment = true"
                   :loading="isLoading"
                   color="primary"
                   block>Registrar pagamento</v-btn>

         
            <v-btn v-if="showConfirmButtom" @click="confirmPayment()"
                   :loading="isLoading"
                   color="green"
                   block>Confirmar pagamento</v-btn>

          </v-flex>
        </v-layout>
      </v-card-text>

     

    </v-card>

    <v-snackbar
      v-model="isBlocked"
      bottom
      color="red"
    >
      O valor recebido está acima do limite!
      <v-btn
      
        flat
        @click="isBlocked = false"
      >
        Ok
      </v-btn>
    </v-snackbar>

  </div>
</template>
<script>
import { VMoney } from "v-money";
import { mapActions, mapGetters } from "vuex";
export default {
  data: () => ({
    dialog: false,
    isBlocked: false,
    isLoading: false,
    isConfirmedPayment: false,
    showConfirmButtom: false,
    amount: 0,
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "",
        suffix: "",
        precision: 2,
        masked: false
      },
    local: {
      contract: {
        customer: {}
      }
    },
    item: {}
  }),
  methods: {
    ...mapActions("receipt", ["showReceipt", "updateReceipt"]),
   
    confirmPayment() {
      this.isLoading = true;

       let formatted = {
         id: this.receipt.id,
     
          amount_paid: parseFloat(this.item.amount_paid.replace(".", "").replace(",", "."))
       }  


if(formatted.amount_paid > this.receipt.total_balance){
  
  this.isBlocked = true
  this.isLoading = false;
  return false;
}


      this.updateReceipt(formatted).then(response => {


       
        this.isLoading = false;


        this.$router.push({
              name: "Receipt"
            });
      });
    },

        returnTomorrow() {
      this.isLoading = true;

       let formatted = {
         id: this.receipt.id,
          amount_paid: 0
       }  

      this.updateReceipt(formatted).then(response => {

        this.isLoading = false;

        this.$router.push({
              name: "Receipt"
            });
      });
    }
  },

  computed: {
    ...mapGetters("receipt", ["receipt"])
  },
  created() {
        this.showReceipt(this.$route.params.id).then(response => {
          
        this.local = this.receipt;

        this.item = {
          id: this.receipt.id,
          amount_paid: this.receipt.amount_paid
        };


      });
  },



  
   directives: { money: VMoney }
};
</script>
