<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white rounded-lg shadow p-8">
            <h1 class="text-2xl font-bold text-center mb-6 text-indigo-600">
                Login
            </h1>

            <form @submit.prevent="submeterFormularioLogin" class="space-y-4">
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700"
                    >
                        E-mail
                    </label>
                    <input
                        id="email"
                        v-model="email"
                        type="email"
                        autocomplete="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        required
                    />
                    <p v-if="erros.email" class="text-xs text-red-500 mt-1">
                        {{ erros.email }}
                    </p>
                </div>

                <div>
                    <label
                        for="senha"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Senha
                    </label>
                    <input
                        id="senha"
                        v-model="senha"
                        type="password"
                        autocomplete="current-password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        required
                    />
                    <p v-if="erros.senha" class="text-xs text-red-500 mt-1">
                        {{ erros.senha }}
                    </p>
                </div>

                <p v-if="erroGeral" class="text-sm text-red-600">
                    {{ erroGeral }}
                </p>

                <button
                    type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    :disabled="estaEnviando"
                >
                    <span v-if="!estaEnviando">Entrar</span>
                    <span v-else>Entrando...</span>
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import apiCliente, { definirTokenAutenticacao } from "../servicos/apiCliente";

const roteador = useRouter();

const email = ref("");
const senha = ref("");
const estaEnviando = ref(false);
const erroGeral = ref("");
const erros = ref({});

async function submeterFormularioLogin() {
    estaEnviando.value = true;
    erroGeral.value = "";
    erros.value = {};

    try {
        const resposta = await apiCliente.post("/login", {
            email: email.value,
            senha: senha.value,
        });

        const token = resposta.data.token;
        definirTokenAutenticacao(token);

        roteador.push({ name: "pessoas.listagem" });
    } catch (erro) {
        if (erro.response?.status === 422) {
            const dadosErros = erro.response.data.errors || {};
            erros.value = Object.fromEntries(
                Object.entries(dadosErros).map(([campo, mensagens]) => [
                    campo,
                    mensagens[0],
                ])
            );
            erroGeral.value = "Verifique os campos informados.";
        } else {
            erroGeral.value =
                "Não foi possível realizar o login. Verifique suas credenciais.";
        }
        console.error("Erro no login", erro);
    } finally {
        estaEnviando.value = false;
    }
}
</script>
