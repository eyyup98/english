import {createApp} from 'vue';
import Vuex from 'vuex';

const app = createApp({})

app.use(Vuex)

export const words = new Vuex.Store({
    state: {
        lists: []
    },
    actions: {
        fetchWords({state, commit}, search = null){
            return axios.get('/getWords', {
                params: {
                    search: search
                }
            }).then((response) => {
                commit('setWords',response.data);
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
        }
    },
    getters: {
        getWords(state){
            return state.words;
        }
    }
})
