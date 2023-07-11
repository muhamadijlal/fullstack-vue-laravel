<script>
export default {
    props: {
        modelValue: Number,
    },
    emits: ["update:modelValue"],
    computed: {
        halfStars() {
            const fraction = Math.round(
                (this.modelValue - Math.floor(this.modelValue)) * 100
            );

            return fraction > 0 && fraction < 50;
        },
        fullStars() {
            return Math.round(this.modelValue);
        },
        emptyStars() {
            return 5 - Math.ceil(this.modelValue);
        },
    },
};
</script>

<template>
    <div class="d-flex">
        <font-awesome-icon
            :icon="['fas', 'star']"
            v-for="star in fullStars"
            :key="'full' + star"
            @click="$emit('update:modelValue', star)"
        />
        <font-awesome-icon
            :icon="['fas', 'star-half-stroke']"
            v-if="halfStars"
        />
        <font-awesome-icon
            :icon="['far', 'star']"
            v-for="star in emptyStars"
            :key="'empty' + star"
            @click="$emit('update:modelValue', fullStars + star)"
        />
    </div>
</template>
