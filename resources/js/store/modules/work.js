const state = {
    work: {
        user:{}
    },
    works: {},
}
const actions = {
    showWorks: (context) => {
        return new Promise((resolve, reject) => {
            axios.get(`/api/works`).then(response => {
                context.commit('SHOW_WORKS', response.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    showWork: (context, id) => {
        return new Promise((resolve, reject) => {
            axios.get(`/api/works/${id}`).then(response => {
                context.commit('SHOW_WORK', response.data.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    showCurrentWork: (context) => {
        return new Promise((resolve, reject) => {
            axios.get(`/api/works/current`).then(response => {
                context.commit('SHOW_CURRENT_WORK', response.data.data)
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    storeWork: (context, payload) => {
        return new Promise((resolve, reject) => {
            axios.post(`/api/works`, payload).then(response => {
                context.commit('STORE_WORK', response.data)
                context.dispatch('showWorks')
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    updateWork: (context, payload) => {
        return new Promise((resolve, reject) => {
            axios.put(`/api/works/${payload.id}`, payload).then(response => {
                context.dispatch('showWorks')
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    destroyWork: (context, id) => {
        return new Promise((resolve, reject) => {
            axios.delete(`/api/works/${id}`).then(response => {
                context.dispatch('showWorks')
                resolve(response)
            }).catch(error => {
                reject(error)
            })
        })
    },
    setWork: (context, id) => {
        context.commit('SET_WORK', id)
    },
}
const mutations = {

    SHOW_WORK: (state, payload) => {
        state.work = JSON.parse(JSON.stringify(payload))
    },
    SHOW_CURRENT_WORK: (state, payload) => {
        state.work = JSON.parse(JSON.stringify(payload))
    },
    SHOW_WORKS: (state, payload) => {
        state.works = payload
    },
    STORE_WORK: (state, payload) => {
        state.work = payload
    }
}
const getters = {
    work: state => {
        return state.work
    },
    works: state => {
        return state.works
    }
}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}
