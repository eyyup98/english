require('./bootstrap');

import {createApp} from "vue";
import * as VueRouter from 'vue-router'

import Home from "../../app/Modules/HomePage/recources/components/Home"

const routes = [
    {path: '/', component: Home},
    // {path: '/example', component: Navbar}
]

const router = VueRouter.createRouter({
    history: VueRouter.createWebHistory('/'),
    routes,
})

const app = createApp({})

app.use(router)

app.component('home', Home)

app.mount('#app')
