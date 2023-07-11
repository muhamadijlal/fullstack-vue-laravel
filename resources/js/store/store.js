import { defineStore } from "pinia";
import { isLoggedIn, logOut } from "../shared/utils/Auth";

export const useSearchStore = defineStore("search", {
    state: () => ({
        lastSearch: {
            from: null,
            to: null,
        },
        basket: {
            items: [],
        },
        isLoggedIn: false,
        user: {},
    }),
    getters: {
        itemsInBasket: (state) => state.basket.items.length,
        inBasketAlready: (state) => {
            return (id) =>
                state.basket.items.reduce(
                    (result, item) => result || item.bookable.id === id,
                    false
                );
        },
    },
    actions: {
        loadStoredState() {
            const lastSearch = localStorage.getItem("lastSearch");
            if (lastSearch) {
                // JSON.parse() => parse string to JSON format
                this.lastSearch = JSON.parse(lastSearch);
            }

            const basket = localStorage.getItem("basket");
            if (basket) {
                // JSON.parse() => parse string to JSON format
                this.basket = JSON.parse(basket);
            }

            this.setLoggedIn(isLoggedIn());
        },
        setLastSearch(lastSearch) {
            localStorage.setItem("lastSearch", JSON.stringify(lastSearch));
        },

        // Logged
        setUser(payload) {
            this.user = payload;
        },
        setLoggedIn(payload) {
            this.isLoggedIn = payload;
        },
        async loadUser() {
            if (isLoggedIn()) {
                try {
                    const user = await axios.get("/user");
                    this.setUser(user);
                    this.setLoggedIn(true);
                } catch (error) {
                    this.logout();
                }
            }
        },
        logout() {
            this.setUser({});
            this.setLoggedIn(false);
            logOut();
        },

        // Basket
        addToBasket(payload) {
            this.basket.items.push(payload);
            localStorage.setItem("basket", JSON.stringify(this.basket));
        },
        removeFromBasket(payload) {
            this.basket.items = this.basket.items.filter(
                (item) => item.bookable.id !== payload
            );
            localStorage.setItem("basket", JSON.stringify(this.basket));
        },
        clearBasket() {
            this.basket.items = [];
            localStorage.setItem("basket", JSON.stringify(this.basket));
        },
    },
});
