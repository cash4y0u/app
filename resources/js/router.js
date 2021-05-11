import Vue from "vue";
import Router from "vue-router";

import Login from "./components/Login.vue";

import Home from "./components/Home.vue";
import Statistic from "./components/Statistic.vue";

import Customer from "./components/Customer/Index.vue";
import CustomerShow from "./components/Customer/Show.vue";
import CustomerStore from "./components/Customer/Store.vue";

import Contract from "./components/Contract/Index.vue";
import ContractShow from "./components/Contract/Show.vue";
import ContractStore from "./components/Contract/Store.vue";
import ContractProvision from "./components/Contract/Provision.vue";

import ProvisionPending from "./components/Provision/Pending.vue";
import ProvisionReceived from "./components/Provision/Received.vue";
import ProvisionShow from "./components/Provision/Show.vue";


import Receipt from "./components/Receipt/Index.vue";
import ReceiptShow from "./components/Receipt/Show.vue";

import Payment from "./components/Payment/Index.vue";


Vue.use(Router);

export default new Router({
    mode: "history",
    base: "/",
    routes: [{
            path: "/login",
            name: "Login",
            component: Login
        },

        {
            path: "/payments",
            name: "Payment",
            component: Payment,
            meta: {
                auth: true
            }

        },

        {
            path: "/receipts",
            name: "Receipt",
            component: Receipt,
            meta: {
                auth: true
            }

        },
        {
            path: "/receipts/:id",
            name: "ReceiptShow",
            component: ReceiptShow,
            meta: {
                auth: true
            }
        },
        
        {
            path: "/",
            name: "Home",
            component: Home,
            meta: {
                auth: true
            }
        },
        {
            path: "/statistic",
            name: "Statistic",
            component: Statistic,
            meta: {
                auth: true
            }
        },
        {
            path: "/customers",
            name: "Customer",
            component: Customer,
            meta: {
                auth: true
            }
        },
        {
            path: "/customers/add",
            name: "CustomerStore",
            component: CustomerStore,
            meta: {
                auth: true
            }
        },
        {
            path: "/customers/:id",
            name: "CustomerShow",
            component: CustomerShow,
            meta: {
                auth: true
            }
        },

        {
            path: "/contracts",
            name: "Contract",
            component: Contract,
            meta: {
                auth: true
            }
        },
        {
            path: "/customers/:id/contracts/add",
            name: "ContractStore",
            component: ContractStore,
            meta: {
                auth: true
            }
        },
        {
            path: "/contracts/:id",
            name: "ContractShow",
            component: ContractShow,
            meta: {
                auth: true
            }
        },
        {
            path: "/contracts/:id/provisions",
            name: "ContractProvision",
            component: ContractProvision,
            meta: {
                auth: true
            }
        },

        {
            path: "/provisions/pending",
            name: "ProvisionPending",
            component: ProvisionPending,
            meta: {
                auth: true
            }
        },
        {
            path: "/provisions/received",
            name: "ProvisionReceived",
            component: ProvisionReceived,
            meta: {
                auth: true
            }
        },
        {
            path: "/provisions/:id",
            name: "ProvisionShow",
            component: ProvisionShow,
            meta: {
                auth: true
            }
        },
        {
            path: "*",
            redirect: "/"
        }
    ],
    scrollBehavior() {
        return {
            x: 0,
            y: 0
        };
    }
});
