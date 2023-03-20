import {createApp} from 'vue';
import Vuex from 'vuex';

const app = createApp({})

app.use(Vuex)

export const words = new Vuex.Store({
    state: {
        lists: [],
        state: {
            types: null,
            words: null,
            progress: null,
        }
    },
    actions: {
        fetchWords({state, commit}, data = null){
            return axios.get('/getWords', {
                params: {
                    data: data
                }
            }).then((response) => {
                commit('setWords',response.data);
                return response.data
            })
        },
        fetchTypes({state, commit}, search = null){
            return axios.get('/getTypes')
                .then((response) => {
                    commit('setTypes',response.data);
                    return response.data
                })
        },
        fetchProgress({state, commit}){
            return axios.get('/getProgress')
                .then((response) => {
                    commit('setProgress',response.data);
                    return response.data
                })
        },
        saveWords({state, commit}, data){
            return axios.post('/saveWords', {
                in_english: data.in_english,
                in_russia: data.in_russia,
                transcription: data.transcription,
                word_type_id: data.word_type_id,
            }).catch((error) => {
                console.log(error)
            })
        },
        deleteWord({state, commit}, deleteId){
            return axios.post('/deleteWord', {
                deleteId: deleteId,
            }).catch((error) => {
                console.log(error)
            })
        },
        clearProgress() {
            return axios.get('/clearProgress')
        },
        saveOneWord({state, commit}, data){
            let result = false
            return axios.put('/saveOneWord', {
                word_id: data.word_id,
                in_english: data.in_english,
                in_russia: data.in_russia,
                transcription: data.transcription,
                word_type_id: data.word_type_id,
                is_show: data.is_show,
                is_frequency: data.is_frequency,
            }).then((response) => {
                result = 'true'
                return result
            }).catch((error) => {
                console.log(error)
                result = 'false'
                return result
            })
        },
        saveProgress({state, commit}, list){
            return axios.post('/saveProgress', {
                data: list
            })
        },
    },
    mutations: {
        setWords(state, value){
            state.words = value
        },
        setTypes(state, value){
            state.types = value
        },
        setProgress(state, value){
            state.progress = value
        }
    },
    getters: {
        getWords(state){
            return state.words;
        },
        getTypes(state){
            return state.types;
        },
        getProgress(state){
            return state.progress;
        }
    }
})
