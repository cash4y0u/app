const state = {
    statistics: []
};
const actions = {
    showStatistics: context => {
        return new Promise((resolve, reject) => {
            axios
                .get(`/api/statistics`)
                .then(response => {
                    context.commit("SHOW_STATISTICS", response.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    }
};
const mutations = {
    SHOW_STATISTICS: (state, payload) => {
        state.statistics = payload;
    }
};
const getters = {
    statistics: state => {
        return state.statistics;
    }
};
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
};
