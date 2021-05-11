<template>
  <v-container v-if="customers.meta.total == 0" fill-height>
    <v-layout justify-center align-center>
      <v-flex shrink>
        <h3>Nenhum cliente cadastrado.</h3>

        <v-btn @click="$router.push({ name: 'CustomerStore' })" flat
          >Cadastrar meu primeiro cliente</v-btn
        >
      </v-flex>
    </v-layout>
  </v-container>

  <div v-else>
    <v-toolbar color="grey darken-3" tabs>
      <v-text-field
        v-model="search"
        full-width
        hide-details
        append-icon="mdi-magnify"
        label="Pesquisar"
      ></v-text-field>

      <v-btn @click="$router.push({ name: 'CustomerStore' })" flat>
        <v-icon left>mdi-account-plus</v-icon>Novo
      </v-btn>
    </v-toolbar>

    <v-list class="transparent">
      <template v-for="(item, index) in filteredList">
        <v-list-tile
          v-if="item.total_contracts > 0"
          :key="`contract-${index}`"
          avatar
          @click="
            $router.push({ name: 'CustomerShow', params: { id: item.id } })
          "
        >
          <v-list-tile-avatar>
            <v-icon>mdi-account-circle</v-icon>
          </v-list-tile-avatar>

          <v-list-tile-content>
            <v-list-tile-title v-html="item.name"></v-list-tile-title>
          </v-list-tile-content>

          <v-list-tile-action>
            <v-chip v-if="item.total_contracts > 0" color="primary" small>
              <v-avatar>
                <v-icon>mdi-file-document-outline</v-icon>
              </v-avatar>
              {{ item.total_contracts }}
            </v-chip>

            <v-chip v-else color="grey" small label>Nenhum</v-chip>
          </v-list-tile-action>
        </v-list-tile>
        <v-divider :key="item.id"></v-divider>
      </template>
    </v-list>

    <v-list class="hidden-md-and-up transparent">
      <template v-for="(item, index) in filteredList">
        <v-list-tile
          v-if="item.total_contracts == 0"
          :key="`nocontract-${index}`"
          avatar
          @click="
            $router.push({ name: 'CustomerShow', params: { id: item.id } })
          "
        >
          <v-list-tile-avatar>
            <v-icon>mdi-account-circle</v-icon>
          </v-list-tile-avatar>

          <v-list-tile-content>
            <v-list-tile-title v-html="item.name"></v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </template>
    </v-list>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      search: "",
      isLoading: true,
    };
  },
  computed: {
    ...mapGetters("customer", ["customers"]),
    filteredList() {
      return this.customers.data.filter((customer) => {
        return customer.name.toLowerCase().includes(this.search.toLowerCase());
      });
    },
  },

  methods: {
    ...mapActions("customer", ["showCustomers"]),
  },
  mounted() {
    this.showCustomers().then((response) => {
      this.isLoading = false;
    });
  },
};
</script>
