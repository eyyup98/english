require('./bootstrap');

import {createApp} from "vue";
import * as VueRouter from 'vue-router'
// import PortalVue from 'portal-vue'

import AddWords from "../../app/Modules/Words/resources/components/AddWords";
import Home from "../../app/Modules/HomePage/recources/components/Home"
import AllWords from "../../app/Modules/Words/resources/components/AllWords";

const routes = [
    {path: '/', component: Home},
    {path: '/addWords', component: AddWords},
    {path: '/allWords', component: AllWords},
]

const router = VueRouter.createRouter({
    history: VueRouter.createWebHistory('/'),
    routes,
})

const app = createApp({})

app.use(router)
// app.use(PortalVue)

app.component('home', Home)

app.mount('#app')
