import {createApp} from 'vue';
import Vuex from 'vuex';
import {message} from "./MessageMethods"

const app = createApp({})

app.use(Vuex)


let dataSuccess = {
    title: 'Сохранено',
        text: 'Сохранение прошло успешно',
        type: 'success'
}
let dataError = {
    title: 'Ошибка',
        text: 'Ошибка при созранении',
        type: 'error'
}
let dataDelete = {
    title: 'Удалено',
        text: 'Удаление прошло успешно',
        type: 'success'
}

export const words = new Vuex.Store({
    state: {
        lists: [],
        state: {
            types: null,
            words: null,
            progress: null,
            info: null,
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
        fetchInfo({state, commit}){
            return axios.get('/getInfo')
                .then((response) => {
                    commit('setInfo',response.data);
                    return response.data
                })
        },
        saveWords({state, commit}, data){
            return axios.post('/saveWords', {
                in_english: data.in_english,
                in_russia: data.in_russia,
                transcription: data.transcription,
                word_type_id: data.word_type_id,
            }).then((response) => {
                message.dispatch('fetchMessage', 'dataSuccess')
            }).catch((error) => {
                message.dispatch('fetchMessage', 'dataError')
                console.log(error)
            })
        },
        deleteWord({state, commit}, deleteId){
            return axios.post('/deleteWord', {
                deleteId: deleteId,
            }).then((response) => {
                message.dispatch('fetchMessage', state.dataDelete)
            }).catch((error) => {
                message.dispatch('fetchMessage', state.dataError)
                console.log(error)
            })
        },
        clearProgress() {
            return axios.get('/clearProgress')
        },
        saveOneWord({state}, data){
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
                message.dispatch('fetchMessage', dataSuccess)
                result = 'true'
                return result
            }).catch((error) => {
                message.dispatch('fetchMessage', 'dataError')
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
        },
        setInfo(state, value){
            state.info = value
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
        },
        getInfo(state){
            return state.info;
        }
    }
})
