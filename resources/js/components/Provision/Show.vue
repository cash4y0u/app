<template>
  <div>

    <v-card flat
            class="transparent">

      <v-img :src="local.contract.customer.photo"
             gradient="to top, rgba(0,0,0,.44), rgba(0,0,0,.44)"
             height="200px">
        <v-layout column
                  fill-height>
          <v-card-title>

            <v-btn flat
                   small
                   @click="$router.push({name: 'CustomerShow', params:{id: local.contract.customer.id}})"> {{local.contract.customer.name}}</v-btn>

            <v-spacer></v-spacer>

            <v-btn flat
                   small
                   fab
                   @click="$router.push({name: 'ContractShow', params:{id: local.contract.id}})">
              <v-icon>mdi-file-document-outline</v-icon>
            </v-btn>

          </v-card-title>

          <v-spacer></v-spacer>

          <v-card-title>
            <div>
              <div class="grey--text font-weight-light">Valor da parcela</div>
              <span class="display-1">R$ {{local.amount}} </span>

              <span v-if="local.isExpired"> + R$ {{local.amountFine}} de juros por atraso</span>

            </div>

            <v-spacer></v-spacer>

          </v-card-title>

        </v-layout>
      </v-img>

      <v-card-title>

        <v-chip>
          <v-icon left>mdi-calendar</v-icon>
          {{local.maturity}}
        </v-chip>

        <v-spacer></v-spacer>

        <v-chip dark
                :color="local.status == 'Pago' ? 'success' : 'error'">
          {{local.status}}
        </v-chip>

      </v-card-title>

      <v-card-text>

        <v-layout row
                  v-if="local.status == 'Pendente'">
          <v-flex>

            <v-switch v-model="local.minimum_payment"
                      label="Pagamento mínimo"></v-switch>

          </v-flex>

        </v-layout>

        <v-layout row
                  v-else>
          <v-flex>
            <v-alert :value="true"
                     color="success"
                     icon="mdi-check-circle"
                     outline>
              Valor de R$ {{local.amount_paid}} recebido em {{local.paid_at}}
            </v-alert>

          </v-flex>

        </v-layout>

        <v-layout row
                  v-if="local.status == 'Pendente' && local.minimum_payment">
          <v-flex>
            <v-alert :value="true"
                     color="info"
                     icon="mdi-information"
                     outline>
              O valor mínimo deve ser de R$ {{local.contract.amount_rate}}
            </v-alert>

          </v-flex>

        </v-layout>

        <v-layout row
                  v-if="local.status == 'Pendente'">
          <v-flex>

            <v-btn @click="confirmPayment()"
                   :loading="isLoading"
                   color="primary"
                   block>Confirmar pagamento</v-btn>

          </v-flex>
        </v-layout>
      </v-card-text>

    </v-card>

  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data: () => ({
    isLoading: false,
    local: {
      contract: {}
    }
  }),
  methods: {
    ...mapActions("provision", ["showProvision", "updateProvision"]),
    loadProvision() {
      this.showProvision(this.$route.params.id).then(response => {
        this.local = this.provision;
      });
    },
    confirmPayment() {
      this.isLoading = true;
      this.updateProvision(this.provision).then(response => {
        this.loadProvision();
        this.isLoading = false;
      });
    }
  },

  computed: {
    ...mapGetters("provision", ["provision"])
  },
  created() {
    this.loadProvision();
  }
};
</script>
