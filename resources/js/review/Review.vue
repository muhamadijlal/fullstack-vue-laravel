<script>
import { is404, is422 } from "../shared/utils/response";
import validationErrors from "../shared/mixins/validationErrors";
import _ from "lodash";

export default {
    mixins: [validationErrors],
    data() {
        return {
            review: {
                id: null,
                rating: 5,
                content: null,
            },
            isLoading: false,
            isSending: false,
            existingReview: null,
            booking: null,
            error: false,
            success: false,
        };
    },
    async created() {
        this.review.id = this.$route.params.id;
        this.isLoading = true;

        try {
            this.existingReview = (
                await axios.get(`/api/reviews/${this.review.id}`)
            ).data.data;
        } catch (err) {
            if (is404(err)) {
                try {
                    this.booking = (
                        await axios.get(
                            `/api/booking-by-review/${this.review.id}`
                        )
                    ).data.data;
                } catch (err) {
                    this.error = !is404(err);
                }
            } else {
                this.error = true;
            }
        }
        this.isLoading = false;
    },
    computed: {
        alreadyReviewed() {
            return this.hasReview || !this.booking;
        },
        hasReview() {
            return this.existingReview !== null;
        },
        hasBooking() {
            return this.booking !== null;
        },
        oneColumn() {
            return !this.isLoading && this.alreadyReviewed;
        },
        twoColumns() {
            return this.isLoading || !this.alreadyReviewed;
        },
    },
    methods: {
        submit() {
            this.errors = null;
            this.isSending = true;
            this.success = false;

            axios
                .post(`/api/reviews`, this.review)
                .then((response) => {
                    this.success = 201 === response.status;
                })
                .catch((err) => {
                    if (is422(err)) {
                        const errors = err.response.data.errors;

                        if (errors["content"] && 1 === _.size(errors)) {
                            this.errors = errors;
                            return;
                        }

                        this.error = true;
                    }
                })
                .then(() => (this.isSending = false));
        },
    },
};
</script>

<template>
    <div>
        <success v-if="success"
            >You've left a review, thank you very much!</success
        >
        <fatal-error v-if="error"></fatal-error>
        <div class="row" v-if="!success && !errors">
            <div :class="[{ 'col-md-4': twoColumns }, { 'd-none': oneColumn }]">
                <div class="card">
                    <div class="card-body">
                        <div v-if="isLoading">Loading...</div>
                        <div v-if="hasBooking">
                            <p>
                                Stayed at
                                <router-link
                                    :to="{
                                        name: 'bookable',
                                        params: {
                                            id: booking.bookable.bookable_id,
                                        },
                                    }"
                                    >{{ booking.bookable.title }}</router-link
                                >
                            </p>
                            <p>From {{ booking.from }} to {{ booking.to }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div
                :class="[
                    { 'col-md-8': twoColumns },
                    { 'col-md-12': oneColumn },
                ]"
            >
                <div v-if="isLoading">Loading...</div>
                <div v-else>
                    <div v-if="alreadyReviewed">
                        <h3>You've already left a review for this booking!</h3>
                    </div>
                    <div v-else>
                        <div class="form-group mb-3">
                            <label class="text-muted"
                                >Select the star rating (1 is worst - 5 is
                                best)</label
                            >
                            <star-rating
                                class="fa-3x"
                                v-model="review.rating"
                            />
                        </div>
                        <div class="form-group mb-3">
                            <label for="content" class="text-muted"
                                >Describe your experience with</label
                            >
                            <textarea
                                name="content"
                                class="form-control"
                                cols="30"
                                rows="10"
                                v-model="review.content"
                                :class="[{ 'is-invalid': errorFor('content') }]"
                            ></textarea>
                            <v-errors :errors="errorFor('content')"></v-errors>

                            <div class="d-grid mt-3">
                                <button
                                    class="btn btn-lg btn-primary"
                                    @click.prevent="submit"
                                    :disabled="isSending"
                                >
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
