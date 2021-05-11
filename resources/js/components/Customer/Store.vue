<template>
  <div>
    <v-card class="transparent"
            flat>

      <v-img height="200"
             :src="(customer.photo == '' ? '': customer.photo)">
        <v-layout wrap>
          <v-flex xs12>
            <v-progress-linear :active="isUploading"
                               class="ma-0"
                               color="primary"
                               height="4"
                               indeterminate></v-progress-linear>
          </v-flex>
          <v-flex text-xs-right
                  xs12>
            <v-btn @click="deletePhoto"
                   icon>
              <v-icon v-if="!customer.photo == ''">mdi-delete</v-icon>
            </v-btn>
          </v-flex>
          <v-layout align-center
                    column
                    justify-end
                    pa-3>
            <v-btn v-if="customer.photo == ''"
                   @click="$refs.fileUpload.click()"
                   large
                   :disabled="isUploading"
                   fab
                   color="primary">
              <v-icon>mdi-camera</v-icon>
            </v-btn>

          </v-layout>
        </v-layout>
      </v-img>
      <v-form data-vv-scope="person">
        <v-container>
          <v-layout wrap>
            <v-flex xs12>
              <v-text-field v-validate="{required:true}"
                            data-vv-validate-on="blur"
                            data-vv-as="nome"
                            data-vv-name="customer.name"
                            :error-messages="errors.first('customer.name')"
                            v-model="customer.name"
                            label="Nome completo"></v-text-field>
            </v-flex>
            <v-flex xs6>
              <v-text-field v-validate="{required:true}"
                            data-vv-validate-on="blur"
                            data-vv-as="cpf"
                            data-vv-name="customer.document"
                            :error-messages="errors.first('customer.document')"
                            mask="###.###.###-##"
                            type="tel"
                            return-masked-value
                            v-model="customer.document"
                            label="CPF"></v-text-field>
            </v-flex>
            <v-flex xs6>
              <v-menu ref="menu"
                      v-model="menu"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px">
                <template v-slot:activator="{ on }">
                  <v-text-field :value="birth"
                                v-validate="{required:true}"
                                data-vv-validate-on="change"
                                data-vv-as="data"
                                data-vv-name="customer.birth"
                                :error-messages="errors.first('customer.birth')"
                                label="Data de nascimento"
                                readonly
                                v-on="on"></v-text-field>
                </template>
                <v-date-picker locale="pt-br"
                               ref="picker"
                               v-model="customer.birth"
                               max="2004-01-01"
                               min="1950-01-01"
                               @change="$refs.menu.save(customer.birth)"></v-date-picker>
              </v-menu>
            </v-flex>
            <v-flex xs6>
              <v-text-field v-validate="{required:true,email:true}"
                            data-vv-validate-on="blur"
                            data-vv-as="e-mail"
                            data-vv-name="customer.email"
                            :error-messages="errors.first('customer.email')"
                            v-model="customer.email"
                            label="E-mail"></v-text-field>
            </v-flex>
            <v-flex xs6>
              <v-text-field v-validate="{required:true}"
                            data-vv-validate-on="blur"
                            data-vv-as="telefone"
                            data-vv-name="customer.phone"
                            :error-messages="errors.first('customer.phone')"
                            return-masked-value
                            type="tel"
                            mask="## #####-####"
                            v-model="customer.phone"
                            label="Telefone"></v-text-field>
            </v-flex>

            <v-flex>
              <v-chip close
                      v-for="(item, index) in customer.adresses"
                      :key="index"
                      @input="removeAddress(item)">
                <v-avatar>
                  <v-icon>{{(item.type == 'Casa' ? 'mdi-home':'mdi-domain')}}</v-icon>
                </v-avatar>
                {{item.street}}, {{item.number}}
              </v-chip>
            </v-flex>

            <v-flex xs12>
              <v-btn block
                     flat
                     dark
                     @click="dialog = true">Adicionar endereço</v-btn>
            </v-flex>

          </v-layout>
        </v-container>

      </v-form>

      <v-card-actions>

        <v-btn v-if="customer.adresses.length > 0"
               @click="register()"
               class="primary"
               block
               depressed>
          <v-icon left>mdi-account-plus</v-icon>
          Cadastrar
        </v-btn>
      </v-card-actions>
      <input v-show="false"
             ref="fileUpload"
             type="file"
             accept="image/*"
             @change="upload">
    </v-card>

    <v-dialog v-model="dialog"
              persistent
              fullscreen="">

      <v-card>
        <v-form data-vv-scope="address">
          <v-card-title>
            <v-spacer></v-spacer>
            <v-btn icon
                   @click="dialog = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs6>
                  <v-autocomplete v-validate="{required:true}"
                                  data-vv-validate-on="blur"
                                  data-vv-as="tipo"
                                  data-vv-name="adresses.type"
                                  :error-messages="errors.first('adresses.type')"
                                  v-model="adresses.type"
                                  :items="['Casa','Empresa']"
                                  label="Tipo de endereço">

                  </v-autocomplete>
                </v-flex>
                <v-flex xs6>
                  <v-text-field v-validate="{required:true}"
                                data-vv-validate-on="blur"
                                data-vv-as="cep"
                                data-vv-name="adresses.zipcode"
                                :error-messages="errors.first('adresses.zipcode')"
                                v-on:keypress="isLookingZipcode = true"
                                v-on:change="cep"
                                :loading="isLookingZipcode"
                                mask="#####-###"
                                return-masked-value
                                type="tel"
                                v-model="adresses.zipcode"
                                label="CEP"></v-text-field>
                </v-flex>

                <v-flex xs12>
                  <v-text-field v-validate="{required:true}"
                                data-vv-validate-on="blur"
                                data-vv-as="endereço"
                                data-vv-name="adresses.street"
                                :error-messages="errors.first('adresses.street')"
                                v-model="adresses.street"
                                label="Endereço"></v-text-field>
                </v-flex>
                <v-flex xs3>
                  <v-text-field v-validate="{min:1, required:true, numeric:true}"
                                data-vv-validate-on="blur"
                                data-vv-as="número"
                                data-vv-name="adresses.number"
                                :error-messages="errors.first('adresses.number')"
                                v-model="adresses.number"
                                type="tel"
                                label="Número"></v-text-field>
                </v-flex>
                <v-flex xs4>
                  <v-text-field v-model="adresses.complement"
                                label="Complemento"></v-text-field>
                </v-flex>
                <v-flex xs5>
                  <v-text-field v-validate="{required:true}"
                                data-vv-validate-on="blur"
                                data-vv-as="bairro"
                                data-vv-name="adresses.district"
                                :error-messages="errors.first('adresses.district')"
                                v-model="adresses.district"
                                label="Bairro"></v-text-field>
                </v-flex>
                <v-flex xs8>
                  <v-text-field v-validate="{required:true}"
                                data-vv-validate-on="blur"
                                data-vv-as="cidade"
                                data-vv-name="adresses.city"
                                :error-messages="errors.first('adresses.city')"
                                v-model="adresses.city"
                                label="Cidade"></v-text-field>
                </v-flex>
                <v-flex xs4>
                  <v-text-field v-validate="{required:true}"
                                data-vv-validate-on="blur"
                                data-vv-as="estado"
                                data-vv-name="adresses.state"
                                :error-messages="errors.first('adresses.state')"
                                v-model="adresses.state"
                                label="Estado"></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>

          </v-card-text>
          <v-card-actions>
            <v-btn block
                   @click="addAddress(adresses)">Salvar</v-btn>
          </v-card-actions>
        </v-form>
      </v-card>

    </v-dialog>

  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      dialog: false,
      isUploading: false,
      isLookingZipcode: false,
      adresses: {},
      customer: {
        photo: "",
        adresses: []
      },
      date: null,
      birth: null,
      menu: false
    };
  },

  watch: {
    menu(val) {
      val && setTimeout(() => (this.$refs.picker.activePicker = "YEAR"));
    },
    "customer.birth": {
      handler(value) {
        const birth = Date.parse(`${value}T10:30:00-03:00`);
        this.birth = new Date(birth).toLocaleDateString();
      }
    }
  },

  computed: {
    ...mapGetters("customer", ["customers"]),
    ...mapGetters("upload", ["file"])
  },

  methods: {
    ...mapActions("customer", ["showCustomers", "storeCustomer"]),
    ...mapActions("upload", ["uploadFile", "deleteFile"]),
    upload(ev) {
      this.isUploading = true;
      this.uploadFile(ev).then(response => {
        this.customer.photo = this.file;
        this.isUploading = false;
      });
    },
    deletePhoto() {
      this.customer.photo = "";
    },
    cep() {
      this.isLookingZipcode = true;
      axios.get(`/api/cep/${this.adresses.zipcode}`).then(response => {
        const data = response.data;
        this.adresses.favorite = false;
        this.adresses.street = data.logradouro;
        this.adresses.district = data.bairro;
        this.adresses.city = data.localidade;
        this.adresses.state = data.uf;
        this.isLookingZipcode = false;
      });
    },
    removeAddress(item) {
      this.customer.adresses.splice(this.customer.adresses.indexOf(item), 1);
    },
    addAddress(address) {
      this.$validator.validateAll("address").then(result => {
        if (result) {
          this.customer.adresses.push(address);
          this.adresses = {};
          this.dialog = false;
        }
      });
    },
    register() {
      this.$validator.validateAll("person").then(result => {
        if (result) {
          this.storeCustomer(this.customer).then(response => {
            this.$router.push({
              name: "CustomerShow",
              params: { id: response.data.id }
            });
          });
        }
      });
    }
  }
};
</script>
