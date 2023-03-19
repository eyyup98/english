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
                        <th id="in_english" @click="sortMethod('in_english')" scope="col" class="text width delete">Английский</th>
                        <th id="transcription" @click="sortMethod('transcription')" scope="col" class=" delete">Транскрипция/Доп.инф.</th>
                        <th id="in_russia" @click="sortMethod('in_russia')" scope="col" class="text width delete">Русский</th>
                        <th id="word_type_id" @click="sortMethod('word_type_id')" scope="col" class="text delete">Тип</th>
                        <th id="is_show" @click="sortMethod('is_show')" scope="col" class="text delete">Показывать</th>
                        <th scope="col" class="text">Действия</th>
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

                        <th v-if="count[index].status_show === 'show'">{{ row.name }}</th>
                        <th v-else>
                            <select class="form-control form-inline" v-model="row.word_type_id">
                                <option v-for="item in types" :value="item.word_type_id">{{item.name}}</option>
                            </select>
                        </th>

                        <th v-if="row.is_show === 1">
                            <i class="bi-check-circle hover" aria-label="Mute" @dblclick="saveBtn(index, true)"></i>
                        </th>

                        <th v-else>
                            <i class="bi-x-circle hover" aria-label="Mute" @dblclick="saveBtn(index, true)"></i>
                        </th>

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
            types: null,
            sortList: [],
        }
    },
    methods:{
        sortMethod(setSort){
            let flag = false
            for (const sortKey in this.sortList) {
                if (this.sortList[sortKey].sort === setSort) {
                    flag = true
                    if (this.sortList[sortKey].sParam === 'asc') {
                        this.sortList[sortKey].sParam = 'desc'
                        document.getElementById(setSort).className = document.getElementById(setSort).className.replace(' asc', '')
                        document.getElementById(setSort).className += ' desc'
                    } else if (this.sortList[sortKey].sParam === 'desc') {
                        this.sortList.splice(sortKey, 1)
                        document.getElementById(setSort).className = document.getElementById(setSort).className.replace(' desc', '')
                        document.getElementById(setSort).className += ' delete'
                    }
                }
            }

            if (!flag) {
                document.getElementById(setSort).className = document.getElementById(setSort).className.replace('delete', '')
                document.getElementById(setSort).className += ' asc'
                this.sortList.push({sort: setSort, sParam: 'asc'})
            }

            this.getData()
        },
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
        async saveBtn(index, changeShow = false) {
            if (changeShow === true){
                if (this.lists[index].is_show === 1) {
                    this.lists[index].is_show = 0
                } else {
                    this.lists[index].is_show = 1
                }
            }

            await words.dispatch('saveOneWord', this.lists[index])
            for (let i = 0; i < this.lists.length; i++) {
                this.count[i].status_show = 'show'
            }
            await this.getData()
        },
        deleteBtn(index) {
            this.deleteId = index
            this.myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                keyboard: false
            })
            this.myModal.show()
        },
        async getData(){
            let data = {
                search: this.search,
                uri: 'allWords',
                sort: this.sortList
            }
            await words.dispatch('fetchWords', data)
            this.lists = words.getters.getWords

            for (let i = 0; i < this.lists.length; i++) {
                this.count.push({status_show: 'show'})
            }
        },
        async getTypes(){
            await words.dispatch('fetchTypes')
            this.types = words.getters.getTypes
        },
    },
    mounted() {
        this.getData()
        this.getTypes()
    }
}
</script>

<style scoped>
.div-table{
    max-height: 80vh;
    overflow-y: auto;
}

.div-table table {
    border-collapse: collapse;
}

.div-table th{
    border: 1px solid #454444;
}

.asc::after {
    color: #acdecf;
    margin: 0 0 0 10px;
    content: "▼"
}

.desc::after {
    color: #acdecf;
    margin: 0 0 0 10px;
    content: "▲"
}

.delete::after {
    color: #454449;
    margin: 0 0 0 10px;
    content: "↕"
}

.text{
    padding-bottom: 20px;
    padding-top: 20px;
}
.params{
    max-width: 100%;
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
.width{
    width: 30%;
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
