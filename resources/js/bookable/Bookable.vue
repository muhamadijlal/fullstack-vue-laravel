<script>
import Availability from "./Availability.vue";
import ReviewList from "./ReviewList.vue";
import priceBreakdown from "./PriceBreakdown.vue";
import { mapState } from "pinia";
import { useSearchStore } from "../store/store";

export default {
    components: {
        Availability,
        ReviewList,
        priceBreakdown,
    },
    data() {
        return {
            bookable: null,
            isLoading: false,
            price: null,
        };
    },
    computed: {
        ...mapState(useSearchStore, {
            lastSearch: "lastSearch",
            inBasketAlready() {
                if (null === this.bookable) {
                    return false;
                }

                return this.$store.inBasketAlready(this.bookable.id);
            },
        }),
    },
    created() {
        this.isLoading = true;
        axios
            .get(`/api/bookables/${this.$route.params.id}`)
            .then((response) => {
                this.bookable = response.data.data;
                this.isLoading = false;
            });
    },
    methods: {
        async checkPrice(hasAvailability) {
            if (!hasAvailability) {
                this.price = null;
                return;
            }

            try {
                this.price = (
                    await axios.get(
                        `/api/bookables/${this.bookable.id}/price?from=${this.lastSearch.from}&to=${this.lastSearch.to}`
                    )
                ).data.data;
            } catch (err) {
                this.price = null;
            }
        },
        addToBasket() {
            const data = {
                bookable: this.bookable,
                price: this.price,
                dates: this.lastSearch,
            };

            this.$store.addToBasket(data);
        },
        removeFromBasket() {
            this.$store.removeFromBasket(this.bookable.id);
        },
    },
};
</script>

<template>
    <div class="row">
        <div class="col-md-8 pb-4">
            <div class="card">
                <div class="card-body">
                    <div v-if="!isLoading">
                        <h2>{{ bookable.title }}</h2>
                        <hr />
                        <article>
                            {{ bookable.description }}
                        </article>
                    </div>
                    <div v-else>Loading...</div>
                </div>
            </div>

            <review-list :bookable-id="this.$route.params.id"></review-list>
        </div>
        <div class="col-md-4">
            <availability
                :bookable-id="this.$route.params.id"
                @availability="checkPrice($event)"
            ></availability>

            <div class="d-grid mt-3">
                <transition-group>
                    <price-breakdown
                        v-if="price"
                        :price="price"
                        class="mb-3"
                    ></price-breakdown>
                    <button
                        class="btn btn-outline-secondary btn-block"
                        v-if="price"
                        @click="addToBasket"
                        :disabled="inBasketAlready"
                    >
                        Book Now
                    </button>

                    <button
                        class="btn btn-outline-secondary btn-block mt-3"
                        v-if="inBasketAlready"
                        @click="removeFromBasket"
                    >
                        Remove from basket
                    </button>

                    <div v-if="inBasketAlready" class="mt-4 text-muted warning">
                        Seems you've added this object to basket already. If you
                        want to change dates, remove from the basket first.
                    </div>
                </transition-group>
            </div>
        </div>
    </div>
</template>

<style scoped>
.warning {
    font-size: 0.7rem;
}
</style>
