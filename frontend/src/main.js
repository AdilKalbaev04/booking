import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import Toast, { POSITION } from "vue-toastification";
import "vue-toastification/dist/index.css";
import { createVuetify } from "vuetify";  // Импортировать создание Vuetify
import "vuetify/styles";  // Импортировать стили Vuetify

const vuetify = createVuetify();  // Создание экземпляра Vuetify

const app = createApp(App);
app.use(vuetify);  // Использование Vuetify
app.use(router);
app.use(Toast, {
  position: POSITION.TOP_RIGHT,
  timeout: 3000,
});

app.mount("#app");
