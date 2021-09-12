<template>

  <v-list class="transparent"
          two-line>

    <template v-for="item in provisions.data">

      <div v-if="item.status == 'Pendente'"
           :key="item.id">
        <v-list-tile three-line :key="item.id"
                     
                     @click="$router.push({name: 'ProvisionShow', params:{id: item.id}})">


          <v-list-tile-content>
            <v-list-tile-title>{{item.customer.name}}</v-list-tile-title>
            <v-list-tile-sub-title>{{ item.maturity }} <v-chip small label><small>{{item.dayOfWeek}}</small></v-chip></v-list-tile-sub-title>
            <v-list-tile-sub-title class="text--primary"
            >R$ {{ item.amount }}</v-list-tile-sub-title>
            <v-list-tile-sub-title v-for="(item, index) in item.customer.adresses" :key="index">
              <div v-if="item.favorite">{{item.street}}, {{item.number}} - {{item.district}}</div> 
            </v-list-tile-sub-title>
          </v-list-tile-content>

          <v-list-tile-action>
            <v-list-tile-action-text>

              <v-chip v-if="item.isExpired" color="red">
                <small> Venceu {{item.diffForHumans}}</small>
              </v-chip>

            </v-list-tile-action-text>

          </v-list-tile-action>
        </v-list-tile>
        <v-divider :key="item.id"></v-divider>
      </div>
    </template>

  </v-list>

</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      loading: false,
    };
  },
  methods: {
    ...mapActions("provision", ["showProvisions"]),


  },

  computed: {
    ...mapGetters("provision", ["provisions"])
  },
  created() {
    this.showProvisions();
  }
};
</script>
