<template>
  <div>

    <v-list class="transparent"
            dense
            two-line>

      <v-list-tile @click="$router.push({name: 'CustomerShow', params:{id: contract.customer.id}})"
                   avatar>
        <v-list-tile-avatar size="50">
          <v-img :src="contract.customer.photo"></v-img>
        </v-list-tile-avatar>
        <v-list-tile-content>
          <v-list-tile-title>{{contract.customer.name}}</v-list-tile-title>
          <v-list-tile-sub-title>Firmado em {{contract.created_at}} - {{contract.diffForHumans}}</v-list-tile-sub-title>

        </v-list-tile-content>
      </v-list-tile>

      <v-divider></v-divider>

      <v-list-tile>
        <v-list-tile-content>
          <v-list-tile-title>Valor do empréstimo</v-list-tile-title>
          <v-list-tile-sub-title>R$ {{contract.amount}} </v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-divider></v-divider>

      <v-list-tile>
        <v-list-tile-content>
          <v-list-tile-title>Valor da parcela</v-list-tile-title>
          <v-list-tile-sub-title>{{contract.split}} X R$ {{contract.amount_provision}}</v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-divider></v-divider>

      <v-list-tile>
        <v-list-tile-content>
          <v-list-tile-title>Juros da parcela</v-list-tile-title>
          <v-list-tile-sub-title>R$ {{contract.amount_rate}}</v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-divider></v-divider>

      <v-list-tile>
        <v-list-tile-content>
          <v-list-tile-title>Total a pagar</v-list-tile-title>
          <v-list-tile-sub-title>R$ {{contract.amount_total}} - Juros: {{contract.rate}}% A.M</v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-divider></v-divider>

      <v-list-tile>
        <v-list-tile-content>
          <v-list-tile-title>Lucro</v-list-tile-title>
          <v-list-tile-sub-title>R$ {{contract.amount_profit}}</v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>

    </v-list>

    <v-container>
      <div class="text-xs-center">
     
            <v-btn @click="$router.push({name: 'ContractProvision', params:{id: contract.id}})" class="mx-2" fab dark color="primary">
      <v-icon dark>mdi-animation</v-icon>
    </v-btn>


      <v-btn @click="dialog = true" class="mx-2" fab dark color="red">
      <v-icon dark>mdi-delete</v-icon>
    </v-btn>

      </div>

    </v-container>

   


    <v-dialog
      v-model="dialog"
      max-width="290"
    >
      <v-card>
        <v-card-title class="headline">Você tem certeza que deseja excluir?</v-card-title>

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
            @click="trash()"
          >
            Confirmar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>




  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      dialog: false
    };
  },

  computed: {
    ...mapGetters("contract", ["contract"])
  },

  methods: {
    ...mapActions("contract", ["showContract", "destroyContract"]),

    trash(){
      let customer = this.contract.customer.id;
      this.destroyContract(this.$route.params.id).then(response => {
        this.$router.push({name: 'CustomerShow', params:{id: customer}});
      });

      
    }
  },
  created() {
    this.showContract(this.$route.params.id);
  }
};
</script>
