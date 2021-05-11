<template>

  <v-container v-if="contracts.meta.total == 0"
               fill-height>

    <v-layout justify-center
              align-center>
      <v-flex shrink>
        <h3>Nenhum contrato cadastrado.</h3>
      </v-flex>
    </v-layout>
  </v-container>

  <div v-else>

    <v-list two-line
            class="transparent">

      <template v-for="item in contracts.data">

        <v-list-tile :key="item.id"
                     avatar
                     @click="$router.push({name: 'ContractShow', params:{id: item.id}})">
          <v-list-tile-avatar :size="55">
            <v-progress-circular :rotate="90"
                                 :size="55"
                                 :width="2"
                                 :value="item.rate"
                                 :color="color(item.rate)">
              <span class="caption">{{item.rate}}%</span>
            </v-progress-circular>
          </v-list-tile-avatar>

          <v-list-tile-content class="ml-3">
            <v-list-tile-title>{{ item.customer.name }} <v-chip :color="item.cycle == 'diário' ? 'blue darken-1' : 'light-green darken-1'" label small><small>{{item.cycle}}</small></v-chip></v-list-tile-title>
            <v-list-tile-sub-title>Contratou R$ {{ item.amount }} {{item.diffForHumans}}</v-list-tile-sub-title>
          </v-list-tile-content>

          <v-list-tile-action>

            <v-icon :color="item.status == 'Quitado' ? 'light-green lighten-1' : 'grey lighten-2'"
                    v-text="item.status == 'Quitado' ? 'mdi-check-circle-outline' : 'mdi-checkbox-blank-circle-outline'"></v-icon>

          </v-list-tile-action>
        </v-list-tile>

        <v-divider :key="item.id"
                   inset></v-divider>
      </template>

    </v-list>

  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {};
  },

  computed: {
    ...mapGetters("contract", ["contracts"])
  },

  methods: {
    ...mapActions("contract", ["showContracts"]),
    color(rate) {
      if (rate < 1) return "green";
      if (rate < 20) return "blue";
      if (rate < 30) return "yellow darken-2";
      if (rate < 80) return "yellow darken-4";
      if (rate < 100) return "deep-orange accent-2";
      if (rate < 200) return "deep-orange accent-3";
      return "red accent-4";
    }
  },
  created() {
    this.showContracts();
  }
};
</script>
