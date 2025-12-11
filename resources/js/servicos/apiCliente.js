import axios from "axios";

const apiCliente = axios.create({
    baseURL: "/api",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
        Accept: "application/json",
    },
});

export function definirTokenAutenticacao(token) {
    if (token) {
        localStorage.setItem("token_autenticacao", token);
        apiCliente.defaults.headers.common.Authorization = `Bearer ${token}`;
    }
}

export function carregarTokenSeExistir() {
    const token = localStorage.getItem("token_autenticacao");
    if (token) {
        apiCliente.defaults.headers.common.Authorization = `Bearer ${token}`;
    }
}

export function removerTokenAutenticacao() {
    localStorage.removeItem("token_autenticacao");
    delete apiCliente.defaults.headers.common.Authorization;
}

export default apiCliente;
