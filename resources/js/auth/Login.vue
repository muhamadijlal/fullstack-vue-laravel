<script>
import validationErrors from "../shared/mixins/validationErrors";
import { logIn } from "../shared/utils/Auth";

export default {
    mixins: [validationErrors],
    data() {
        return {
            email: null,
            password: null,
            isLoading: false,
        };
    },
    methods: {
        async login() {
            this.isLoading = true;
            this.errors = null;

            try {
                await axios.get("/sanctum/csrf-cookie");
                await axios.post("/login", {
                    email: this.email,
                    password: this.password,
                });

                logIn();
                this.$store.loadUser();
                this.$router.push({ name: "home" });
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
                    <label for="email">Email</label>
                    <input
                        type="text"
                        name="email"
                        placeholder="Enter your email"
                        class="form-control"
                        v-model="email"
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
                        v-model="password"
                        :class="[{ 'is-invalid': errorFor('password') }]"
                    />
                    <v-errors :errors="errorFor('password')"></v-errors>
                </div>

                <div class="d-grid mt-5">
                    <button
                        class="btn btn-primary"
                        :disabled="isLoading"
                        @click.prevent="login"
                    >
                        Login
                    </button>
                </div>
                <hr />
                <div class="text-nowrap">
                    No account yet?
                    <router-link :to="{ name: 'register' }" class="fw-bold"
                        >Register</router-link
                    >
                </div>

                <div class="text-nowrap">
                    Forgotten pasword?
                    <router-link :to="{ name: 'home' }" class="fw-bold"
                        >Reset password</router-link
                    >
                </div>
            </form>
        </div>
    </div>
</template>
