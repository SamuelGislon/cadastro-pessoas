<template>
    <LayoutAutenticado>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                {{ estaEditando ? "Editar Pessoa" : "Nova Pessoa" }}
            </h1>
        </div>

        <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
            <form class="space-y-4" @submit.prevent="submeterFormularioPessoa">
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nome
                    </label>
                    <input
                        v-model="formulario.nome"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    />
                    <p v-if="erros.nome" class="text-xs text-red-500 mt-1">
                        {{ erros.nome }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Tipo
                        </label>
                        <select
                            v-model="formulario.tipo"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                            <option value="">Selecione</option>
                            <option value="fisica">Pessoa Física</option>
                            <option value="juridica">Pessoa Jurídica</option>
                        </select>
                        <p v-if="erros.tipo" class="text-xs text-red-500 mt-1">
                            {{ erros.tipo }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            CPF/CNPJ
                        </label>
                        <input
                            v-model="formulario.cpf_cnpj"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        />
                        <p
                            v-if="erros.cpf_cnpj"
                            class="text-xs text-red-500 mt-1"
                        >
                            {{ erros.cpf_cnpj }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Telefone
                        </label>
                        <input
                            v-model="formulario.telefone"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="(xx) xxxxx-xxxx"
                        />
                        <p
                            v-if="erros.telefone"
                            class="text-xs text-red-500 mt-1"
                        >
                            {{ erros.telefone }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            E-mail
                        </label>
                        <input
                            v-model="formulario.email"
                            type="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        />
                        <p v-if="erros.email" class="text-xs text-red-500 mt-1">
                            {{ erros.email }}
                        </p>
                    </div>
                </div>

                <p v-if="erroGeral" class="text-sm text-red-600">
                    {{ erroGeral }}
                </p>

                <div class="flex justify-between mt-6">
                    <button
                        type="button"
                        class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100"
                        @click="voltarParaListagem"
                    >
                        Voltar
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50"
                        :disabled="estaSalvando"
                    >
                        <span v-if="!estaSalvando">
                            {{
                                estaEditando ? "Salvar alterações" : "Cadastrar"
                            }}
                        </span>
                        <span v-else> Salvando... </span>
                    </button>
                </div>
            </form>
        </div>
    </LayoutAutenticado>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from "vue";
import { useRouter } from "vue-router";
import LayoutAutenticado from "../../componentes/layout/LayoutAutenticado.vue";
import apiCliente from "../../servicos/apiCliente";

const propriedades = defineProps({
    id: {
        type: [String, Number],
        required: false,
    },
});

const roteador = useRouter();

const formulario = reactive({
    nome: "",
    tipo: "",
    cpf_cnpj: "",
    telefone: "",
    email: "",
});

const erros = ref({});
const erroGeral = ref("");
const estaSalvando = ref(false);

const estaEditando = computed(() => !!propriedades.id);

async function carregarPessoa() {
    try {
        const resposta = await apiCliente.get(`/pessoas/${propriedades.id}`);
        const dados = resposta.data;

        formulario.nome = dados.nome;
        formulario.tipo = dados.tipo;
        formulario.cpf_cnpj = dados.cpf_cnpj;
        formulario.telefone = dados.telefone;
        formulario.email = dados.email;
    } catch (erro) {
        console.error("Erro ao carregar pessoa", erro);
        erroGeral.value = "Não foi possível carregar os dados da pessoa.";
    }
}

async function submeterFormularioPessoa() {
    estaSalvando.value = true;
    erroGeral.value = "";
    erros.value = {};

    try {
        const dadosEnvio = { ...formulario };

        if (estaEditando.value) {
            await apiCliente.put(`/pessoas/${propriedades.id}`, dadosEnvio);
        } else {
            await apiCliente.post("/pessoas", dadosEnvio);
        }

        voltarParaListagem();
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
            erroGeral.value = "Não foi possível salvar os dados da pessoa.";
        }

        console.error("Erro ao salvar pessoa", erro);
    } finally {
        estaSalvando.value = false;
    }
}

function voltarParaListagem() {
    roteador.push({ name: "pessoas.listagem" });
}

onMounted(() => {
    if (estaEditando.value) {
        carregarPessoa();
    }
});
</script>
