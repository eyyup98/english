import {createApp} from 'vue';
import Vuex from 'vuex';

const app = createApp({})

app.use(Vuex)

export const words = new Vuex.Store({
    state: {
        lists: []
    },
    actions: {
        fetchWords({state, commit}){
            return axios.get('/getWords', {
                params: {
                    choice: 'this.choiceBtn',
                    eventBtn: 'this.eventBtn'
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
        }
    },
    mutations: {
        setWords(state, value){
            state.words = value
        }
    },
    getters: {
        getWords(state){
            console.log('gett')
            return state.words;
        }
    }
})
