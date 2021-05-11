<template>
  <v-container fluid
               grid-list-md>

             

    <v-data-iterator v-if="user.isAdmin" :items="items"
                     content-tag="v-layout"
                     row
                     wrap
                     hide-actions>
      <template v-slot:item="props">
        <v-flex xs12
                sm6
                md4
                lg3>
          <v-card>
            <v-card-title>
              <h4>{{ props.item.month }}</h4>
            </v-card-title>
            <v-divider></v-divider>
            <v-list dense>
              <v-list-tile>
                <v-list-tile-content>Investido:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ formattedAmount(props.item.amount) }}</v-list-tile-content>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-content>Rendimento:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ formattedAmount(props.item.amount_provision) }}</v-list-tile-content>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-content>Lucro:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ formattedAmount(props.item.amount_rate) }}</v-list-tile-content>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-content>Ganho:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ parseFloat(( props.item.amount_rate * 100 ) / props.item.amount).toFixed(2) }}%</v-list-tile-content>
              </v-list-tile>
            </v-list>
          </v-card>
        </v-flex>
      </template>
    </v-data-iterator>
  </v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      items: []
    };
  },
  computed: {
    ...mapGetters("auth", ["user"]),
  },
  methods: {
    loadCharts() {
      axios.get(`/api/charts`).then(response => {
        console.log(response.data);
        this.items = response.data;
      });
    },
    formattedAmount(amount) {
      return parseFloat(amount).toLocaleString("pt-BR", {
        minimumFractionDigits: 2,
        style: "currency",
        currency: "BRL"
      });
    }
  },

  created() {
    this.loadCharts();
  }
};
</script>
