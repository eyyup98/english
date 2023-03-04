<template>
    <div class="form-group" style="padding: 10px">
        <h1>Поиск городов</h1>
        <div style="display: flex; justify-content: left; margin: 10px 0">
            <input style="width: 50%; margin: 0 20px 0 0" type="text" class="form-control" v-model="search" @keyup.enter="keyDown" id="search-input" aria-describedby="textHelp" placeholder="Введите название города">
            <button type="button" :onclick="keyDown" class="btn btn-success">Найти</button>
        </div>
        <ul v-if="lists.length > 0" class="list-group" style="width: 50%; overflow-x: hidden; overflow-y: auto; max-height: 70vh; text-align:justify; border: 1px solid #c8c8c8; border-radius: 5px">
            <li class="list-group-item" v-for="list in lists">{{list.name}}</li>
        </ul>
    </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations} from 'vuex';

export default {
    name: "SearchCities",
    data(){
        return{
            search:'',
            lists:''
        }
    },
    computed: {
        ...mapGetters({
        })
    },
    methods: {
        async keyDown() {
            let test = [];
            this.lists = await axios.get('/searchCities', {
                params: {
                    city: this.search,
                }
            }).then((response) => {
                test = response.data;
                return test
            })
        },
        ...mapMutations([
        ]),
        ...mapActions({
        }),
    },
}

</script>

<style scoped>

</style>
