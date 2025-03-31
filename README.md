# Seplag API

Uma API REST desenvolvida para gerenciar os dados da SEPLAG, conforme o diagrama de banco de dados e as orientações do edital. 
A solução implementa um CRUD para Servidores Efetivos, Servidores Temporários, Unidades e Lotações, além de endpoints específicos para consultas e upload de fotografias utilizando Min.IO.

---

## Sumário

- [Seplag API](#seplag-api)
  - [Sumário](#sumário)
  - [Visão Geral](#visão-geral)
  - [Requisitos](#requisitos)
  - [Instalação](#instalação)
    - [Com Docker Compose](#com-docker-compose)
    - [Sem Docker](#sem-docker)
  - [Uso](#uso)
    - [Autenticação](#autenticação)
    - [Documentação no Postman](#documentação-no-postman)
  - [Endpoints](#endpoints)
    - [Servidores Efetivos e Temporários](#servidores-efetivos-e-temporários)
    - [Unidades](#unidades)
    - [Lotações](#lotações)
  - [Contribuição](#contribuição)
  - [Licença](#licença)
  - [Dados do Desenvolvedor](#dados-do-desenvolvedor)

---

## Visão Geral

A **Seplag API** foi desenvolvida utilizando **PHP (Laravel)** para gerenciar operações de CRUD e consultas referentes a:

- **Servidores Efetivos**
- **Servidores Temporários**
- **Unidades**
- **Lotações**

Além disso, foram implementados endpoints para:

- Consultar servidores efetivos lotados em determinada unidade.
- Consultar o endereço funcional a partir do nome do servidor.
- Realizar upload de fotografias para o **Min.IO**, gerando links temporários de acesso.

---

## Requisitos

- **PHP:** Versão 8.3
- **Laravel:** Versão 12
- **Composer:** Gerenciador de dependências do PHP
- **Docker & Docker Compose:** Para ambiente isolado
- **Banco de Dados:** PostgreSQL
- **Min.IO Client:** Para armazenamento de imagens

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
3. Ajuste as configurações no `.env` e suba os containers:
   ```bash
   docker-compose up -d
   ```

---

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

Para testar os endpoints, utilize **Postman** ou **cURL**. Para autenticação, inclua `Authorization: Bearer {token}` nos cabeçalhos.

### Autenticação

Exemplo de login via `cURL`:
```bash
curl -X POST "http://localhost/api/login" \
     -H "Content-Type: application/json" \
     -d '{"email": "user@example.com", "password": "senha123"}'
```

Sim, é possível incorporar o preview diretamente na documentação, como uma imagem ou um link de visualização do Postman.

Aqui está a versão atualizada da documentação, incluindo o preview de como acessá-la no Postman:

---

### Documentação no Postman

A API está documentada no Postman e pode ser acessada diretamente no link abaixo:

- [Documentação no Postman](https://documenter.getpostman.com/view/32616805/2sB2cPkR88)

**Exemplo de visualização da documentação:**


![Postman Preview](image.png)

---

Com isso, o link da documentação no Postman está bem destacado, e inclui uma visualização para quem preferir ver um preview. Se precisar de mais ajustes ou outro formato, só me avisar! 😄

## Endpoints

### Servidores Efetivos e Temporários

- **[GET] /api/permanent-servants** - Listar servidores efetivos com filtros opcionais:
  - `per_page`: Quantidade de itens por página.
  - `page`: Página atual.
  - `unid_id`: Filtrar por unidade.
  - `nome`: Filtrar por nome.
  
  **Exemplo:**
  ```bash
  {{url}}/api/permanent-servants?per_page=10&page=1&unid_id=1&nome=Kunde
  ```

- **[GET] /api/temporary-servants** - Listar servidores temporários com filtros opcionais:
  - `per_page`: Quantidade de itens por página.
  - `page`: Página atual.
  - `unid_id`: Filtrar por unidade.
  
  **Exemplo:**
  ```bash
  {{url}}/api/temporary-servants?per_page=10&page=1&unid_id=1
  ```

- **[POST] /api/permanent-servants** - Criar um servidor efetivo.
- **[GET] /api/permanent-servants/{id}** - Retornar um servidor específico.
- **[PUT] /api/permanent-servants/{id}** - Atualizar um servidor efetivo.
- **[DELETE] /api/permanent-servants/{id}** - Remover um servidor efetivo.

### Unidades

- **[POST] /api/units** - Criar uma unidade.
- **[GET] /api/units** - Listar todas as unidades.
- **[GET] /api/units/{id}** - Retornar uma unidade específica.
- **[PUT] /api/units/{id}** - Atualizar uma unidade.
- **[DELETE] /api/units/{id}** - Remover uma unidade.

### Lotações

- **[POST] /api/assignment** - Criar uma lotação.
- **[GET] /api/assignment** - Listar todas as lotações.
- **[GET] /api/assignment/{id}** - Retornar uma lotação específica.
- **[PUT] /api/assignment/{id}** - Atualizar uma lotação.
- **[DELETE] /api/assignment/{id}** - Remover uma lotação.

---

## Contribuição

Sugestões e melhorias são bem-vindas! Envie um **pull request** ou abra uma **issue** no GitHub.

---

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

---

## Dados do Desenvolvedor

- **Nome:** Raphael Rychard
- **GitHub:** [RaphaelRychard](https://github.com/RaphaelRychard)

