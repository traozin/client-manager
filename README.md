# 📋 Client Manager

Sistema completo de gerenciamento de clientes com painel administrativo, CRUD via API RESTful, e interface web com Blade e Bootstrap. Projeto pronto para rodar com Docker e configuração automatizada via `entrypoint.sh`.

---

## 🚀 Tecnologias Utilizadas

### Backend

* **PHP 8.2**
* **Laravel 11**
* **Composer**

### Frontend

* **Blade + Bootstrap 5**
* **TailwindCSS** (suporte adicional)
* Consumo de dados via **API RESTful**

### Banco de Dados

* **MySQL 8.0** via Docker

### DevOps / Ambiente

* **Docker**
* **Docker Compose**
* **Script `entrypoint.sh` para provisionamento automático**

---

## 🥪 Como Rodar o Projeto

### ⭮️ Pré-requisitos

* [Docker](https://www.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/)

---

### ⚙️ Clonando o Repositório

```bash
git clone https://github.com/seu-usuario/client-manager.git
cd client-manager
```

---

### 🐳 Subindo o Ambiente com Docker

O ambiente está **totalmente automatizado** via `entrypoint.sh`. Ao subir os containers, os seguintes passos ocorrerão automaticamente:

* Criação do arquivo `.env` a partir do `.env.example`
* Aguardar o banco de dados estar pronto
* Instalar as dependências do Composer
* Gerar a chave da aplicação
* Rodar as migrations e seeders
* Iniciar o servidor Laravel na porta `8000`

#### ✅ Comando único:

```bash
docker-compose up -d --build
```

Acesse no navegador:

```
http://localhost:8000
```

---

## 🔌 Endpoints da API

A API está disponível sob o prefixo `/api/v1`.

### ▶️ Lista de Rotas

| Método | Endpoint                               | Descrição                          |
| ------ | -------------------------------------- | ---------------------------------- |
| GET    | `/api/v1/ping`                         | Verifica se a API está ativa       |
| GET    | `/api/v1/cidades`                      | Lista todas as cidades             |
| GET    | `/api/v1/cidades/{id}`                 | Exibe detalhes de uma cidade       |
| GET    | `/api/v1/cidades/{id}/representantes`  | Lista representantes de uma cidade |
| POST   | `/api/v1/cidades`                      | Cria uma nova cidade               |
| PUT    | `/api/v1/cidades/{id}`                 | Atualiza dados da cidade           |
| DELETE | `/api/v1/cidades/{id}`                 | Remove uma cidade                  |
| GET    | `/api/v1/clientes`                     | Lista todos os clientes            |
| GET    | `/api/v1/clientes/{id}`                | Detalhes de um cliente             |
| GET    | `/api/v1/clientes/{id}/representantes` | Lista representantes de um cliente |
| POST   | `/api/v1/clientes`                     | Cria um novo cliente               |
| PUT    | `/api/v1/clientes/{id}`                | Atualiza dados do cliente          |
| DELETE | `/api/v1/clientes/{id}`                | Remove um cliente                  |

> Todos os endpoints retornam dados em JSON. A autenticação não é exigida por padrão.

---

## 🌐 Rotas Web

A interface web consome os dados da API RESTful e é renderizada com Blade.

| Caminho           | Controller                     | Descrição              |
| ----------------- | ------------------------------ | ---------------------- |
| `/`               | `ViewClienteController@index`  | Lista de clientes      |
| `/clientes/criar` | `ViewClienteController@create` | Formulário de cadastro |

---

## 🧼 Scripts SQL

Disponíveis no diretório `/scripts`:

* `representantes_por_cliente.sql`
* `representantes_por_cidade.sql`
* `estrutura_banco.sql` – DDL completo do banco

---