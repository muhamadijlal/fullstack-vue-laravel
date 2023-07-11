import Bookables from "./bookables/Bookables.vue";
import Bookable from "./bookable/bookable.vue";
import Review from "./review/Review.vue";
import Basket from "./basket/Basket.vue";
import Login from "./auth/Login.vue";
import Register from "./auth/Register.vue";

import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        component: Bookables,
        name: "home",
    },
    {
        path: "/bookable/:id",
        component: Bookable,
        name: "bookable",
    },
    {
        path: "/review/:id",
        component: Review,
        name: "review",
    },
    {
        path: "/basket",
        component: Basket,
        name: "basket",
    },
    {
        path: "/auth/login",
        component: Login,
        name: "login",
    },
    {
        path: "/auth/register",
        component: Register,
        name: "register",
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes, // shorthand for `routes: routes`
});

export default router;
