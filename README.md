# Cadastro de Pessoas – Laravel + Vue.js

Sistema web para **cadastro, listagem, edição e exclusão de pessoas**, com autenticação de usuário (login) e controle de acesso.  
Projeto desenvolvido em **Laravel (API)** + **Vue 3 (SPA)** + **Tailwind CSS**, utilizando **PostgreSQL** como banco de dados.

---

## 🧩 Objetivo do Projeto

O sistema permite que um usuário autenticado:

-   Faça **login** com e-mail e senha;
-   Acesse uma tela de **listagem de pessoas**;
-   **Cadastre** novas pessoas;
-   **Altere** pessoas existentes;
-   **Exclua** pessoas;
-   Utilize filtros básicos na listagem (nome, tipo, CPF/CNPJ, e-mail).

Cada pessoa possui os campos:

-   `id`
-   `nome`
-   `cpf_cnpj` (CPF ou CNPJ)
-   `tipo` (`fisica` ou `juridica`)
-   `telefone`
-   `email`

---

## 🛠 Tecnologias Utilizadas

### Backend

-   PHP
-   Laravel
-   Laravel Sanctum
-   **Arquitetura**:
    -   MVC
    -   DAO (`app/DAO`)
    -   Serviços (`app/Servicos`)

### Frontend

-   Vue.js 3
-   Vue Router
-   Vite
-   Tailwind CSS 4
-   Axios

### Banco de Dados

-   PostgreSQL

---

## 💻 Programas e ferramentas recomendadas

### Linguagens / runtimes

-   **PHP 8.5 ou superior**
-   **Node.js 18+ ou 20+**
-   **PostgreSQL 13+**

### Gerenciadores / servidores

-   **Composer**
-   **npm**

### Editor de código

**Visual Studio Code** com as seguintes extensões:

#### Laravel / PHP

-   `PHP Intelephense`
-   `Laravel Extra Intellisense`
-   `Laravel Blade Snippets`
-   `Laravel Artisan`

#### JavaScript / Vue

-   `Vue (Official)`
-   `ESLint`
-   `Prettier - Code formatter`

#### Tailwind / CSS

-   `Tailwind CSS IntelliSense`

#### Git / Banco de Dados

-   `GitLens`
-   `SQLTools`
-   `SQLTools PostgreSQL/Cockroach Driver`
-   `PostgreSQL`

---

## 📁 Estrutura geral do projeto

Principais pastas usadas no projeto:

```text
app/
  Models/
    Pessoa.php
    User.php

  DAO/
    PessoaDAO.php

  Servicos/
    AutenticacaoServico.php
    PessoaServico.php

  Http/
    Controllers/
      Api/
        AutenticacaoControlador.php
        PessoaControlador.php
    Requests/
      Pessoa/
        PessoaCriarRequisicao.php
        PessoaAtualizarRequisicao.php

database/
  migrations/
    0001_01_01_000000_create_users_table.php
    2025_12_11_162241_criar_tabela_pessoas.php
    (migrations do Sanctum)

resources/
  views/
    app.blade.php          # View raiz da SPA

  js/
    app.js                 # Boot do Vue
    App.vue
    rotas/
      index.js             # Vue Router
    servicos/
      apiCliente.js        # Cliente Axios + token
    componentes/
      layout/
        BarraNavegacao.vue
        LayoutAutenticado.vue
    paginas/
      Login.vue
      pessoas/
        ListagemPessoas.vue
        FormularioPessoa.vue

routes/
  api.php                  # Rotas da API (login, logout, pessoas)
  web.php                  # Rota catch-all para a SPA (Vue)
```

---

## ⚙️ Configurando o ambiente local (passo a passo)

### 1. Clonar o repositório

```bash
git clone https://github.com/SEU_USUARIO/cadastro-pessoas.git
cd cadastro-pessoas
```

---

### 2. Instalar dependências PHP (Laravel)

Na raiz do projeto:

```bash
composer install
```

Se der erro de versão de PHP, confira se está usando PHP 8.5+.

---

### 3. Configurar o arquivo `.env`

Crie o arquivo `.env` a partir do exemplo:

**Windows (PowerShell):**

```powershell
copy .env.example .env
```

Depois, gere a chave da aplicação:

```bash
php artisan key:generate
```

---

### 4. Configurar o banco PostgreSQL

1. Acesse seu PostgreSQL (pgAdmin, DBeaver, psql, etc.)

2. Crie o banco de dados:

    ```sql
    CREATE DATABASE cadastro_pessoas;
    ```

3. Edite o arquivo `.env` e ajuste os campos:

    ```env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=cadastro_pessoas
    DB_USERNAME=seu_usuario
    DB_PASSWORD=sua_senha
    ```

    - `DB_USERNAME` → usuário do PostgreSQL (ex.: `postgres`)
    - `DB_PASSWORD` → senha do PostgreSQL

---

### 5. Migrations (criar tabelas no banco)

Ainda na raiz do projeto:

```bash
php artisan migrate
```

Isso vai criar:

-   Tabela `users`
-   Tabela `pessoas`
-   Tabelas do Sanctum (`personal_access_tokens`, etc.)

Se der erro de conexão, revise a configuração do `.env` (host, porta, usuário, senha e nome do banco).

---

### 6. Criar um usuário para login

O sistema de login utiliza a tabela `users`.
Vamos criar um usuário padrão via **Tinker**.

```bash
php artisan tinker
```

No prompt do Tinker, rode:

```php
use App\Models\User;

User::create([
    'name' => 'Administrador',
    'email' => 'admin@teste.com',
    'password' => bcrypt('senha123'),
]);
```

Depois, saia do Tinker:

```php
exit
```

Credenciais padrão de login:

-   **E-mail:** `admin@teste.com`
-   **Senha:** `senha123`

Você pode criar outros usuários da mesma forma se quiser.

---

### 7. Instalar dependências do Frontend (Vue + Vite + Tailwind)

Na raiz do projeto:

```bash
npm install
```

Isso instala:

-   Vite
-   Vue 3
-   Tailwind CSS 4
-   Axios
-   Plugin Laravel Vite

---

### 8. Subir o backend (Laravel)

Em um terminal:

```bash
php artisan serve
```

Por padrão, a aplicação Laravel fica disponível em:

```text
http://127.0.0.1:8000
```

---

### 9. Subir o frontend (Vite)

Em outro terminal (também na raiz do projeto):

```bash
npm run dev
```

O Vite vai cuidar de compilar o JS/CSS e injetar no Laravel.

---

### 10. Acessar a aplicação

Abra o navegador em:

```text
http://127.0.0.1:8000
```

A SPA do Vue vai carregar e o router vai redirecionar para a tela de login.

Use as credenciais criadas:

-   **E-mail:** `admin@teste.com`
-   **Senha:** `senha123`

Se o login for bem-sucedido, você será redirecionado para a tela de **Listagem de Pessoas**.

---

## 🔐 Fluxo de autenticação

-   Login: `POST /api/login`

    -   Corpo: `{ "email": "...", "senha": "..." }`
    -   Resposta (sucesso): `{ "mensagem": "...", "token": "..." }`
    -   O token é salvo no `localStorage` e enviado em todas as requisições seguintes como `Authorization: Bearer <token>`.

-   Logout: `POST /api/logout`

    -   Revoga o token atual do usuário.

-   Usuário autenticado: `GET /api/usuario-autenticado`

As rotas de pessoas são protegidas pelo middleware `auth:sanctum`:

-   `GET /api/pessoas`
-   `POST /api/pessoas`
-   `GET /api/pessoas/{id}`
-   `PUT /api/pessoas/{id}`
-   `DELETE /api/pessoas/{id}`

---

## 🧪 Problemas comuns e soluções rápidas

**1. Erro de conexão com banco (migrate falha)**
➡ Verifique o `.env`:

-   Nome do banco (`DB_DATABASE`)
-   Usuário (`DB_USERNAME`)
-   Senha (`DB_PASSWORD`)
-   Se o PostgreSQL está rodando na porta certa (`DB_PORT`)

---

**2. Erro ao acessar (tela branca / erro 500)**
➡ Rode:

```bash
php artisan optimize:clear
```

E tente acessar novamente.

---

**3. Alterei algo em `composer.json` e deu problema**
➡ Rode:

```bash
composer install
composer dump-autoload
php artisan optimize:clear
```

---

**4. Alterei algo em `package.json` e o Vite quebrou**
➡ Rode:

```bash
npm install
npm run dev
```

---

## 📌 Observações finais

-   A arquitetura utiliza **MVC + DAO + Serviços**, separando bem a camada de acesso a dados, regras de negócio e controladores HTTP.
-   O frontend é uma **SPA** com Vue 3, com rotas para:

    -   `/login`
    -   `/pessoas`
    -   `/pessoas/nova`
    -   `/pessoas/:id/editar`
