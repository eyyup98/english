<template>
    <NavBar></NavBar>

    <div class="container">
        <div class="next-btn" style="margin: 30px auto" v-if="viewInputs === false">
            <button type="button" class="btn btn-dark" @click="addWords">Добавить слова</button>
        </div>

        <div class="input-block successful" v-if="viewInputs === true">
            <div class="div-input">
                <span class="span-input">Английский</span>
                <textarea id="in_english" class="flex-input" v-model="in_english"></textarea>
            </div>
            <div class="div-input">
                <span class="span-input">Транскрипция</span>
                <textarea id="transcription" class="flex-input" v-model="transcription"></textarea>
            </div>
            <div class="div-input">
                <span class="span-input">Русский</span>
                <textarea id="in_russia" class="flex-input" v-model="in_russia"></textarea>
            </div>
        </div>

        <div class="words-type successful" style="margin: 30px auto" v-if="viewInputs === true">
            <select class="form-control form-inline" v-model="word_type_id">
                <option v-for="item in types" :value="item.word_type_id">{{item.name}}</option>
            </select>
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
            viewInputs: true,
            in_english: '',
            in_russia: '',
            transcription: '',
            types: null,
            word_type_id: 1,
        }
    },
    methods:{
        async getTypes(){
            await words.dispatch('fetchTypes')
            this.types = words.getters.getTypes
        },
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


            console.log(this.word_type_id)
            const data = {
                in_english: in_english,
                in_russia: in_russia,
                transcription: transcription,
                word_type_id: this.word_type_id
            }
            words.dispatch('saveWords', data)

            this.viewInputs = false
        },
    },
    mounted() {
        this.getTypes()
    }
}
</script>

<style scoped>
.container{
    height: 100vh;
}
.words-type{
    max-width: 20%;
    margin: 0 auto;
}
.input-block{
    display: flex;
    font-size: 28px;
    padding: 50px 50px 0 50px;
    justify-content:space-between;
}
.flex-input{
    border: #dddcdc solid 1px;
    min-height: 30vh;
    height: 40vh;
    max-height: 55vh;
    border-bottom: 2px solid #2d3748;
    text-align: left;
    max-width: 100%;
}
.span-input{
    color: #2d3748;
}
.successful {
    transition: 3s;
}
.div-input{
    max-width: 30%;
    flex: 0 1 33.333%;
}
@media screen and (max-width: 995px) {
    .input-block{
        display: flex;
        flex-wrap: wrap;
        justify-content:flex-start;
        font-size: 22px;
        padding: 0 10px;
    }
    .flex-input{
        height: 50vh;
    }
    .div-input{
        flex: none;
        max-width: 33.33%;
    }
    .container{
        min-width: 100%;
    }
}

@media screen and (max-width: 995px) {
    .input-block{
        font-size: 16px;
    }
}
</style>
