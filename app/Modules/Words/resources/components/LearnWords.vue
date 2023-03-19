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
                <label class="btn btn-secondary" v-on:click="eventBtn = 'begin'" @click="checkedEvent">
                    <input type="radio" name="event" id="begin" autocomplete="off"> Начать
                </label>
                <label class="btn btn-secondary" v-on:click="eventBtn = 'end'" @click="checkedEvent">
                    <input type="radio" name="event" id="end" autocomplete="off"> Закончить
                </label>
            </div>

            <div class="btn-group btn-group-toggle btn-m" data-toggle="buttons">
                <label class="btn btn-secondary active" @click="typeWords = 1">
                    <input type="radio" name="options" id="words" autocomplete="off" :disabled="eventBtn === 'begin'"> Слова
                </label>
                <label class="btn btn-secondary" @click="typeWords = 2">
                    <input type="radio" name="options" id="expressions" autocomplete="off" :disabled="eventBtn === 'begin'"> Выражения
                </label>
                <label class="btn btn-secondary" @click="typeWords = 3">
                    <input type="radio" name="options" id="rules" autocomplete="off" :disabled="eventBtn === 'begin'"> Правила
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

        <div id="end-learn" class="get-word successful">
            <span class="end-text h-text">Поздравляю вы закончили!</span>
        </div>

        <div class="next-btn" v-if="eventBtn === 'begin'">
            <button type="button" class="btn btn-dark" @click="printFrequency(0)" v-if="word.is_frequency === 1">
                Выводить реже
            </button>
            <button type="button" class="btn btn-dark" @click="printFrequency(1)" v-if="word.is_frequency === 0">
                Выводить чаще
            </button>
            <button type="button" class="btn btn-dark btn-m" @click="nextWord">Следующее слово</button>
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
            language: null,
            typeWords: 1,
        }
    },
    methods:{
        printFrequency(value){
            this.word.is_frequency = value
            words.dispatch('saveOneWord', this.word)
        },
        changeLanguage(){
            if (this.language === 'russia') {
                this.language = 'english'
            } else {
                this.language = 'russia'
            }
        },
        nullWord(){
            this.word = {
                is_frequency: null,
                in_english: '',
                transcription: '',
                in_russia: '',
            }
        },
        async checkedEvent(){
            document.getElementById('end-learn').style.display = 'none'
            this.nullWord()
            if (this.eventBtn === 'end') {
                this.lists = []
                return
            }

            let data = {
                uri: 'learnWords',
                typeWords: this.typeWords
            }
            await words.dispatch('fetchWords', data)
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

            this.nullWord()

            if (this.lists.length === 0) {
                this.eventBtn = 'end'
                document.getElementById('end-learn').style.display = 'block'
                return
            }

            if (this.lists[0].is_frequency === 0) {
                this.rand = Math.floor(Math.random() * this.lists.length);
                this.word = this.lists[this.rand]
            } else {
                this.rand = 0
                this.word = this.lists[0]
            }
        }
    },
    mounted() {
        document.getElementById('end-learn').style.display = 'none'
    }
}
</script>

<style scoped>
.btn-m{
    margin: 0 0 0 5%;
}
.choiceType{
    margin: 30px auto 0;
    width: 100%;
    display: flex;
    justify-content: center;
}
.get-word {
    margin: 8% auto;
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
.end-text{
    font-size: 48px;
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
