<template>
  <v-container v-if="item.meta.remaining == 0" fill-height>
    <v-layout justify-center align-center>
      <v-flex shrink>
        <h1>
          Bom trabalho
          <v-icon x-large>mdi-thumb-up</v-icon>
        </h1>
        <h3>Todas coletas realizadas com sucesso</h3>
      </v-flex>
    </v-layout>
  </v-container>

  <v-card v-else class="mx-auto transparent" flat>
    <v-card dark>
      <v-btn @click="openWaze()" absolute bottom color="primary" right fab>
        <v-icon>mdi-google-maps</v-icon>
      </v-btn>
      <v-container fill-height>
        <v-layout align-center>
          <strong class="display-4 font-weight-regular mr-4">{{
            item.meta.date.day
          }}</strong>
          <v-layout column justify-end>
            <div class="headline font-weight-light">
              {{ item.meta.date.weekname }}
            </div>
            <div class="text-uppercase font-weight-light">
              {{ item.meta.date.month }} {{ item.meta.date.year }}
            </div>
          </v-layout>
        </v-layout>
      </v-container>
    </v-card>
    <v-card-text class="py-0 transparent">
      <v-timeline align-top dense>
        <v-timeline-item
          large
          fill-dot
          color="grey darken-3"
          v-for="(item, index) in item.data"
          :key="index"
        >
          <template v-slot:icon>
            <v-avatar>
              <v-img :src="`${item.contract.customer.photo}`"></v-img>
            </v-avatar>
          </template>

          <div
            v-for="(address, index) in item.contract.customer.adresses"
            :key="index"
          >
            <template v-if="address.favorite">
              <v-layout pt-3>
                <v-flex>
                  {{ item.contract.customer.name }}
                  <p>{{item.type}} - R${{item.valuecoletaentrega}}</p>
                  {{ address.street }}, {{ address.number }}
                  <span v-if="address.complement"
                    >- {{ address.complement }}</span
                  >
                <v-chip small
                      outline class="right">{{item.distancia} km}</v-chip>
                </v-flex>
              </v-layout>
            </template>
          </div>
        </v-timeline-item>
      </v-timeline>
    </v-card-text>
    <v-layout justify-center align-center>
      <v-flex shrink> </v-flex>
    </v-layout>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      item: {
        meta: {
          date: {},
        },
      },
      dialog: false,
    };
  },

  methods: {
    ...mapActions("receipt", ["showReceipts", "updateReceipt"]),

    openWaze() {
      window.open(this.item.meta.comgooglemaps, "_blank");
    },

    received(item) {
      this.updateReceipt(item).then((response) => {});
    },
  },

  created() {
    this.showReceipts().then((response) => {
      this.item = response.data;
    });
  },

  computed: {
    ...mapGetters("receipt", ["receipts"]),
  },
};
</script>
