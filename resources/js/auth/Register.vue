<script>
import validationErrors from "../shared/mixins/validationErrors";
import { logIn } from "../shared/utils/Auth";

export default {
    mixins: [validationErrors],
    data() {
        return {
            user: {
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
            },
            isLoading: false,
        };
    },
    methods: {
        async register() {
            this.isLoading = true;
            this.errors = null;

            try {
                const response = await axios.post("/register", this.user);

                if (201 === response.status) {
                    logIn();
                    this.$store.loadUser();
                    this.$router.push({ name: "home" });
                }
            } catch (error) {
                this.errors =
                    error.response?.data.errors ?? error.response?.data.message;
            }

            this.isLoading = false;
        },
    },
};
</script>

<template>
    <div class="w-50 m-auto">
        <div class="card card-body">
            <form>
                <div class="mb-3">
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input
                            type="text"
                            name="name"
                            placeholder="How people call you ?"
                            class="form-control"
                            v-model="user.name"
                            :class="[{ 'is-invalid': errorFor('name') }]"
                        />
                        <v-errors :errors="errorFor('name')"></v-errors>
                    </div>

                    <label for="email">Email</label>
                    <input
                        type="text"
                        name="email"
                        placeholder="Enter your email"
                        class="form-control"
                        v-model="user.email"
                        :class="[{ 'is-invalid': errorFor('email') }]"
                    />
                    <v-errors :errors="errorFor('email')"></v-errors>
                </div>

                <div class="mb-3">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        class="form-control"
                        v-model="user.password"
                        :class="[{ 'is-invalid': errorFor('password') }]"
                    />
                    <v-errors :errors="errorFor('password')"></v-errors>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation"
                        >Re-type you're password</label
                    >
                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Enter your password"
                        class="form-control"
                        v-model="user.password_confirmation"
                        :class="[
                            { 'is-invalid': errorFor('password_confirmation') },
                        ]"
                    />
                    <v-errors
                        :errors="errorFor('password_confirmation')"
                    ></v-errors>
                </div>

                <div class="d-grid mt-5">
                    <button
                        class="btn btn-primary"
                        :disabled="isLoading"
                        @click.prevent="register"
                    >
                        Register
                    </button>
                </div>
                <hr />
                <div class="text-nowrap">
                    Already have an acount ?
                    <router-link :to="{ name: 'login' }" class="fw-bold"
                        >Login</router-link
                    >
                </div>
            </form>
        </div>
    </div>
</template>
