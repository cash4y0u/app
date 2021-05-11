import Vue from "vue";
import Vuex from "vuex";

import auth from "./modules/auth";
import statistic from "./modules/statistic";
import customer from "./modules/customer";
import contract from "./modules/contract";
import provision from "./modules/provision";
import upload from "./modules/upload";
import receipt from "./modules/receipt";
import payment from "./modules/payment";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        auth,
        customer,
        provision,
        contract,
        upload,
        statistic,
        receipt,
        payment
    },
    strict: true
});
