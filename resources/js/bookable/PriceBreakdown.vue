<script>
export default {
    props: {
        price: null,
    },
};
</script>

<template>
    <div>
        <h6 class="text-uppercase text-secondary fw-bolder">price breakdown</h6>

        <div
            v-for="(days, price) in price.breakdown"
            :key="price"
            class="py-2 border-bottom border-top d-flex justify-content-between"
        >
            <span>{{ days }} x ${{ price }}</span>
            <span>${{ days * price }}</span>
        </div>

        <div class="fw-bolder py-2 d-flex justify-content-between">
            <span> Total </span>
            <span> ${{ price.total }}</span>
        </div>
    </div>
</template>
