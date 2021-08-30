<template>
  <v-layout row>
    <v-flex>
      <v-card>

        <v-img :src="local.photo"
               gradient="to top, rgba(0,0,0,.44), rgba(0,0,0,.44)"
               height="290px">
          <v-layout column
                    fill-height>
            <v-card-title>

              <v-btn @click="callPhone(local.phone)"
                     dark
                     icon
                     class="mr-3">
                <v-icon>mdi-phone</v-icon>
              </v-btn>
              <v-btn @click="sendText(local.phone)"
                     dark
                     icon
                     class="mr-3">
                <v-icon>mdi-whatsapp</v-icon>
              </v-btn>

        
              <v-btn @click="sendMail(local.email)"
                     dark
                     icon
                     class="mr-3">
                <v-icon>mdi-email</v-icon>
              </v-btn>
              <v-btn @click="$router.push({name: 'CustomerEdit', params:{id: local.id}})"
                     dark
                     icon
                     class="mr-3">
                <v-icon>mdi-account-edit</v-icon>
              </v-btn>
               <v-btn @click="sendTextContract(local)"
                     dark
                     icon
                     class="mr-3">
                <v-icon>mdi-message-bulleted</v-icon>
              </v-btn>

              <v-spacer></v-spacer>

              <v-btn dark
                     icon
                     class="mr-3"
                     @click="$router.push({name: 'ContractStore', params:{id: local.id}})">
                <v-icon>fa-hand-holding-usd</v-icon>

              </v-btn>

            </v-card-title>

            <v-spacer></v-spacer>

            <v-card-title class="white--text pl-5 pt-5">
              <div class="title pl-5 pt-5">{{local.name}}</div>
            </v-card-title>
          </v-layout>
        </v-img>

        <v-list dense>
          <v-list-tile>
            <v-list-tile-action>
              <v-icon>mdi-account-card-details</v-icon>
            </v-list-tile-action>

            <v-list-tile-content>
              <v-list-tile-title>{{local.document}}</v-list-tile-title>

            </v-list-tile-content>

          </v-list-tile>

          <v-list-tile>
            <v-list-tile-action>
              <v-icon>mdi-calendar</v-icon>
            </v-list-tile-action>

            <v-list-tile-content>
              <v-list-tile-title>{{formatar(local.birth)}}</v-list-tile-title>

            </v-list-tile-content>
            <v-list-tile-action>
              <v-chip small
                      outline>{{local.age}}</v-chip>
            </v-list-tile-action>
          </v-list-tile>

          <v-list-group prepend-icon="mdi-map-marker-radius">
            <template v-slot:activator>
              <v-list-tile>
                <v-list-tile-content>
                  <v-list-tile-title>Endereços</v-list-tile-title>
                </v-list-tile-content>
              </v-list-tile>
            </template>

            <v-list-tile v-for="(item, index) in local.adresses"

                         :key="index">
              <v-list-tile-action >
                <v-icon>{{(item.type == 'Casa' ? 'mdi-home':'mdi-domain')}}</v-icon>
              </v-list-tile-action>

              <v-list-tile-content @click="openWaze(item)">
                <v-list-tile-title>{{item.street}}, {{item.number}} - {{item.district}}</v-list-tile-title>
                <v-list-tile-sub-title>{{item.city}} - {{item.state}}, {{item.zipcode}}</v-list-tile-sub-title>
              </v-list-tile-content>

              <v-list-tile-action  @click="favorite(index)">
                <v-icon :color="(item.favorite ? 'yellow accent-3' : 'grey darken-1')">mdi-star</v-icon>
              </v-list-tile-action>

            </v-list-tile>

          </v-list-group>

          <v-list-group v-if="local.contracts.length > 0">

            <template v-slot:activator>
              <v-list-tile>
                <v-list-tile-action>
                  <v-icon>mdi-file-document-outline</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                  <v-list-tile-title>Contratos</v-list-tile-title>
                </v-list-tile-content>
              </v-list-tile>
            </template>

            <v-list-tile v-for="(item, index) in local.contracts"
                         @click="$router.push({name: 'ContractShow', params:{id: item.id}})"
                         :key="index">

              <v-list-tile-content>
                <v-list-tile-title>{{item.amount}} - {{item.split}} x {{item.amount_provision}}</v-list-tile-title>

              </v-list-tile-content>

              <v-list-tile-action>
                <v-chip small>{{item.created_at}}</v-chip>
              </v-list-tile-action>

            </v-list-tile>

          </v-list-group>

        </v-list>

      </v-card>

    </v-flex>
  </v-layout>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import moment from 'moment';
export default {
  data() {
    return {
      local: {},

    };
  },

  computed: {
    ...mapGetters("customer", ["customer"]),

      },

  methods: {
    ...mapActions("customer", ["showCustomer", "updateCustomer"]),
      ...mapActions("upload", ["uploadFile", "deleteFile"]),


    favorite(index){

          this.local.adresses[index].favorite = true
       for (let i = 0; i < this.local.adresses.length; i++) {

           if(i !== index){
            this.local.adresses[i].favorite = false
           }

           this.updateCustomer(this.local);


}


    },

formatar(value){
        return moment(value).format('DD/MM/YYYY');
      }, 


    callPhone(number) {
      window.open(`tel:${number}`, "_self");
    },
    sendMail(email) {
      window.open(`mailto:${email}`, "_self");
    },
    sendText(number) {
      window.open(`whatsapp://send?phone=55${number}`, "_self");
    },
    openWaze(item) {
      window.open(
        `waze://?q=${item.number} ${item.street}, ${item.city}&navigate=yes`,
        "_self"
      );
    },
      sendTextContract(local) {
        let $endereco;
        let $tipoResidencia;
          for(let i = 0; i < local.adresses.length; i++) {
            if(local.adresses[i].type=='Casa'){
              $tipoResidencia='Casa';
            }else{
              $tipoResidencia='Residencial';
            }
              if(i!=0){
                $endereco=`${$endereco}\n *Endereco ${$tipoResidencia}:* \n- ${local.adresses[i].street},${local.adresses[i].number} - ${local.adresses[i].district}
                \n${local.adresses[i].city} - ${local.adresses[i].state} - ${local.adresses[i].zipcode} `;
              }else{
                 $endereco=`*Endereco ${$tipoResidencia}:* \n- ${local.adresses[i].street},${local.adresses[i].number} - ${local.adresses[i].district}
                \n${local.adresses[i].city} - ${local.adresses[i].state} - ${local.adresses[i].zipcode}`;
           }
          }
          let $mensagem =  `Olá,\nEstou passando para lembrar que sua parcela vence *amanhã*.\nQual seria o endereço de coleta?\n ${$endereco}\nPor favor,responda assim que puder.\n*Cash4You*`;
          let $mensagemencode= encodeURI($mensagem);
      window.open(`whatsapp://send?phone=55${local.phone}&text=${$mensagemencode}`, "_self");
    }
  },
  created() {
    this.showCustomer(this.$route.params.id).then(response => {
        this.local = JSON.parse(JSON.stringify(this.customer));
      });
  },


};
</script>
