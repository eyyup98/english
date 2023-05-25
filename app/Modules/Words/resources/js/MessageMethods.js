import {createApp} from 'vue';
import Vuex from 'vuex';

const app = createApp({})

app.use(Vuex)

export const message = new Vuex.Store({
    actions: {
        fetchMessage({dataSuccess}, data){
            let message
            let messageTitle
            let messageText
            message = document.getElementById('message')
            messageTitle = document.getElementById('message-title')
            messageText = document.getElementById('message-text')
            message.style.display = 'block'
            setTimeout(function() {
                message.style.display = 'none'
            }, (3000));
            messageTitle.content = data.title
            messageText.content = data.text
            if (data.type === 'success') {
                message.style.backgroundColor = 'rgba(61,121,52,0.98)'
            }
            if (data.type === 'error') {
                message.style.backgroundColor = 'rgba(222, 147, 147, 0.98)'
            }
        },
    },
})
