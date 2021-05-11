const state = {
    file: null,
}
const actions = {

    uploadFile: (context, ev) => {

        return new Promise((resolve, reject) => {

            const file = ev.target.files[0];
            const reader = new FileReader();
            reader.onload = e => {
                
                const data = new FormData();
                data.append("file", file);
                const header = {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                }
                
                axios.post(`/api/customers/upload`, data, header).then(response => {
                   
                    const file = response.data
                    context.commit('UPLOAD', file)
                    resolve(response)
                }).catch(error => {
                    reject(error)
                });
            };
            reader.readAsText(file);
        })
    },

    
}
const mutations = {

    UPLOAD: (state, file) => {
        state.file = file
    },

}
const getters = {
    file: state => {
        return state.file
    },

}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}