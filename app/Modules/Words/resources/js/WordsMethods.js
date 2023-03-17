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
        saveWords({state, commit}, data){
            return axios.post('/saveWords', {
                in_english: data.in_english,
                in_russia: data.in_russia,
                transcription: data.transcription,
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
        saveOneWord({state, commit}, data){
            let result = false
            return axios.put('/saveOneWord', {
                word_id: data.word_id,
                in_english: data.in_english,
                in_russia: data.in_russia,
                transcription: data.transcription,
                word_type_id: data.word_type_id,
                is_show: data.is_show,
            }).then((response) => {
                result = 'true'
                return result
            }).catch((error) => {
                console.log(error)
                result = 'false'
                return result
            })
        }
    },
    mutations: {
        setWords(state, value){
            state.words = value
        },
        setTypes(state, value){
            state.types = value
        }
    },
    getters: {
        getWords(state){
            return state.words;
        },
        getTypes(state){
            return state.types;
        }
    }
})
