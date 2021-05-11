<template>
  <div id="app">
    <v-app dark>
      <v-toolbar app dark>
        <v-btn icon @click="$router.push({ name: 'Receipt' })">
          <v-icon>mdi-google-maps</v-icon>
        </v-btn>

        <v-spacer></v-spacer>
        <v-icon @click="goStatistics()">mdi-finance</v-icon>
        <v-toolbar-title @click="goHome()">Cash4You</v-toolbar-title>

        <v-spacer></v-spacer>

        <v-avatar @click="calendar(token)" v-if="logged" size="36">
          <img :src="user.picture" :alt="user.name" />
        </v-avatar>

        <v-btn v-if="logged" icon @click="exit()">
          <v-icon>mdi-power</v-icon>
        </v-btn>
      </v-toolbar>

      <v-content>
        <router-view :key="$route.fullPath"></router-view>
      </v-content>

      <v-bottom-nav
        v-if="logged && user.isAdmin"
        :active.sync="bottomNav"
        :value="true"
        fixed
        app
      >
        <v-btn flat to="/customers">
          <span>Clientes</span>
          <v-badge right>
            <template v-slot:badge>
              <span>{{ statistics.customers }}</span>
            </template>
            <v-icon>mdi-account</v-icon>
          </v-badge>
        </v-btn>

        <v-btn flat to="/contracts">
          <span>Contratos</span>
          <v-badge right>
            <template v-slot:badge>
              <span>{{ statistics.contracts }}</span>
            </template>
            <v-icon>mdi-file-document-outline</v-icon>
          </v-badge>
        </v-btn>

        <v-btn flat to="/provisions/pending">
          <span>Pendente</span>
          <v-icon>mdi-progress-clock</v-icon>
        </v-btn>

        <v-btn flat to="/provisions/received">
          <span>Recebido</span>
          <v-icon>mdi-progress-check</v-icon>
        </v-btn>
      </v-bottom-nav>
    </v-app>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      bottomNav: "contracts",
    };
  },

  computed: {
    ...mapGetters("auth", ["user", "logged", "token"]),
    ...mapGetters("statistic", ["statistics"]),
  },

  methods: {
    ...mapActions("auth", ["getUser", "logout"]),
    ...mapActions("statistic", ["showStatistics"]),

    showToolbar() {
      const routes = ["Home"];
      return routes.includes(this.$route.name);
    },

    goStatistics() {
      if (!this.user.isAdmin) {
        return false;
      } else {
        this.$router.push({ name: "Statistic" });
      }
    },
    goHome() {
      if (!this.user.isAdmin) {
        return false;
      } else {
        this.$router.push({ name: "Home" });
      }
    },

    exit() {
      this.logout().then((res) => {
        this.$router.push({ name: "Login" });
      });
    },

    calendar(token) {
      if (!this.user.isAdmin) {
        return false;
      }
      return window.open(
        `webcal://cash4you.app/calendar?api_token=${token}`,
        "_top"
      );
    },
  },
  created() {
    if (this.logged) {
      this.getUser();
      this.showStatistics();
    }
  },
};
</script>
