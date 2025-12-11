<template>
    <LayoutAutenticado>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Pessoas</h1>

            <button
                type="button"
                class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700"
                @click="irParaCriarPessoa"
            >
                Nova Pessoa
            </button>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form
                class="grid grid-cols-1 md:grid-cols-4 gap-4"
                @submit.prevent="aplicarFiltros"
            >
                <div>
                    <label class="block text-xs font-medium text-gray-700"
                        >Nome</label
                    >
                    <input
                        v-model="filtros.nome"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Filtrar por nome"
                    />
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700"
                        >Tipo</label
                    >
                    <select
                        v-model="filtros.tipo"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Todos</option>
                        <option value="fisica">Física</option>
                        <option value="juridica">Jurídica</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700"
                        >CPF/CNPJ</label
                    >
                    <input
                        v-model="filtros.cpf_cnpj"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Filtrar por CPF/CNPJ"
                    />
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700"
                        >E-mail</label
                    >
                    <input
                        v-model="filtros.email"
                        type="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Filtrar por e-mail"
                    />
                </div>

                <div class="md:col-span-4 flex justify-end space-x-2 mt-2">
                    <button
                        type="button"
                        class="px-3 py-1 rounded-md text-sm border border-gray-300 text-gray-700 hover:bg-gray-100"
                        @click="limparFiltros"
                    >
                        Limpar
                    </button>

                    <button
                        type="submit"
                        class="px-3 py-1 rounded-md text-sm bg-indigo-600 text-white hover:bg-indigo-700"
                    >
                        Aplicar filtros
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabela -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"
                        >
                            Nome
                        </th>
                        <th
                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"
                        >
                            CPF/CNPJ
                        </th>
                        <th
                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"
                        >
                            Tipo
                        </th>
                        <th
                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"
                        >
                            Telefone
                        </th>
                        <th
                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"
                        >
                            E-mail
                        </th>
                        <th
                            class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase"
                        >
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="pessoas.length === 0">
                        <td
                            colspan="6"
                            class="px-4 py-4 text-center text-sm text-gray-500"
                        >
                            Nenhuma pessoa encontrada.
                        </td>
                    </tr>

                    <tr v-for="pessoa in pessoas" :key="pessoa.id">
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ pessoa.nome }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ pessoa.cpf_cnpj }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{
                                pessoa.tipo === "fisica" ? "Física" : "Jurídica"
                            }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ pessoa.telefone || "-" }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-900">
                            {{ pessoa.email }}
                        </td>
                        <td class="px-4 py-2 text-sm text-right space-x-2">
                            <button
                                type="button"
                                class="px-2 py-1 rounded-md text-xs bg-blue-500 text-white hover:bg-blue-600"
                                @click="irParaEditarPessoa(pessoa.id)"
                            >
                                Editar
                            </button>
                            <button
                                type="button"
                                class="px-2 py-1 rounded-md text-xs bg-red-500 text-white hover:bg-red-600"
                                @click="confirmarExclusao(pessoa.id)"
                            >
                                Excluir
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div
            v-if="totalPaginas > 1"
            class="flex justify-between items-center mt-4 text-sm text-gray-700"
        >
            <span> Página {{ paginaAtual }} de {{ totalPaginas }} </span>

            <div class="space-x-2">
                <button
                    type="button"
                    class="px-3 py-1 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 disabled:opacity-50"
                    :disabled="paginaAtual <= 1 || estaCarregando"
                    @click="alterarPagina(paginaAtual - 1)"
                >
                    Anterior
                </button>
                <button
                    type="button"
                    class="px-3 py-1 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 disabled:opacity-50"
                    :disabled="paginaAtual >= totalPaginas || estaCarregando"
                    @click="alterarPagina(paginaAtual + 1)"
                >
                    Próxima
                </button>
            </div>
        </div>
    </LayoutAutenticado>
</template>

<script setup>
import { onMounted, reactive, ref } from "vue";
import { useRouter } from "vue-router";
import LayoutAutenticado from "../../componentes/layout/LayoutAutenticado.vue";
import apiCliente from "../../servicos/apiCliente";

const roteador = useRouter();

const pessoas = ref([]);
const paginaAtual = ref(1);
const totalPaginas = ref(1);
const estaCarregando = ref(false);
const filtros = reactive({
    nome: "",
    tipo: "",
    cpf_cnpj: "",
    email: "",
});

async function carregarPessoas(pagina = 1) {
    estaCarregando.value = true;

    try {
        const parametros = {
            page: pagina,
            ...filtros,
        };

        const resposta = await apiCliente.get("/pessoas", {
            params: parametros,
        });

        pessoas.value = resposta.data.data || [];
        paginaAtual.value = resposta.data.current_page;
        totalPaginas.value = resposta.data.last_page;
    } catch (erro) {
        console.error("Erro ao carregar pessoas", erro);
        if (erro.response?.status === 401) {
            roteador.push({ name: "login" });
        }
    } finally {
        estaCarregando.value = false;
    }
}

function aplicarFiltros() {
    carregarPessoas(1);
}

function limparFiltros() {
    filtros.nome = "";
    filtros.tipo = "";
    filtros.cpf_cnpj = "";
    filtros.email = "";
    carregarPessoas(1);
}

function alterarPagina(novaPagina) {
    if (novaPagina < 1 || novaPagina > totalPaginas.value) {
        return;
    }
    carregarPessoas(novaPagina);
}

function irParaCriarPessoa() {
    roteador.push({ name: "pessoas.nova" });
}

function irParaEditarPessoa(idPessoa) {
    roteador.push({ name: "pessoas.editar", params: { id: idPessoa } });
}

async function confirmarExclusao(idPessoa) {
    const confirmar = window.confirm("Deseja realmente excluir esta pessoa?");

    if (!confirmar) {
        return;
    }

    try {
        await apiCliente.delete(`/pessoas/${idPessoa}`);
        carregarPessoas(paginaAtual.value);
    } catch (erro) {
        console.error("Erro ao excluir pessoa", erro);
        alert("Não foi possível excluir a pessoa.");
    }
}

onMounted(() => {
    carregarPessoas();
});
</script>
