const state = {
    receipt: {},
    receipts: []

}
const actions = {
    showReceipts: (context, query) => {
        return new Promise((resolve, reject) => {
            var request = {
                params: {
                  search: query
                }
              }
            axios.get(`/api/receipts`, request).then(response => {
                context.commit('SHOW_RECEIPTS', response.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    showReceipt: (context, id) => {
        return new Promise((resolve, reject) => {
            axios.get(`/api/receipts/${id}`).then(response => {
                context.commit('SHOW_RECEIPT', response.data.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },

    storeReceipt: (context, payload) => {
        return new Promise((resolve, reject) => {
            axios.post(`/api/receipts`, payload).then(response => {
                context.commit('STORE_RECEIPT', response.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    updateReceipt: (context, payload) => {
        return new Promise((resolve, reject) => {
            axios.put(`/api/receipts/${payload.id}`, payload).then(response => {
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    destroyReceipt: (context, id) => {
        return new Promise((resolve, reject) => {
            axios.delete(`/api/receipts/${id}`).then(response => {
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },

}
const mutations = {

    SHOW_RECEIPTS: (state, payload) => {
        state.receipts = payload
    },

    SHOW_RECEIPT: (state, payload) => {
        state.receipt = JSON.parse(JSON.stringify(payload))
    },

    STORE_RECEIPT: (state, payload) => {
        state.receipt = payload
    }
}
const getters = {
    receipt: state => {
        return state.receipt
    },
    receipts: state => {
        return state.receipts
    }
}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}
