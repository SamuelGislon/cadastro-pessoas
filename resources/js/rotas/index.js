import { createRouter, createWebHistory } from "vue-router";
import LoginPagina from "../paginas/Login.vue";
import ListagemPessoasPagina from "../paginas/pessoas/ListagemPessoas.vue";
import FormularioPessoaPagina from "../paginas/pessoas/FormularioPessoa.vue";
import { carregarTokenSeExistir } from "../servicos/apiCliente";

carregarTokenSeExistir();

const rotas = [
    {
        path: "/login",
        name: "login",
        component: LoginPagina,
        meta: { requerAutenticacao: false },
    },
    {
        path: "/",
        redirect: { name: "pessoas.listagem" },
    },
    {
        path: "/pessoas",
        name: "pessoas.listagem",
        component: ListagemPessoasPagina,
        meta: { requerAutenticacao: true },
    },
    {
        path: "/pessoas/nova",
        name: "pessoas.nova",
        component: FormularioPessoaPagina,
        meta: { requerAutenticacao: true },
    },
    {
        path: "/pessoas/:id/editar",
        name: "pessoas.editar",
        component: FormularioPessoaPagina,
        meta: { requerAutenticacao: true },
        props: true,
    },
];

const roteador = createRouter({
    history: createWebHistory(),
    routes: rotas,
});

roteador.beforeEach((rotaDestino, rotaOrigem, proximo) => {
    const token = localStorage.getItem("token_autenticacao");
    const requerAutenticacao = rotaDestino.meta.requerAutenticacao ?? false;

    if (requerAutenticacao && !token) {
        proximo({ name: "login" });
        return;
    }

    if (rotaDestino.name === "login" && token) {
        proximo({ name: "pessoas.listagem" });
        return;
    }

    proximo();
});

export default roteador;
