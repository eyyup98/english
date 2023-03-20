<template>
    <div class="modal" tabindex="-1" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Начать заного</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Если вы начнете заного, тогда прошлый прогресс обнулится! Вы согласны на это?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Нет</button>
                    <button type="button" class="btn btn-dark" v-on:click="eventBtn = 'begin'" @click="newBegin">Да</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="myEndModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Сохранить прогресс перед окончанием?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Если вы начнете заного, тогда прошлый прогресс обнулится! Вы согласны на это?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Нет</button>
                    <button type="button" class="btn btn-dark" v-on:click="eventBtn = 'begin'" @click="newBegin">Да</button>
                </div>
            </div>
        </div>
    </div>
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
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary" v-on:click="eventBtn = 'begin'" @click="beginBtn" v-if="learnedBtn < 1">
                    <input type="radio" name="event" id="newBegin" autocomplete="off"> Начать
                </label>
                <label class="btn btn-secondary" @click="modalEvent" v-else>
                    <input type="radio" name="event" id="begin" autocomplete="off"> Начать заного
                </label>
                <label class="btn btn-secondary" v-on:click="eventBtn = 'end'" @click="endBtn">
                    <input type="radio" name="event" id="end" autocomplete="off"> Закончить
                </label>
                <label class="btn btn-secondary" v-on:click="eventBtn = 'begin'" @click="continueBtn" v-if="eventBtn !== 'begin' && learnedBtn > 0">
                    <input type="radio" name="event" id="continue" autocomplete="off"> Продолжить
                </label>
                <label class="btn btn-secondary" @click="saveProgress" v-if="eventBtn === 'begin'">
                    <input type="radio" name="event" id="progress" autocomplete="off"> Сохранить прогресс
                </label>
            </div>

            <div class="btn-group btn-group-toggle" data-toggle="buttons">
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

        <div id="save-print" class="save-print successful" style="display:none">
            <h4>Процесс сохранен!</h4>
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
            <button type="button" class="btn btn-dark" @click="notShow">
                Не показывать больше
            </button>
            <button type="button" class="btn btn-dark btn-m" @click="printFrequency(0)" v-if="word.is_frequency === 1">
                Выводить реже
            </button>
            <button type="button" class="btn btn-dark btn-m" @click="printFrequency(1)" v-if="word.is_frequency === 0">
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
            word: this.nullWord,
            language: null,
            typeWords: 1,
            learnedWords: [],
            learnedBtn: 0,
        }
    },
    methods:{
        async continueBtn(){
            let data = {
                uri: 'learnWords',
                typeWords: this.typeWords,
                progress: this.learnedWords
            }
            await words.dispatch('fetchWords', data)
            this.lists = words.getters.getWords
            this.printWord()
        },
        async saveProgress(){
            await words.dispatch('clearProgress')
            await words.dispatch('saveProgress', this.learnedWords)
            this.learnedBtn = this.learnedWords.length
            document.getElementById('save-print').style.display = 'block'
            setTimeout(function() {
                document.getElementById('save-print').style.display = 'none'
            }, (2000));
        },
        async beginBtn(){
            let data = {
                uri: 'learnWords',
                typeWords: this.typeWords
            }
            await words.dispatch('fetchWords', data)
            this.lists = words.getters.getWords
            this.printWord()
        },
        async newBegin(){
            await words.dispatch('clearProgress')
            this.learnedWords = []
            this.nullWord()
            this.eventBtn = 'begin'
            this.myModal.hide()
            let data = {
                uri: 'learnWords',
                typeWords: this.typeWords
            }
            await words.dispatch('fetchWords', data)
            this.lists = words.getters.getWords
            this.printWord()
        },
        notShow(){
            this.word.is_show = 0
            words.dispatch('saveOneWord', this.word)
            this.nextWord()
        },
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
                is_frequency: 0,
                in_english: '',
                transcription: '',
                in_russia: '',
            }
        },
        endBtn() {
            document.getElementById('end-learn').style.display = 'none'
            this.nullWord()
            this.lists = []
        },
        modalEvent(){
            this.myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                keyboard: false
            })
            this.myModal.show()
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

            this.learnedWords.push({word_progress_id: this.word.word_id})
        }
    },
    async mounted() {
        document.getElementById('end-learn').style.display = 'none'
        await words.dispatch('fetchProgress')
        this.learnedWords = words.getters.getProgress
        this.learnedBtn = this.learnedWords.length
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
    justify-content: space-around;
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
.save-print {
    position: absolute;
    width: 100%;
    margin: 2% auto;
}
@media screen and (max-width: 1000px) {
    .next-btn,
    .choiceType{
        display: flex;
        flex-direction:column;
        align-items: center;
    }
    .choiceType .btn-secondary{
        margin: 0 0 5% 0;
    }
    .btn.btn-dark{
        margin: 0 0 1% 0;
    }
    .save-print {
        margin: 1% auto;
    }
}
</style>
