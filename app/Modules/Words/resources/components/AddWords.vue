<template>
    <NavBar></NavBar>

    <div class="container">
        <div class="next-btn" style="margin: 30px auto">
            <button type="button" class="btn btn-dark" @click="addWords">Добавить слова</button>
        </div>

        <div class="input-block successful" v-if="viewInputs === true">
            <div>
                <span class="span-input">Английский</span>
                <textarea id="in_english" class="flex-input" v-model="in_english"></textarea>
            </div>
            <div>
                <span class="span-input">Транскрипция</span>
                <textarea id="transcription" class="flex-input" v-model="transcription"></textarea>
            </div>
            <div>
                <span class="span-input">Русский</span>
                <textarea id="in_russia" class="flex-input" v-model="in_russia"></textarea>
            </div>
        </div>

        <div class="next-btn successful" style="margin: 30px auto" v-if="viewInputs === true">
            <button type="button" class="btn btn-success" @click="saveWords">Сохранить</button>
        </div>

        <div class="successful" v-if="viewInputs === false">
            <h3>Слова успешно сохранены</h3>
        </div>
    </div>

</template>

<script>
import NavBar from "../../../HomePage/recources/components/NavBar";
import {words} from "../js/WordsMethods"

export default {
    name: "AddWords",
    components: {
        NavBar,
    },
    data(){
        return {
            viewInputs: null,
            in_english: '',
            in_russia: '',
            transcription: '',
        }
    },
    methods:{
        addWords(){
            this.viewInputs = true

        },
        saveWords(){
            this.viewInputs = null
            let in_english = ''
            if (this.in_english !== '') {
                in_english = this.in_english.split("\n");
            }
            let in_russia = ''
            if (this.in_russia !== '') {
                in_russia = this.in_russia.split("\n");
            }
            let transcription = ''
            if (this.transcription !== '') {
                transcription = this.transcription.split("\n");
            }

            if (in_english.length !== in_russia.length){
                this.viewInputs = true
                document.getElementById('in_english').style.border = '1px solid red';
                document.getElementById('in_russia').style.border = '1px solid red';
                alert('Количество английских и русских слов дожно совпадать!')
                return false
            } else {
                document.getElementById('in_english').style.border = null
                document.getElementById('in_russia').style.border = null;
            }

            if (this.in_english === ''){
                this.viewInputs = true
                document.getElementById('in_english').style.border = '1px solid red';
                document.getElementById('in_russia').style.border = '1px solid red';
                alert('Необходимо добавить хотя бы одно слово!')
                return false
            }

            const data = {
                in_english: in_english,
                in_russia: in_russia,
                transcription: transcription,
            }
            words.dispatch('saveWords', data)

            this.viewInputs = false
        },
    }
}
</script>

<style scoped>
.input-block{
    display: flex;
    font-size: 28px;
    padding: 0 50px;
}
.flex-input{
    flex: 0 1 33.333%;
    border: #dddcdc solid 1px;
    height: 500px;
    border-bottom: 3px solid #2d3748;
    text-align: left;
}
.span-input{
    color: #2d3748;
}
.successful {
    transition: 3s;
}
</style>
