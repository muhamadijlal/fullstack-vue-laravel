<script>
export default {
    props: {
        id: String,
        title: String,
        description: String,
    },
};
</script>

<template>
    <div class="card w-100">
        <div class="card-body">
            <router-link :to="{ name: 'bookable', params: { id } }">
                <h5 class="card-title">{{ title }}</h5>
            </router-link>
            <p class="card-text">{{ description }}</p>
        </div>
    </div>
</template>
