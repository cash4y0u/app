<template>
  <v-form>
    <v-container>

      <v-layout row
                wrap>
        <v-flex xs8>

          <v-text-field :autofocus="true"
                        v-model.lazy="amount"
                        v-money="money"
                        type="tel"
                        v-validate="{required: true}"
                        data-vv-validate-on="blur"
                        data-vv-as="valor"
                        data-vv-name="contract.amount"
                        :error-messages="errors.first('contract.amount')"
                        label="Valor"></v-text-field>
        </v-flex>
        <v-flex xs4>
          <v-text-field v-model="contract.split"
                        type="tel"
                        v-validate="{required: true, min_value: 1}"
                        data-vv-validate-on="blur"
                        data-vv-as="parcelas"
                        data-vv-name="contract.split"
                        :error-messages="errors.first('contract.split')"
                        label="Parcelas"></v-text-field>

        </v-flex>
      </v-layout>

      <v-layout row>

        <v-flex xs11
                class="pt-3">
          <v-slider v-model="contract.rate"
                    always-dirty
                    :color="color"
                    thumb-label="always"
                    thumb-size="46"
                    max="300"
                    min="0.1"
                    step="0.1">
            <template v-slot:prepend>
              <v-icon @click="contract.rate -= 0.1">
                mdi-minus
              </v-icon>
            </template>

            <template v-slot:append>
              <v-icon @click="contract.rate += 0.1">
                mdi-plus
              </v-icon>
            </template>
          </v-slider>

        </v-flex>

        <v-flex>
          <v-progress-circular :rotate="90"
                               :size="80"
                               :width="4"
                               :value="contract.rate * 4"
                               :color="color">
            <span>{{estimating}}</span>
          </v-progress-circular>
        </v-flex>

      </v-layout>

      <v-layout row>
        <v-flex xs6>
          <v-select v-model="contract.cycle"
                    :items="cycles"
                    prepend-inner-icon="mdi-update"
                    label="Ciclo de cobrança"></v-select>
        </v-flex>

        <v-flex xs6>

          <v-dialog ref="dialog"
                    v-model="modal"
                    :return-value.sync="contract.maturity"
                    persistent
                    lazy
                    full-width
                    width="290px">
            <template v-slot:activator="{ on }">
              <v-text-field :value="date"
                            v-validate="{required: true}"
                            data-vv-as="valor"
                            data-vv-name="contract.maturity"
                            :error-messages="errors.first('contract.maturity')"
                            label="Vencimento"
                            prepend-inner-icon="mdi-calendar-range"
                            readonly
                            v-on="on"></v-text-field>
            </template>
            <v-date-picker v-model="contract.maturity"
                           locale="pt-br">
              <v-spacer></v-spacer>
              <v-btn flat
                     color="primary"
                     @click="$refs.dialog.save(contract.maturity)">OK</v-btn>
            </v-date-picker>
          </v-dialog>
        </v-flex>

      </v-layout>

      <v-layout row>

        <v-flex>
          <v-slider v-model="contract.rate_daily"
                    thumb-label
                    :label="`Juros de ${contract.rate_daily}% por atraso`"
                    max="10"
                    min="0"
                    step="1">
          </v-slider>

        </v-flex>

      </v-layout>

      <v-btn @click="openResume()"
             color="primary"
             block>Calcular</v-btn>

      <v-dialog v-model="visibilityResume"
                fullscreen
                hide-overlay
                transition="dialog-bottom-transition">

        <v-card>
          <v-toolbar dark
                     color="primary">
            <v-btn icon
                   :disabled="isLoading"
                   dark
                   @click="visibilityResume = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title>Estimativa do contrato</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn dark
                     :loading="isLoading"
                     flat
                     @click="store()">Lançar</v-btn>
            </v-toolbar-items>
          </v-toolbar>

          <v-divider></v-divider>
          <v-list dense>
            <v-list-tile>
              <v-list-tile-content>Valor:</v-list-tile-content>
              <v-list-tile-content class="align-end">R$ {{contract.amount}}</v-list-tile-content>
            </v-list-tile>

            <v-list-tile>
              <v-list-tile-content>Data da primeira parcela:</v-list-tile-content>
              <v-list-tile-content class="align-end">{{ date }}</v-list-tile-content>
            </v-list-tile>

            <v-list-tile>
              <v-list-tile-content>Parcelas:</v-list-tile-content>
              <v-list-tile-content class="align-end">{{ contract.split }}</v-list-tile-content>
            </v-list-tile>

            <v-list-tile>
              <v-list-tile-content>Taxa:</v-list-tile-content>
              <v-list-tile-content class="align-end">{{ contract.rate }}%</v-list-tile-content>
            </v-list-tile>

            <v-list-tile>
              <v-list-tile-content>Ciclo de cobrança:</v-list-tile-content>
              <v-list-tile-content class="align-end">{{ cycle }}</v-list-tile-content>
            </v-list-tile>

            <v-list-tile>
              <v-list-tile-content>Valor da parcela:</v-list-tile-content>
              <v-list-tile-content class="align-end">R$ {{ (contract.amount_provision) }}</v-list-tile-content>
            </v-list-tile>

            <v-list-tile>
              <v-list-tile-content>Juros da parcela:</v-list-tile-content>
              <v-list-tile-content class="align-end">R$ {{ contract.amount_rate }}</v-list-tile-content>
            </v-list-tile>

            <v-list-tile>
              <v-list-tile-content>Valor total do contrato:</v-list-tile-content>
              <v-list-tile-content class="align-end">R$ {{ contract.amount_total }}</v-list-tile-content>
            </v-list-tile>

            <v-list-tile>
              <v-list-tile-content>Lucro do contrato:</v-list-tile-content>
              <v-list-tile-content class="align-end">R$ {{ contract.amount_profit }}</v-list-tile-content>
            </v-list-tile>

          </v-list>
        </v-card>
      </v-dialog>

    </v-container>

  </v-form>

</template>

<script>
import { VMoney } from "v-money";
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      amount: 0,
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "",
        suffix: "",
        precision: 2,
        masked: false
      },
      modal: false,
      calculating: false,
      visibilityResume: false,
      isLoading: false,
      contract: {
        customer_id: this.$route.params.id,
        amount: 0,
        amount_provision: "",
        amount_rate: "",
        amount_profit: "",
        amount_total: "",
        rate: 0.1,
        rate_daily: 3,
        cycle: "monthly",
        maturity: "",
        split: ""
      },
      estimating: 0,
      amount: {},
      resume: {},

      date: "",
      cycle: "",
      cycles: [
        {
          text: "Diário",
          value: "daily"
        },
        {
          text: "Mensal",
          value: "monthly"
        }
      ]
    };
  },

  methods: {
    ...mapActions("contract", ["storeContract"]),
    openResume() {
      this.$validator.validate().then(result => {
        if (result) {
          this.visibilityResume = true;
        }
      });
    },

    calculate() {
      const rate = this.contract.rate / 100;
      const split = this.contract.split;

      const amount = parseFloat(this.amount.replace(".", "").replace(",", "."));
      const amount1 = rate * Math.pow(1 + rate, split);
      const amount2 = Math.pow(1 + rate, split) - 1;
      const amount_provision = Math.round(amount * (amount1 / amount2));
      const amount_rate = Math.round(amount_provision - amount / split);
      const amount_profit = Math.round(amount_provision * split - amount);
      const amount_total = Math.round(amount_provision * split);

      this.contract.amount = amount;

      this.contract.amount_provision = amount_provision;
      this.contract.amount_rate = amount_rate;
      this.contract.amount_profit = amount_profit;
      this.contract.amount_total = amount_total;

      this.estimating = parseFloat(amount_provision).toLocaleString("pt-BR", {
        minimumFractionDigits: 2
      });
    },
    store() {
      this.isLoading = true;
      this.storeContract(this.contract).then(response => {
        this.isLoading = false;
        this.visibilityResume = false;
        this.$router.push({
          name: "ContractShow",
          params: { id: response.data.id }
        });
      });
    }
  },

  watch: {
    amount: {
      handler(value) {
        this.calculate();
      }
    },
    "contract.split": {
      handler(value) {
        this.calculate();
      }
    },
    "contract.rate": {
      handler(value) {
        this.calculate();
      }
    },
    "contract.maturity": {
      handler(value) {
        const today = Date.parse(`${value}T10:30:00-03:00`);
        this.date = new Date(today).toLocaleDateString();
      }
    },
    "contract.cycle": {
      handler(value) {
        switch (this.contract.cycle) {
          case "daily":
            this.cycle = "Diário";
            break;
          case "monthly":
            this.cycle = "Mensal";
            break;
        }
      }
    }
  },

  computed: {
    color() {
      if (this.contract.rate < 1) return "green";
      if (this.contract.rate < 20) return "blue";
      if (this.contract.rate < 30) return "yellow darken-2";
      if (this.contract.rate < 80) return "yellow darken-4";
      if (this.contract.rate < 100) return "deep-orange accent-2";
      if (this.contract.rate < 200) return "deep-orange accent-3";
      return "red accent-4";
    }
  },

  directives: { money: VMoney }
};
</script>
