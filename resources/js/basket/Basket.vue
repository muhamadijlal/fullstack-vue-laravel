<script>
import { mapState } from "pinia";
import { useSearchStore } from "../store/store";
import validationErrors from "../shared/mixins/validationErrors";

export default {
    mixins: [validationErrors],
    data() {
        return {
            isLoading: false,
            bookingAttempted: false,
            customer: {
                first_name: null,
                last_name: null,
                email: null,
                street: null,
                city: null,
                country: null,
                state: null,
                zip: null,
            },
        };
    },
    computed: {
        ...mapState(useSearchStore, {
            itemsInBasket: "itemsInBasket",
            basket: (state) => state.basket.items,
        }),
        success() {
            return (
                !this.isLoading &&
                0 === this.itemsInBasket &&
                this.bookingAttempted
            );
        },
    },
    methods: {
        async book() {
            this.isLoading = true;
            this.errors = null;
            this.bookingAttempted = false;

            try {
                await axios.post(`/api/checkout`, {
                    customer: this.customer,
                    bookings: this.basket.map((basketItem) => ({
                        bookable_id: basketItem.bookable.id,
                        from: basketItem.dates.from,
                        to: basketItem.dates.to,
                    })),
                });

                this.$store.clearBasket();
            } catch (error) {
                this.errors = error.response?.data.errors;
            }

            this.isLoading = false;
            this.bookingAttempted = true;
        },
    },
};
</script>

<template>
    <div>
        <success v-if="success">Congratulations of you're bookings!</success>
        <div class="row" v-else>
            <div class="col-md-8" v-if="itemsInBasket">
                <div class="row mb-3">
                    <div class="col-md-6 form-group">
                        <label for="first_name">First Name</label>
                        <input
                            type="text"
                            class="form-control"
                            name="first_name"
                            v-model="customer.first_name"
                            :class="[
                                {
                                    'is-invalid': errorFor(
                                        'customer.first_name'
                                    ),
                                },
                            ]"
                        />
                        <v-errors
                            :errors="errorFor('customer.first_name')"
                        ></v-errors>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="last_name">Last Name</label>
                        <input
                            type="text"
                            class="form-control"
                            name="last_name"
                            v-model="customer.last_name"
                            :class="[
                                {
                                    'is-invalid':
                                        errorFor('customer.last_name'),
                                },
                            ]"
                        />
                        <v-errors
                            :errors="errorFor('customer.last_name')"
                        ></v-errors>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            v-model="customer.email"
                            :class="[
                                {
                                    'is-invalid': errorFor('customer.email'),
                                },
                            ]"
                        />
                        <v-errors
                            :errors="errorFor('customer.email')"
                        ></v-errors>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 form-group">
                        <label for="street">Street</label>
                        <input
                            type="text"
                            class="form-control"
                            name="street"
                            v-model="customer.street"
                            :class="[
                                {
                                    'is-invalid': errorFor('customer.street'),
                                },
                            ]"
                        />
                        <v-errors
                            :errors="errorFor('customer.street')"
                        ></v-errors>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="city">City</label>
                        <input
                            type="text"
                            class="form-control"
                            name="city"
                            v-model="customer.city"
                            :class="[
                                {
                                    'is-invalid': errorFor('customer.city'),
                                },
                            ]"
                        />
                        <v-errors
                            :errors="errorFor('customer.city')"
                        ></v-errors>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 form-group">
                        <label for="country">Country</label>
                        <input
                            type="text"
                            class="form-control"
                            name="country"
                            v-model="customer.country"
                            :class="[
                                {
                                    'is-invalid': errorFor('customer.country'),
                                },
                            ]"
                        />
                        <v-errors
                            :errors="errorFor('customer.country')"
                        ></v-errors>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="state">State</label>
                        <input
                            type="text"
                            class="form-control"
                            name="state"
                            v-model="customer.state"
                            :class="[
                                {
                                    'is-invalid': errorFor('customer.state'),
                                },
                            ]"
                        />
                        <v-errors
                            :errors="errorFor('customer.state')"
                        ></v-errors>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="zip">Zip</label>
                        <input
                            type="text"
                            class="form-control"
                            name="zip"
                            v-model="customer.zip"
                            :class="[
                                {
                                    'is-invalid': errorFor('customer.zip'),
                                },
                            ]"
                        />
                        <v-errors :errors="errorFor('customer.zip')"></v-errors>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="d-grid gap-2">
                        <button
                            class="btn btn-primary"
                            type="button"
                            @click.prevent="book"
                            :disabled="isLoading"
                        >
                            <span v-if="!isLoading">Book Now!</span>
                            <span v-if="isLoading"
                                ><font-awesome-icon
                                    :icon="['fass', 'spinner']"
                                    class="fa-spin animated"
                                />
                                Process...</span
                            >
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-8" v-else>
                <div class="jumbotron">
                    <h1>Empty</h1>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex justify-content-between">
                    <h6
                        class="text-uppercase text-secondary font-weight-bolder"
                    >
                        Your Cart
                    </h6>
                    <h6 class="badge bg-secondary text-uppercase">
                        <span v-if="itemsInBasket">
                            Items {{ itemsInBasket }}</span
                        >
                        <span v-else> Empty </span>
                    </h6>
                </div>
                <transition-group>
                    <div v-for="item in basket" :key="item.bookable.id">
                        <div
                            class="py-2 border-top d-flex justify-content-between"
                        >
                            <span>
                                <router-link
                                    :to="{
                                        name: 'bookable',
                                        params: { id: item.bookable.id },
                                    }"
                                >
                                    {{ item.bookable.title }}
                                </router-link>
                            </span>
                            <span class="font-weight-bold">
                                ${{ item.price.total }}
                            </span>
                        </div>

                        <div class="py-2 d-flex justify-content-between">
                            <span> From {{ item.dates.from }} </span>
                            <span> To {{ item.dates.to }} </span>
                        </div>

                        <div class="py-2 text-end">
                            <button
                                class="btn btn-sm btn-outline-secondary"
                                @click="
                                    this.$store.removeFromBasket(
                                        item.bookable.id
                                    )
                                "
                            >
                                <font-awesome-icon :icon="['fas', 'trash']" />
                            </button>
                        </div>
                    </div>
                </transition-group>
            </div>
        </div>
    </div>
</template>

<style scoped>
h6.badge {
    font-size: 100%;
}

a {
    text-decoration: none;
    color: black;
}

.jumbotron {
    width: 100%;
    background-color: #e5e5e5;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 14rem;
}
</style>
