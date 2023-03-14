<template>
    <div class="modal" tabindex="-1" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Удаление</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Вы действительно хотите удалить это слово?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-dark" @click="deleteElement">Удалить</button>
                </div>
            </div>
        </div>
    </div>
    <NavBar></NavBar>

    <div class="container">

        <div class="form-inline my-2 my-lg-0 search">
            <x-field>
                <input class="form-control mr-sm-2" type="search" v-on:keyup.enter="getData"
                       v-model="search" placeholder="Поиск" aria-label="Search">
                <span class="close" @click="clearSearch" v-if="search !== null && search !== ''">&times;</span>
            </x-field>
            <button class="btn btn-dark" type="submit" @click="getData">Поиск слов</button>
        </div>
        <div class="div-table">
            <table class="table table-success table-striped table-hover params">
                <thead class="table-dark sticky" >
                    <tr>
                        <th scope="col">Английский</th>
                        <th scope="col">Транскрипция/Доп.инф.</th>
                        <th scope="col">Русский</th>
                        <th scope="col">Действия</th>
                    </tr>
                </thead>
                <tbody class="table-body" v-for="(row, index) in lists">
                    <tr>
                        <th v-if="count[index].status_show === 'show'">{{ row.in_english }}</th>
                        <th v-else><input class="form-control" v-model="row.in_english"></th>

                        <th v-if="count[index].status_show === 'show'">{{ row.transcription }}</th>
                        <th v-else><input class="form-control" v-model="row.transcription"></th>

                        <th v-if="count[index].status_show === 'show'">{{ row.in_russia }}</th>
                        <th v-else><input class="form-control" v-model="row.in_russia"></th>

                        <th v-if="count[index].status_show === 'show'">
                            <i class="bi-pencil-fill hover" aria-label="Mute" @click="editBtn(index)"></i>
                            <i class="bi-trash3-fill hover" aria-label="Mute" @click="deleteBtn(index)"
                               data-toggle="modal" data-target="#exampleModal"></i>
                        </th>
                        <th v-else>
                            <i class="bi-x-square-fill hover" aria-label="Mute" @click="cancelBtn(index)"></i>
                            <i class="bi-save-fill hover" aria-label="Mute" @click="saveBtn(index)"></i>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import NavBar from "../../../HomePage/recources/components/NavBar";
import {words} from "../js/WordsMethods"

export default {
    name: "AllWords",
    components: {
        NavBar,
    },
    data(){
        return {
            lists: null,
            count: [],
            search: null,
            deleteId: null,
            myModal: null,
        }
    },
    methods:{
        clearSearch(){
            this.search = ''
            this.getData()
        },
        deleteElement(){
            this.myModal.hide()
            words.dispatch('deleteWord', this.lists[this.deleteId].word_id)
            this.lists.splice(this.deleteId, 1);
        },
        editBtn(index) {
            for (let i = 0; i < this.lists.length; i++) {
                this.count[i].status_show = 'show'
            }
            this.count[index].status_show = 'edit'
        },
        cancelBtn(index) {
            for (let i = 0; i < this.lists.length; i++) {
                this.count[i].status_show = 'show'
            }
        },
        saveBtn(index) {
            words.dispatch('saveOneWord', this.lists[index])
            for (let i = 0; i < this.lists.length; i++) {
                this.count[i].status_show = 'show'
            }
        },
        deleteBtn(index) {
            this.deleteId = index
            this.myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                keyboard: false
            })
            this.myModal.show()
        },
        async getData(){
            await words.dispatch('fetchWords', this.search)
            this.lists = words.getters.getWords

            for (let i = 0; i < this.lists.length; i++) {
                this.count.push({status_show: 'show'})
            }
        },
    },
    mounted() {
        this.getData()
    }
}
</script>

<style scoped>
.params{
    max-width: 90%;
    margin: 0 auto;
}
.table-body{
    border: #2d3748 2px solid;
}
.sticky {
    position: sticky;
    top: 0;
    margin: 2em;
    min-height: 2em;
    background: lightpink;
}
.div-table{
    max-height: 80vh;
    overflow-y: auto;
}
.hover{
    font-size: 1.5rem;
    margin: 0 10px 0 0;
}
.hover:hover{
    color: #3c9db4;
}
.search{
    display:flex;
    justify-content:center;
    padding: 30px 0;
}
@media screen and (max-width: 580px) {
    .btn-dark{
        margin: 0 0 0 10px;
    }
}
</style>
