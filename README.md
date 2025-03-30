### Seplag API

Uma API REST desenvolvida para gerenciar os dados da SEPLAG, conforme o diagrama de banco de dados e as orientações do edital. 
A solução implementa um CRUD para Servidor Efetivo, Servidor Temporário, Unidade e Lotação, além de endpoints específicos para consultas e upload de fotografias utilizando Min.IO.

---

## Sumário

- [Sumário](#sumário)
- [Visão Geral](#visão-geral)
- [Requisitos Específicos](#requisitos-específicos)
- [Requisitos e Dependências](#requisitos-e-dependências)
- [Instalação](#instalação)
  - [Com Docker Compose](#com-docker-compose)
  - [Sem Docker](#sem-docker)
- [Uso](#uso)
- [Endpoints](#endpoints)
  - [Autenticação](#autenticação)
  - [Servidores Efetivos e Temporários](#servidores-efetivos-e-temporários)
  - [Unidades](#unidades)
  - [Lotação](#lotação)
  - [Consultas Específicas](#consultas-específicas)
  - [Upload de Fotografias](#upload-de-fotografias)
- [Exemplos de Erros](#exemplos-de-erros)
- [Contribuição](#contribuição)
- [Licença](#licença)
- [Dados do Desenvolvedor](#dados-do-desenvolvedor)

---

## Visão Geral

A Seplag API foi desenvolvida utilizando PHP (Laravel) para gerenciar operações de CRUD e consultas referentes a:

- **Servidor Efetivo**
- **Servidor Temporário**
- **Unidade**
- **Lotação**

Além disso, foram implementados endpoints para:

- Consultar servidores efetivos lotados em determinada unidade.
- Consultar o endereço funcional a partir do nome do servidor.
- Realizar upload de fotografias para o Min.IO, gerando links temporários de acesso.

---

## Requisitos Específicos

- **CRUD Completo** para todas as entidades mencionadas.
- **Endpoints de consulta** por unidade e nome.
- **Upload de fotografias** com recuperação via links temporários.
- **Versionamento e Documentação** no GitHub, sem commits após o prazo de entrega.

---

## Requisitos e Dependências

- **PHP:** Versão 8 ou superior.
- **Composer:** Gerenciador de dependências do PHP.
- **Docker & Docker Compose:** Para ambiente isolado.
- **Banco de Dados:** MySQL/PostgreSQL compatível com Laravel.
- **Min.IO Client:** Para armazenamento de imagens.

---

## Instalação

### Com Docker Compose

1. Clone o repositório e acesse o diretório:
   ```bash
   git clone https://github.com/RaphaelRychard/api-seplag.git
   cd api-seplag
   ```
2. Configure o ambiente:
   ```bash
   cp .env.example .env
   ```
3. Ajuste o `.env` e suba os containers:
   ```bash
   docker-compose up --build
   ```

### Sem Docker

1. Clone o repositório e instale as dependências:
   ```bash
   composer install
   ```
2. Configure o ambiente e gere a chave:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Execute as migrations:
   ```bash
   php artisan migrate
   ```
4. Inicie o servidor:
   ```bash
   php artisan serve
   ```

---

## Uso

Para testar os endpoints, utilize Postman ou cURL. Para autenticação, inclua `Authorization: Bearer {token}` nos cabeçalhos.

---

## Endpoints

### Autenticação

- **[POST] /api/register** - Registra um novo usuário.
- **[POST] /api/login** - Autentica e retorna um token JWT.
- **[GET] /api/user** - Retorna dados do usuário autenticado.

### Servidores Efetivos e Temporários

- **[POST] /api/permanent-servants** - Registra um servidor efetivo.
- **[GET] /api/permanent-servants** - Lista todos os servidores efetivos.
- **[GET] /api/permanent-servants/{id}** - Retorna um servidor específico.
- **[PUT] /api/permanent-servants/{id}** - Atualiza um servidor efetivo.

### Unidades

- **[POST] /api/units** - Cria uma unidade.
- **[GET] /api/units** - Lista todas as unidades.
- **[GET] /api/units/{id}** - Retorna uma unidade específica.
- **[PUT] /api/units/{id}** - Atualiza uma unidade.

### Lotação

- **[POST] /api/assignment** - Registra uma nova lotação.
- **[GET] /api/assignment** - Lista todas as lotações.
- **[GET] /api/assignment/{id}** - Retorna uma lotação específica.
- **[PUT] /api/assignment/{id}** - Atualiza uma lotação.

### Consultas Específicas

- **[GET] /api/servers/by-unit** - Retorna servidores por unidade.
- **[GET] /api/address/by-name** - Retorna o endereço funcional.

### Upload de Fotografias

- **[POST] /api/upload** - Envia uma ou mais imagens.

---

## Exemplos de Erros

- **400 – Requisição Inválida**
- **401 – Não Autorizado**
- **404 – Recurso Não Encontrado**
- **500 – Erro Interno do Servidor**

---

## Contribuição

Sugestões e melhorias são bem-vindas! Envie um pull request ou abra uma issue no GitHub.

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

## Dados do Desenvolvedor

- **Nome:** Raphael Rychard
- **GitHub:** [RaphaelRychard](https://github.com/RaphaelRychard)
