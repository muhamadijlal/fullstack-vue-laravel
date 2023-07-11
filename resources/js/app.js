import "./bootstrap";
import moment from "moment";
import router from "./routes";
import { createApp } from "vue";
import Index from "./Index.vue";
import { createPinia } from "pinia";
import { useSearchStore } from "./store/store";

// Components
import FatalError from "./shared/components/FatalError.vue";
import StarRating from "./shared/components/StarRating.vue";
import ValidationErrors from "./shared/components/ValidationErrors.vue";
import Success from "./shared/components/Success.vue";

// FontAwesome
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";
import "font-awesome-animation/css/font-awesome-animation.min.css";

library.add(fas, far);

const pinia = createPinia();
const app = createApp({
    components: { Index },
    async beforeCreate() {
        this.$store.loadStoredState();
        this.$store.loadUser();
    },
});
app.component("font-awesome-icon", FontAwesomeIcon)
    .component("star-rating", StarRating)
    .component("fatal-error", FatalError)
    .component("v-errors", ValidationErrors)
    .component("success", Success);

app.use(router);
app.use(pinia);

// GLobal properties
app.config.globalProperties.$store = useSearchStore();

app.config.globalProperties.$filters = {
    fromNow(date) {
        return moment(date).fromNow();
    },
};

window.axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (401 === error.response.status) {
            this.$store.logout;
        }

        return Promise.reject(error);
    }
);

app.mount("#app");
