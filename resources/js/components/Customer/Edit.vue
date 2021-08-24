
<template>
  <div>
    <v-container>
      <v-card-title class="justify-center">
      <div class="title pl-1 pt-1">Editar Informações do Cliente</div>
      </v-card-title>

      <v-flex xs12>
        <v-text-field v-model="local.name" label="Nome"></v-text-field>
      </v-flex>
      <v-layout wrap>
        <v-flex xs5>
              <v-text-field v-validate="{required:true}"
                            data-vv-validate-on="blur"
                            data-vv-as="cpf"
                            data-vv-name="customer.document"
                            :error-messages="errors.first('customer.document')"
                            mask="###.###.###-##"
                            type="tel"
                            return-masked-value
                            v-model="local.document"
                            label="CPF"></v-text-field>
        </v-flex>
        <v-spacer></v-spacer>
        <v-flex xs6>
          <v-text-field v-model="local.email" label="E-mail"></v-text-field>
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
                            v-model="local.phone"
                            label="Telefone"></v-text-field>
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
                  <v-text-field :value="local.birth"
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
                               v-model="local.birth"
                               max="2004-01-01"
                               min="1950-01-01"
                               @change="$refs.menu.save(local.birth)"></v-date-picker>
              </v-menu>
            </v-flex>
      </v-layout>

      <div><v-divider></v-divider></div>
        
       <v-list-item v-for="(item, index) in local.adresses"
                         :key="index">

            <v-btn @click="removeAddress(item)"
              icon>
              <v-icon>mdi-delete</v-icon>
            </v-btn>
            <v-list-item-title>{{(item.type == 'Casa' ? 'Endereço Residencial':'Endereço Comercial')}}
            </v-list-item-title>
            <v-layout wrap>
            <v-flex xs5>

            <v-text-field v-model="item.street" label="Rua/Avenida"></v-text-field>
            </v-flex>
             <v-spacer></v-spacer>
            <v-flex xs5>
            <v-text-field v-model="item.number" label="Número"></v-text-field>
            </v-flex>
            <v-flex xs5>
            <v-text-field v-model="item.district" label="Bairro"></v-text-field>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs3>
            <v-spacer></v-spacer>
            <v-text-field v-model="item.city" label="Cidade"></v-text-field>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs2>
            <v-text-field v-model="item.state" label="Estado"></v-text-field>
            </v-flex>
             <v-spacer></v-spacer>
            <v-flex xs5>
            <v-text-field v-model="item.zipcode" label="CEP"></v-text-field>
            </v-flex>         
             </v-layout>
            <v-divider></v-divider>

            </v-list-item>

            <v-flex xs12>
              <v-btn block
                     flat
                     dark
                     @click="dialog = true">Adicionar endereço</v-btn>
            </v-flex>


      <v-btn v-if="this.local.adresses.length > 0 "
          @click="favorite()"
           class="primary"
           block
             depressed>
          <v-icon left>mdi-account-plus</v-icon>
          Atualizar
        </v-btn>
    </v-container>
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
                  <v-text-field v-model="adresses.number"
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
import moment from 'moment';

export default {
  data() {
    return {
      local: {},
      adresses: {},
      dialog: false,
    };
  },
  watch: {
    menu(val) {
      val && setTimeout(() => (this.$refs.picker.activePicker = "YEAR"));
    },
    "local.birth": {
      handler(value) {
        const birth = moment(value).format('YYYY-MM-DD');
      }
    }
  },


  computed: {
    ...mapGetters("customer", ["customer"]),
     ...mapGetters("upload", ["file"]),
          
    
  },

  methods: {
    
    ...mapActions("customer", ["showCustomer", "updateCustomer"]),
    favorite() {
        const id= this.local.id;
        this.updateCustomer(this.local).then(response => {
            this.$router.push({
              name: "CustomerShow",
              params: { id: id }
            });
          });
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
      this.local.adresses.splice(this.local.adresses.indexOf(item), 1);
    },
    addAddress(address) {
      this.$validator.validateAll("address").then(result => {
        if (result) {
          this.local.adresses.push(address);
          this.adresses = {};
          this.dialog = false;
        }
      });
    },
  },

  created() {
    this.showCustomer(this.$route.params.id).then((response) => {
      this.local = JSON.parse(JSON.stringify(this.customer));
    });
  },
};
</script>
