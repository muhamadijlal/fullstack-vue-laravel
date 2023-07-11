<script>
export default {
    props: {
        bookableId: String,
    },
    data() {
        return {
            isLoading: false,
            reviews: null,
        };
    },
    created() {
        this.isLoading = true;
        axios
            .get(`/api/bookables/${this.bookableId}/reviews`)
            .then((response) => (this.reviews = response.data.data))
            .then(() => (this.isLoading = false));
    },
};
</script>

<template>
    <div style="padding: 1.25rem">
        <h6 class="text-uppercase text-secondary fw-bolder pt-4">
            Review List
        </h6>

        <div v-if="isLoading">Loading...</div>
        <div v-else>
            <div
                class="border-bottom d-none d-md-block"
                v-for="(review, index) in reviews"
                :key="index"
            >
                <div class="row pt-4">
                    <div class="col-md-6">Haidar Ijlal</div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <star-rating
                            :modelValue="review.rating"
                            class="fa-lg"
                        ></star-rating>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{ this.$filters.fromNow(review.created_at) }}
                    </div>
                </div>
                <div class="row py-4">
                    <div class="col-md-12">{{ review.content }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
