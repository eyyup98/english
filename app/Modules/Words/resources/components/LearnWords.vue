<template>
    <div class="container">
        <div class="choiceType">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary active" @click="choiceBtn = 'random'">
                    <input type="radio" name="options" id="random" autocomplete="off"> Рандом
                </label>
                <label class="btn btn-secondary" @click="choiceBtn = 'english'">
                    <input type="radio" name="options" id="english" autocomplete="off"> Английский
                </label>
                <label class="btn btn-secondary" @click="choiceBtn = 'russia'">
                    <input type="radio" name="options" id="russia" autocomplete="off"> Русский
                </label>
            </div>
        </div>
        <div class="choiceType">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary" @click="checkedEvent" v-on:click="eventBtn = 'begin'">
                    <input type="radio" name="event" id="begin" autocomplete="off"> Начать
                </label>
                <label class="btn btn-secondary" v-on:click="eventBtn = 'end'">
                    <input type="radio" name="event" id="end" autocomplete="off"> Закончить
                </label>
            </div>
        </div>

        <div id="get-word" class="get-word successful" v-if="eventBtn === 'begin'">
            <button @click="changeLanguage">
                <div class="input-word h-text">
                    <span class="h-text" v-if="language === 'english'">{{word.in_english}}</span>
                    <span class="h-text transcription" v-if="language === 'english'">{{ word.transcription }}</span>
                    <span class="h-text" v-if="language === 'russia'">{{word.in_russia}}</span>
                </div>
            </button>
        </div>

        <div class="next-btn" v-if="eventBtn === 'begin'">
            <button type="button" class="btn btn-dark" @click="nextWord">Следующее слово</button>
        </div>
    </div>
</template>

<script>
import {words} from "../js/WordsMethods"

export default {
    name: "LearnWords",
    data(){
        return {
            choiceBtn: 'random',
            eventBtn: null,
            lists: [],
            rand: null,
            word: null,
            language: null
        }
    },
    methods:{
        changeLanguage(){
            if (this.language === 'russia') {
                this.language = 'english'
            } else {
                this.language = 'russia'
            }
        },
        checkedEvent(){
            this.lists = words.getters.getWords
            this.printWord()
        },
        nextWord(){
            this.lists.splice(this.rand, 1)
            this.printWord()
        },
        printWord(){
            if (this.choiceBtn === 'random') {
                let rand =  Math.floor(Math.random() * 2);
                if (rand === 1) {
                    this.language = 'english'
                } else {
                    this.language = 'russia'
                }
            } else if (this.choiceBtn === 'english') {
                this.language = 'english'
            } else this.language = 'russia'


            this.rand = Math.floor(Math.random() * this.lists.length);
            this.word = this.lists[this.rand]
        }
    },
    mounted() {
        words.dispatch('fetchWords')
    }
}
</script>

<style scoped>
.choiceType{
    margin: 30px auto 0;
    width: 100%;
    display: flex;
    justify-content: center;
}
.get-word {
    margin: 50px auto;
    display: flex;
    justify-content: center;
    align-items:center;
}
.input-word{
    display: flex;
    justify-content: center;
    align-items:center;
    min-width: 300px;
    min-height: 200px;
    background-color: #bef2f0;
    border-radius: 10px;
    font-size: 48px;
    padding: 20px;
    flex-direction:column;
    box-shadow: 0 0 10px 10px #93eae7;
}
.input-word:hover+ .input-word{
     opacity:0;
     transition: 5s;
 }
.transcription {
    color: #2d3748;
    font-size: 24px;
}
</style>
