<script>
import { is422 } from "../shared/utils/response";
import validationErrors from "../shared/mixins/validationErrors";

export default {
    mixins: [validationErrors],
    props: {
        bookableId: String,
    },
    emits: ["availability"],
    data() {
        return {
            isLoading: false,
            status: null,
            lastSearch: this.$store.lastSearch,
        };
    },
    methods: {
        async check() {
            this.isLoading = true;
            this.errors = null;

            this.$store.setLastSearch(this.lastSearch);

            try {
                this.status = (
                    await axios.get(
                        `/api/bookables/${this.bookableId}/availability?from=${this.lastSearch.from}&to=${this.lastSearch.to}`
                    )
                ).status;

                this.$emit("availability", this.hasAvailability);
            } catch (err) {
                if (is422(err)) {
                    this.errors = err.response.data.errors;
                }
                this.status = err.response.status;
                this.$emit("availability", this.hasAvailability);
            }

            this.isLoading = false;
        },
    },
    computed: {
        hasErrors() {
            return 422 === this.status && this.errors !== null;
        },
        hasAvailability() {
            return 200 === this.status;
        },
        noAvailability() {
            return 404 === this.status;
        },
    },
};
</script>

<template>
    <div>
        <h6 class="text-uppercase text-secondary font-weight-bolder">
            Check Availability
            <transition-group>
                <span v-if="noAvailability" class="text-danger fw-bold"
                    >(NO AVAILABLE)</span
                >
                <span v-if="hasAvailability" class="text-success fw-bold"
                    >(AVAILABLE)</span
                >
            </transition-group>
        </h6>

        <div class="row">
            <div class="form-group col-md-6">
                <label for="from">From</label>
                <input
                    type="text"
                    name="from"
                    id="from"
                    class="form-control form-control-sm"
                    placeholder="Start date"
                    v-model="lastSearch.from"
                    @keyup.enter="check"
                    :class="[{ 'is-invalid': errorFor('from') }]"
                />
                <v-errors :errors="errorFor('from')"></v-errors>
            </div>

            <div class="form-group col-md-6">
                <label for="to">To</label>
                <input
                    type="text"
                    name="to"
                    id="to"
                    class="form-control form-control-sm"
                    placeholder="End date"
                    v-model="lastSearch.to"
                    @keyup.enter="check"
                    :class="[{ 'is-invalid': errorFor('to') }]"
                />
                <v-errors :errors="errorFor('to')"></v-errors>
            </div>
        </div>

        <div class="d-grid mt-3">
            <button
                class="btn btn-secondary"
                @click.enter="check"
                type="button"
                :disabled="isLoading"
            >
                <span v-if="!isLoading">Check</span>
                <span v-if="isLoading">
                    <font-awesome-icon
                        :icon="['fass', 'spinner']"
                        class="fa-spin animated"
                    />
                    Checking...
                </span>
            </button>
        </div>
    </div>
</template>

<style scoped>
label {
    font-size: 0.7rem;
    text-transform: uppercase;
    color: gray;
    font-weight: bolder;
}

.is-invalid {
    border-color: #b22222;
    background-image: none;
}

.invalid-feedback {
    color: #b22222;
}
</style>
