const state = {
    provision: {},
    provisions: [],
}
const actions = {
    showProvisions: (context) => {
        return new Promise((resolve, reject) => {

            axios.get(`/api/provisions`).then(response => {
                context.commit('SHOW_PROVISIONS', response.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    showProvisionsByContract: (context, id) => {
        return new Promise((resolve, reject) => {
            axios.get(`/api/contracts/${id}/provisions`).then(response => {
                context.commit('SHOW_PROVISIONS', response.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    showProvision: (context, id) => {
        return new Promise((resolve, reject) => {
            axios.get(`/api/provisions/${id}`).then(response => {
                context.commit('SHOW_PROVISION', response.data.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    
    storeProvision: (context, payload) => {
        return new Promise((resolve, reject) => {
            axios.post(`/api/provisions`, payload).then(response => {
                context.commit('STORE_PROVISION', response.data)
                context.dispatch('showProvisions')
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    updateProvision: (context, payload) => {
        return new Promise((resolve, reject) => {
            axios.put(`/api/provisions/${payload.id}`, payload).then(response => {
                context.dispatch('showProvisions')
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    destroyProvision: (context, id) => {
        return new Promise((resolve, reject) => {
            axios.delete(`/api/provisions/${id}`).then(response => {
                context.dispatch('showProvisions')
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    setProvision: (context, id) => {
        context.commit('SET_PROVISION', id)
    },
}
const mutations = {

    SHOW_PROVISION: (state, payload) => {
        state.provision = JSON.parse(JSON.stringify(payload))
    },
    SHOW_CURRENT_PROVISION: (state, payload) => {
        state.provision = JSON.parse(JSON.stringify(payload))
    },
    SHOW_PROVISIONS: (state, payload) => {
        state.provisions = payload
    },
    STORE_PROVISION: (state, payload) => {
        state.provision = payload
    }
}
const getters = {
    provision: state => {
        return state.provision
    },
    provisions: state => {
        return state.provisions
    }
}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}
