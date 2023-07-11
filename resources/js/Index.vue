<script>
import { mapState } from "pinia";
import { useSearchStore } from "./store/store";

export default {
    data() {
        return {
            lastSearch: this.$store.lastSearch,
        };
    },
    computed: {
        ...mapState(useSearchStore, {
            isLoggedIn: "isLoggedIn",
        }),
        itemsInBasket() {
            return this.$store.itemsInBasket;
        },
    },
    methods: {
        async logout() {
            try {
                axios.get("/logout");
                this.$store.logout();
            } catch (error) {
                this.$store.logout();
            }
        },
    },
};
</script>

<template>
    <div>
        <nav
            class="navbar navbar-expand-lg px-4 bg-white border-bottom navbar-light"
        >
            <div class="container-fluid">
                <router-link class="navbar-brand mr-auto" :to="{ name: 'home' }"
                    >LaravelBnb</router-link
                >
                <ul class="navbar-nav">
                    <div class="nav-item">
                        <router-link
                            class="btn nav-button"
                            :to="{ name: 'basket' }"
                        >
                            Basket
                            <span
                                v-if="itemsInBasket"
                                class="badge bg-secondary"
                                >{{ itemsInBasket }}</span
                            >
                        </router-link>
                    </div>

                    <li class="nav-item" v-if="!isLoggedIn">
                        <router-link :to="{ name: 'register' }" class="nav-link"
                            >Register</router-link
                        >
                    </li>
                    <li class="nav-item" v-if="!isLoggedIn">
                        <router-link :to="{ name: 'login' }" class="nav-link"
                            >Sign-in</router-link
                        >
                    </li>
                    <li class="nav-item" v-if="isLoggedIn">
                        <a class="nav-link" href="#" @click.prevent="logout"
                            >Logout</a
                        >
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container my-4 px-4">
            <router-view></router-view>
        </div>
    </div>
</template>
