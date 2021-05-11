<template>

  <v-list class="transparent"
          two-line>

    <template v-for="item in provisions.data">

      <div v-if="item.status == 'Pago'"
           :key="item.id">
        <v-list-tile three-line :key="item.id"
                     
                     @click="$router.push({name: 'ProvisionShow', params:{id: item.id}})">


          <v-list-tile-content>
            <v-list-tile-title>{{item.customer.name}}</v-list-tile-title>
            <v-list-tile-sub-title>{{ item.maturity }} <v-chip small label><small>{{item.dayOfWeek}}</small></v-chip></v-list-tile-sub-title>
            <v-list-tile-sub-title>R$ {{ item.amount }}</v-list-tile-sub-title>
          </v-list-tile-content>

          <v-list-tile-action>
            <v-list-tile-action-text>

              <v-avatar>
                <v-icon v-text="item.status == 'Pago' ? 'mdi-check-circle' : 'mdi-close-circle'"></v-icon>
              </v-avatar>

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
      filters: [],
      items: [
        { text: "Pendente", value: "pending" },
        { text: "Pago", value: "paid" }
      ]
    };
  },
  methods: {
    ...mapActions("provision", ["showProvisions"]),

    search() {
      this.loading = true;
      this.showProvisions(this.filters)
        .then(response => {
          this.loading = false;
        })
        .catch(error => {
          this.loading = false;
        });
    }
  },

  computed: {
    ...mapGetters("provision", ["provisions"])
  },
  created() {
    this.showProvisions();
  }
};
</script>
