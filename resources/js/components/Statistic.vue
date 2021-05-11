<template>
  <v-container grid-list-md
               text-xs-center>
    <v-layout row
              wrap>

      <v-flex xs12>
        <v-card>
          <v-card-title>
            <v-icon color="red lighten-2"
                    class="mr-5"
                    size="44">
              mdi-bank-transfer-out
            </v-icon>
            <v-layout column
                      align-start>
              <div class="caption grey--text text-uppercase">
                Total investido
              </div>
              <div>
                <span class="display-1 font-weight-black">{{statistics.invested}}</span>

              </div>
            </v-layout>

          </v-card-title>

        </v-card>

      </v-flex>

      <v-flex xs12>

        <v-card>
          <v-card-title>
            <v-icon color="green lighten-2"
                    class="mr-5"
                    size="44">
              mdi-checkbox-multiple-marked-circle-outline
            </v-icon>
            <v-layout column
                      align-start>
              <div class="caption grey--text text-uppercase">
                Você já recebeu
              </div>
              <div>
                <span class="display-1 font-weight-black">{{statistics.yielded}}</span>

              </div>
            </v-layout>

          </v-card-title>

        </v-card>
      </v-flex>


      <v-flex xs6 v-for="(amount,year) in statistics.prediction.details" :key="amount">



        <v-card>
          <v-card-title>
            <v-icon color="blue lighten-2"
                    class="mr-5"
                    size="24">
              mdi-chart-line-variant
            </v-icon>
            <v-layout column
                      align-start>
              <div class="caption grey--text text-uppercase">
                {{year}}
              </div>
              <div>
                <span class="overline">R$</span> <span class="subtitle-1 font-weight-black"> {{amount}}</span>

              </div>
            </v-layout>

          </v-card-title>

        </v-card>
      </v-flex>

    </v-layout>

  </v-container>
</template>


<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {};
  },

  computed: {
    ...mapGetters("statistic", ["statistics"])
  },

  methods: {
    ...mapActions("statistic", ["showStatistics"])
  },
  created() {
    this.showStatistics();
  }
};
</script>
