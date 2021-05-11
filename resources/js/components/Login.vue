<template>
  <v-container fill-height>
    <v-layout justify-center align-center>
      <v-flex shrink xs12 sm8 md5>
        <v-card flat class="transparent">
          <v-form>
            <v-text-field
              box
              v-validate="{required: true, email: true}"
              data-vv-validate-on="blur"
              data-vv-as="e-mail"
              data-vv-name="email"
              :error-messages="errors.first('email')"
              append-icon="mdi-account-tie"
              v-model="auth.email"
              label="E-mail"
              type="text"
            ></v-text-field>
            <v-text-field
              box
              v-validate="{required: true}"
              data-vv-validate-on="blur"
              data-vv-as="senha"
              data-vv-name="password"
              :error-messages="errors.first('password')"
              append-icon="mdi-lock"
              v-model="auth.password"
              label="Senha"
              type="password"
            ></v-text-field>

            <v-btn @click="authenticate()" block color="primary">Login</v-btn>
          </v-form>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      auth: {
        email: "",
        password: ""
      }
    };
  },

  computed: {
    ...mapState("auth", ["logged", "user"])
  },

  methods: {
    ...mapActions("auth", ["login"]),

    authenticate() {
      this.$validator.validate().then(result => {
        if (result) {
          this.login(this.auth).then(res => {
            if (!this.user.isAdmin) {
               this.$router.push(`/receipts`);
               return false;
            }

            if (this.$route.query.redirect) {
              this.$router.push(this.$route.query.redirect);
            } else {
              this.$router.push(`/`);
            }
          });
        }
      });
    }
  }
};
</script>
