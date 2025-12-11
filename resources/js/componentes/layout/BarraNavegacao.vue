<template>
    <nav class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-xl font-bold text-indigo-600"
                        >Cadastro de Pessoas</span
                    >
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 text-sm" v-if="nomeUsuario">
                        Olá, {{ nomeUsuario }}
                    </span>

                    <button
                        type="button"
                        class="px-3 py-1 rounded-md text-sm font-medium bg-red-500 text-white hover:bg-red-600"
                        @click="sair"
                    >
                        Sair
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import apiCliente, {
    removerTokenAutenticacao,
} from "../../servicos/apiCliente";

const roteador = useRouter();
const usuario = ref(null);

const nomeUsuario = computed(
    () => usuario.value?.name ?? usuario.value?.email ?? ""
);

async function carregarUsuarioAutenticado() {
    try {
        const resposta = await apiCliente.get("/usuario-autenticado");
        usuario.value = resposta.data;
    } catch (erro) {
        // se der erro (ex: 401) só ignora aqui
        console.error("Erro ao carregar usuário autenticado", erro);
    }
}

async function sair() {
    try {
        await apiCliente.post("/logout");
    } catch (erro) {
        console.error("Erro ao realizar logout", erro);
    } finally {
        removerTokenAutenticacao();
        roteador.push({ name: "login" });
    }
}

onMounted(() => {
    carregarUsuarioAutenticado();
});
</script>
