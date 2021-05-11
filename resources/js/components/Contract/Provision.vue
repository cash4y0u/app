<template>

  <v-list class="transparent"
          
          three-line>
    <template v-for="item in provisions.data">



      <v-list-tile :key="item.number"
                   avatar
                   @click="$router.push({name: 'ProvisionShow', params:{id: item.id}})">
        <v-list-tile>
          <v-avatar :color="item.status == 'Pago' ? 'light-green lighten-1' : 'grey darken-3'">
            <span class="white--text subheading">{{item.number}}</span>
          </v-avatar>
        </v-list-tile>

        <v-list-tile-content>
          <v-list-tile-title>{{ item.maturity }} <v-chip small label><small>{{item.dayOfWeek}}</small></v-chip></v-list-tile-title>
          <v-list-tile-sub-title class="text--primary">Valor da parcela: R$ {{ item.amount }} </v-list-tile-sub-title>
          <v-list-tile-sub-title>Valor pago: R$ {{ item.amount_paid }} </v-list-tile-sub-title>

        </v-list-tile-content>

        <v-list-tile-action>

          
              

                 <v-progress-circular v-if="!item.amount_paid == '0,00'"
      indeterminate
      color="red"
    ></v-progress-circular>

    <v-icon else :color="item.status == 'Pago' ? 'light-green lighten-1' : 'grey lighten-2'"
                  v-text="item.status == 'Pago' ? 'mdi-check-decagram' : 'mdi-close-octagon'"></v-icon>

        </v-list-tile-action>
      </v-list-tile>
      <v-divider inset
                 :key="item.number"></v-divider>
    </template>

  </v-list>

</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {};
  },
  methods: {
    ...mapActions("provision", ["showProvisionsByContract"])
  },

  computed: {
    ...mapGetters("provision", ["provisions"])
  },
  created() {
    this.showProvisionsByContract(this.$route.params.id);
  }
};
</script>
