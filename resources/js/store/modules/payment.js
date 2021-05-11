const state = {
    payments: {
        data: [],
        meta: {}
    }

}
const actions = {
    showPayments: (context, query) => {
        return new Promise((resolve, reject) => {
            var request = {
                params: {
                  search: query
                }
              }
            axios.get(`/api/payments`, request).then(response => {
                context.commit('SHOW_PAYMENTS', response.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },

    storePayment: (context, payload) => {
        return new Promise((resolve, reject) => {
            axios.post(`/api/payments`, payload).then(response => {
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },


}
const mutations = {

    SHOW_PAYMENTS: (state, payload) => {
        state.payments = payload
    },

}
const getters = {
    payment: state => {
        return state.payment
    },
    payments: state => {
        return state.payments
    }
}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}
