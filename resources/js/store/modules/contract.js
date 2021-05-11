const state = {
    contract: {
        customer: {},
        provisions: {}
    },
    contracts: []
}
const actions = {
    showContracts: (context) => {
        return new Promise((resolve, reject) => {
            axios.get(`/api/contracts`).then(response => {
                context.commit('SHOW_CONTRACTS', response.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    showContract: (context, id) => {
        return new Promise((resolve, reject) => {
            axios.get(`/api/contracts/${id}`).then(response => {
                context.commit('SHOW_CONTRACT', response.data.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    storeContract: (context, payload) => {
        return new Promise((resolve, reject) => {
            axios.post(`/api/contracts`, payload).then(response => {
                context.commit('STORE_CONTRACT', response.data)
                context.dispatch('showContracts')
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    updateContract: (context, payload) => {
        return new Promise((resolve, reject) => {
            axios.put(`/api/contracts/${payload.id}`, payload).then(response => {
                context.dispatch('showContracts')
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    destroyContract: (context, id) => {
        return new Promise((resolve, reject) => {
            axios.delete(`/api/contracts/${id}`).then(response => {
                context.dispatch('showContracts')
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    }
}
const mutations = {

    SHOW_CONTRACT: (state, payload) => {
        state.contract = JSON.parse(JSON.stringify(payload))
    },
    SHOW_CONTRACTS: (state, payload) => {
        state.contracts = payload
    },
    STORE_CONTRACT: (state, payload) => {
        state.contract = payload
    }
}
const getters = {
    contract: state => {
        return state.contract
    },
    contracts: state => {
        return state.contracts
    }
}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}
