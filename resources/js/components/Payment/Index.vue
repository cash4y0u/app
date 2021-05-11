<template>



  <v-card
    class="mx-auto transparent"
    flat
    max-width="400"
  >



    <v-card-text class="py-0 transparent">
      <v-timeline
        align-top
        dense
      >
      <v-timeline-item
          
          

        >

        <template v-slot:icon>
       <v-progress-circular
      :rotate="360"
      :size="100"
      :width="3"
      :value="( payments.meta.collected * 100 ) / payments.meta.remaining"
      
    >
      <small>{{ Math.round(( payments.meta.collected * 100 ) / payments.meta.remaining).toFixed(0) }}%</small>
    </v-progress-circular>
      </template>

          <v-layout pt-3>
            <v-flex xs4>
             Total coletado:
            </v-flex>
            <v-flex>
              <strong>R$ {{payments.meta.amount}}</strong>
             
            </v-flex>
          </v-layout>
        </v-timeline-item>


<template v-for="collect in payments.data"
         >

        <v-timeline-item
         
          large
          
         v-bind:key="collect.id"
         v-if="collect.isReceived"
        >

        <template v-slot:icon>
        <v-avatar>
          <img :src="collect.contract.customer.photo">
        </v-avatar>
      </template>

          <v-layout pt-3>
            <v-flex xs3>
              <strong>{{collect.received_at}}</strong>
            </v-flex>
            <v-flex>
              <strong>{{collect.contract.customer.name}}</strong>
              <div class="caption">Valor recebido: {{collect.amount_paid}}</div>
            </v-flex>
          </v-layout>
        </v-timeline-item>
        
        </template>

        
      </v-timeline>
    </v-card-text>

     <v-layout>
          <v-flex>

            <v-btn @click="confirmation = true"
                    :disabled="payments.meta.collected == 0"
                   :loading="isLoading"
                   color="primary"
                   block>Contabilizar pagamentos</v-btn>

         
           

          </v-flex>
        </v-layout>


    <v-dialog
      v-model="confirmation"
     
    >
      <v-card>
        <v-card-title class="headline">Você tem certeza que deseja confirmar os pagamentos listados?</v-card-title>

        <v-card-text>
          Essa ação não pode ser revertida e poderá te causar prejuízos.
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            color="red darken-1"
            text
            @click="confirmation = false"
          >
            Cancelar
          </v-btn>

          <v-btn
            color="blue darken-1"
            text
            @click="confirmPayment()"
          >
            Confirmar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-card>


  

</template>



<script>

import { mapState, mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      isLoading: false,
      confirmation: false,
        item: {
            meta: {}
        }
    };
  },

  methods: {
    ...mapActions("payment", ["showPayments", "storePayment"]),


confirmPayment() {
      this.isLoading = true;
      this.storePayment(this.payments.data).then(response => {
        this.isLoading = false;
        this.confirmation = false;
        this.showPayments();
        this.$router.push({
              name: "Payment"
            });
      });
    },

  },

  created() {
        this.showPayments();
  },

  computed: {
    ...mapGetters("payment", ["payments"])
  },
 
};
</script>
