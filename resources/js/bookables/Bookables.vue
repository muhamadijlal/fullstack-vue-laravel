<script>
import BookablesListItem from "./BookableListItem.vue";

export default {
    components: {
        BookablesListItem,
    },
    data() {
        return {
            bookables: null,
            isLoading: false,
            columns: 3,
        };
    },
    computed: {
        rows() {
            return this.bookables === null
                ? 0
                : Math.ceil(this.bookables.length / this.columns);
        },
    },
    methods: {
        bookablesInRow(row) {
            return this.bookables.slice(
                (row - 1) * this.columns,
                row * this.columns
            );
        },
        placeholderInRow(row) {
            return this.columns - this.bookablesInRow(row).length;
        },
    },
    created() {
        this.isLoading = true;

        const request = axios.get("/api/bookables").then((response) => {
            this.bookables = response.data.data;
            this.isLoading = false;
        });
    },
};
</script>

<template>
    <div>
        Rows is: {{ rows }}
        <div v-if="isLoading">Loading ...</div>
        <div v-else>
            <div class="row mb-4" v-for="row in rows" :key="'row' + row">
                <div
                    class="col d-flex align-items-stretch"
                    v-for="(bookable, column) in bookablesInRow(row)"
                    :key="'row' + row + column"
                >
                    <bookables-list-item
                        v-bind="bookable"
                    ></bookables-list-item>
                </div>

                <div
                    class="col"
                    v-for="p in placeholderInRow(row)"
                    :key="'placeholder' + row + p"
                ></div>
            </div>
        </div>
    </div>
</template>
