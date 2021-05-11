const state = {
    customer: {},
    customers: {
        data: [],
        meta:{}
    },
}
const actions = {
    showCustomers: (context) => {
        return new Promise((resolve, reject) => {
            axios.get(`/api/customers`).then(response => {
                context.commit('SHOW_CUSTOMERS', response.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    showCustomer: (context, id) => {
        return new Promise((resolve, reject) => {
            axios.get(`/api/customers/${id}`).then(response => {
                context.commit('SHOW_CUSTOMER', response.data.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    storeCustomer: (context, payload) => {
        return new Promise((resolve, reject) => {
            axios.post(`/api/customers`, payload).then(response => {
                context.commit('STORE_CUSTOMER', response.data)
                context.dispatch('showCustomers')
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    updateCustomer: (context, payload) => {
        return new Promise((resolve, reject) => {
            axios.put(`/api/customers/${payload.id}`, payload).then(response => {
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    destroyCustomer: (context, id) => {
        return new Promise((resolve, reject) => {
            axios.delete(`/api/customers/${id}`).then(response => {
                context.dispatch('showCustomers')
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    }
}
const mutations = {

    SHOW_CUSTOMER: (state, payload) => {
        state.customer = payload
    },
    SHOW_CUSTOMERS: (state, payload) => {
        state.customers = payload
    },
    STORE_CUSTOMER: (state, payload) => {
        state.customer = payload
    }
}
const getters = {
    customer: state => {
        return state.customer
    },
    customers: state => {
        return state.customers
    }
}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}
