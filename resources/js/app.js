import "./bootstrap";
import { createApp } from "vue";
import App from "./App.vue";
import roteador from "./rotas";

const aplicacao = createApp(App);

aplicacao.use(roteador);

aplicacao.mount("#app");
