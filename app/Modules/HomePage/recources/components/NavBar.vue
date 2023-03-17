<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark pad">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">ENGLISH</a>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <router-link class="nav-link" to="/">Учить слова <span class="sr-only">(current)</span></router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/addWords">Добавить слова</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/allWords">Список слов</router-link>
                </li>
            </ul>
            <div class="form-inline my-2 my-lg-0">
                <x-field>
                    <input class="form-control mr-sm-2" type="search"
                           placeholder="Поиск" aria-label="Search" v-model="search" v-on:keyup.enter="searchBtn">
                    <span class="close" @click="clearSearch" v-if="search !== null && search !== ''">&times;</span>
                </x-field>
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit" @click="searchBtn">Поиск слов</button>
                <div class="list-group search-param" v-if="lists !== null || lists !== ''">
                    <dl class="list-group-item list-group-item-action" v-for="(row, index) in lists">
                        <dt>{{row.in_english}} - {{row.in_russia}}</dt>
                    </dl>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import {words} from "../../../Words/resources/js/WordsMethods"

export default {
    name: "NavBar",
    data(){
        return {
            search: null,
            lists: null,
        }
    },
    methods: {
        clearSearch(){
            this.search = ''
            this.lists = null
        },
        async searchBtn() {
            if (this.search !== null && this.search !== '') {
                let data = {
                    search: this.search,
                    uri: 'navBar'
                }

                await words.dispatch('fetchWords', data)
                this.lists = words.getters.getWords
            } else {
                this.lists = null
            }
        },
    }
}
</script>

<style scoped>
.pad{
    padding: 10px 10%;
}
.search-param{
    position: absolute;
    top: 65px;
    min-width: 17%;
    max-width: 30%;
    max-height: 50vh;
    overflow-y: auto;
}
@media screen and (max-width: 990px) {
    .search-param{
        max-width: 225px;
        max-height: 18vh;
        top: 55px;
    }
}
@media screen and (max-width: 580px) {
    .btn{
        margin: 0 0 0 10px;
    }
}
</style>
