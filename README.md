Segue abaixo uma versão atualizada do README, incluindo os requisitos específicos do edital e as orientações do processo:

---

# Seplag API

Uma API REST construída para gerenciar os dados da SEPLAG, conforme o diagrama de banco de dados e as orientações do edital.  
Esta solução implementa um CRUD para Servidor Efetivo, Servidor Temporário, Unidade e Lotação, além de endpoints específicos para consultas e upload de fotografias utilizando Min.IO.

---

## Sumário

- [Seplag API](#seplag-api)
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
      - [\[POST\] `/api/register`](#post-apiregister)
      - [\[POST\] `/api/login`](#post-apilogin)
      - [\[GET\] `/api/user`](#get-apiuser)
    - [Pessoas](#pessoas)
      - [\[POST\] `/api/persons`](#post-apipersons)
      - [\[GET\] `/api/persons`](#get-apipersons)
      - [\[GET\] `/api/persons/{id}`](#get-apipersonsid)
      - [\[PUT\] `/api/persons/{id}`](#put-apipersonsid)
    - [Servidores Efetivos e Temporários](#servidores-efetivos-e-temporários)
      - [\[POST\] `/api/permanent-servants`](#post-apipermanent-servants)
      - [\[GET\] `/api/permanent-servants`](#get-apipermanent-servants)
      - [\[GET\] `/api/permanent-servants/{id}`](#get-apipermanent-servantsid)
      - [\[PUT\] `/api/permanent-servants/{id}`](#put-apipermanent-servantsid)
    - [Unidades](#unidades)
      - [\[POST\] `/api/units`](#post-apiunits)
      - [\[GET\] `/api/units`](#get-apiunits)
      - [\[GET\] `/api/units/{id}`](#get-apiunitsid)
      - [\[PUT\] `/api/units/{id}`](#put-apiunitsid)
    - [Lotação](#lotação)
      - [\[POST\] `/api/assignment`](#post-apiassignment)
      - [\[GET\] `/api/assignment`](#get-apiassignment)
      - [\[GET\] `/api/assignment/{id}`](#get-apiassignmentid)
      - [\[PUT\] `/api/assignment/{id}`](#put-apiassignmentid)
    - [Consultas Específicas](#consultas-específicas)
      - [\[GET\] `/api/servers/by-unit`](#get-apiserversby-unit)
      - [\[GET\] `/api/address/by-name`](#get-apiaddressby-name)
    - [Upload de Fotografias](#upload-de-fotografias)
      - [\[POST\] `/api/upload`](#post-apiupload)
  - [Exemplos de Erros](#exemplos-de-erros)
    - [Erro 400 – Requisição Inválida](#erro-400--requisição-inválida)
    - [Erro 401 – Não Autorizado](#erro-401--não-autorizado)
    - [Erro 404 – Recurso Não Encontrado](#erro-404--recurso-não-encontrado)
    - [Erro 500 – Erro Interno do Servidor](#erro-500--erro-interno-do-servidor)
  - [Considerações Gerais](#considerações-gerais)
  - [Contribuição](#contribuição)
  - [Licença](#licença)
  - [Dados do Desenvolvedor](#dados-do-desenvolvedor)

---

## Visão Geral

A Seplag API foi desenvolvida utilizando PHP (Laravel) para gerenciar operações de CRUD e consultas referentes a:

- **Servidor Efetivo e Servidor Temporário**
- **Unidade**
- **Lotação**

Além disso, foram implementados endpoints para:

- Consultar os servidores efetivos lotados em uma determinada unidade (filtrando pelo atributo `unid_id`), retornando **Nome, idade, unidade de lotação e fotografia**.
- Consultar o endereço funcional (da unidade onde o servidor é lotado) a partir de uma parte do nome do servidor efetivo.
- Realizar o upload de uma ou mais fotografias, enviando-as para o Min.IO e recuperando links temporários com tempo de expiração de 5 minutos.

---

## Requisitos Específicos

- **CRUD Completo:**  
  - Servidor Efetivo  
  - Servidor Temporário  
  - Unidade  
  - Lotação  
  Devem ser contempladas as operações de inclusão, edição, exclusão e listagem, com as tabelas relacionadas integradas.

- **Endpoints Específicos:**  
  - **Consulta por Unidade:** Endpoint que permita consultar os servidores efetivos lotados em uma unidade, utilizando o parâmetro `unid_id`. A resposta deverá incluir os campos: **Nome, idade, unidade de lotação e fotografia**.
  - **Consulta por Nome:** Endpoint que permita consultar o endereço funcional (da unidade onde o servidor é lotado) a partir de uma parte do nome do servidor efetivo.

- **Upload de Fotografias:**  
  - Permitir o upload de uma ou mais fotografias.  
  - As imagens devem ser enviadas para o Min.IO.  
  - A recuperação das imagens deverá ser feita por meio de links temporários, com tempo de expiração de 5 minutos, gerados pela biblioteca do Min.IO.

- **Versionamento e Instruções:**  
  - O projeto deverá estar disponível no GitHub.  
  - O README.md deve conter os dados de inscrição do desenvolvedor e orientações de como executar e testar a solução.  
  - Após o prazo de entrega, nenhum commit adicional deverá ser enviado ao repositório.

---

## Requisitos e Dependências

Certifique-se de que sua máquina possui:

- **PHP:** Versão compatível com Laravel (recomenda-se PHP 8 ou superior);
- **Composer:** Gerenciador de dependências do PHP;
- **Docker & Docker Compose:** (Opcional, mas recomendado) para facilitar o setup do ambiente;
- **Banco de Dados:** MySQL, PostgreSQL ou outro compatível com Laravel (configurado via .env);
- **Git:** Para clonar o repositório;
- **Min.IO Client:** Biblioteca para geração de links temporários (já incluída como dependência).

---

## Instalação

### Com Docker Compose

1. Clone o repositório:
   ```bash
   git clone https://github.com/RaphaelRychard/api-seplag.git
   cd api-seplag
   ```
2. Copie o arquivo de ambiente:
   ```bash
   cp .env.example .env
   ```
3. Ajuste as variáveis do arquivo `.env` (ex.: conexão com o banco de dados, APP_KEY, configurações do Min.IO, etc.).
4. Execute o Docker Compose para construir e subir os containers:
   ```bash
   docker-compose up --build
   ```
   O Docker Compose irá subir a aplicação, o banco de dados e os serviços necessários (inclusive o Min.IO, se configurado).

### Sem Docker

1. Clone o repositório:
   ```bash
   git clone https://github.com/RaphaelRychard/api-seplag.git
   cd api-seplag
   ```
2. Instale as dependências com Composer:
   ```bash
   composer install
   ```
3. Copie o arquivo de ambiente:
   ```bash
   cp .env.example .env
   ```
4. Gere a chave da aplicação:
   ```bash
   php artisan key:generate
   ```
5. Configure o arquivo `.env` com as informações do seu banco de dados, Min.IO e demais configurações.
6. Execute as migrations (se houver):
   ```bash
   php artisan migrate
   ```
7. Inicie o servidor:
   ```bash
   php artisan serve
   ```
   A aplicação ficará disponível em `http://localhost:8000`.

---

## Uso

Para testar os endpoints, utilize ferramentas como Postman ou cURL.  
Lembre-se de incluir o cabeçalho `Authorization: Bearer {seu_token}` para os endpoints protegidos.

Exemplo com cURL:
```bash
curl -X GET http://localhost:8000/api/user \
     -H "Authorization: Bearer seu_token_aqui" \
     -H "Accept: application/json"
```

---

## Endpoints

### Autenticação

#### [POST] `/api/register`
- **Descrição:** Registra um novo usuário.
- **Cabeçalhos:** `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
      "name": "Nome Qualquer",
      "email": "examplo@examplo.com",
      "password": "12345",
      "password_confirmation": "12345"
  }
  ```
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "message": "Usuário registrado com sucesso",
      "data": { "id": 1, "name": "Nome Qualquer", "email": "examplo@examplo.com" }
  }
  ```

#### [POST] `/api/login`
- **Descrição:** Autentica o usuário e retorna um token JWT.
- **Cabeçalhos:** `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
      "email": "examplo@examplo.com",
      "password": "12345"
  }
  ```
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "token": "jwt_token_aqui"
  }
  ```

#### [GET] `/api/user`
- **Descrição:** Retorna os dados do usuário autenticado.
- **Cabeçalhos:** `Authorization: Bearer {seu_token_aqui}`
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "data": { "id": 1, "name": "Nome Qualquer", "email": "examplo@examplo.com" }
  }
  ```

---

### Pessoas

#### [POST] `/api/persons`
- **Descrição:** Cria um novo registro de pessoa.
- **Cabeçalhos:** `Authorization`, `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
      "nome": "Kristi Ernser",
      "data_nascimento": "2003-03-29",
      "sexo": "masculino",
      "mae": "Tony Barton",
      "pai": "Sandy Mitchell"
  }
  ```
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "message": "Pessoa criada com sucesso",
      "data": { "id": 1, "nome": "Kristi Ernser", ... }
  }
  ```

#### [GET] `/api/persons`
- **Descrição:** Lista todas as pessoas.
- **Cabeçalhos:** `Authorization`
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "data": [
          { "id": 1, "nome": "Kristi Ernser", ... },
          { "id": 2, "nome": "Outro Nome", ... }
      ]
  }
  ```

#### [GET] `/api/persons/{id}`
- **Descrição:** Retorna os dados de uma pessoa específica.
- **Cabeçalhos:** `Authorization`
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "data": { "id": 1, "nome": "Kristi Ernser", ... }
  }
  ```

#### [PUT] `/api/persons/{id}`
- **Descrição:** Atualiza os dados de uma pessoa.
- **Cabeçalhos:** `Authorization`, `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
      "nome": "Outro Nome 12123123",
      "data_nascimento": "2001-05-06",
      "sexo": "Masculino",
      "mae": "Flavia Soares",
      "pai": "Welbdo Teste"
  }
  ```
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "message": "Pessoa atualizada com sucesso",
      "data": { "id": 2, "nome": "Outro Nome 12123123", ... }
  }
  ```

---

### Servidores Efetivos e Temporários

#### [POST] `/api/permanent-servants`
- **Descrição:** Registra um novo servidor efetivo.
- **Cabeçalhos:** `Authorization`, `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
      "pes_id": "1",
      "se_matricula": "012393"
  }
  ```
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "message": "Servidor efetivo registrado com sucesso",
      "data": { "id": 1, "pes_id": "1", "se_matricula": "012393" }
  }
  ```

> **Observação:** Um CRUD similar deve ser implementado para Servidor Temporário, seguindo a mesma estrutura.

#### [GET] `/api/permanent-servants`
- **Descrição:** Lista todos os servidores efetivos.
- **Cabeçalhos:** `Authorization`
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "data": [
          { "id": 1, "pes_id": "1", "se_matricula": "012393" },
          { "id": 2, "pes_id": "2", "se_matricula": "045678" }
      ]
  }
  ```

#### [GET] `/api/permanent-servants/{id}`
- **Descrição:** Retorna os dados de um servidor efetivo específico.
- **Cabeçalhos:** `Authorization`
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "data": { "id": 1, "pes_id": "1", "se_matricula": "012393" }
  }
  ```

#### [PUT] `/api/permanent-servants/{id}`
- **Descrição:** Atualiza os dados de um servidor efetivo.
- **Cabeçalhos:** `Authorization`, `Content-Type: application/json`, `Accept: application/json`
- **Body:** (Utilize os campos necessários para atualização)
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "message": "Servidor efetivo atualizado com sucesso",
      "data": { "id": 2, ... }
  }
  ```

---

### Unidades

#### [POST] `/api/units`
- **Descrição:** Cria uma nova unidade.
- **Cabeçalhos:** `Authorization`, `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
      "nome": "Uma unidade nova unidade de teste",
      "sigla": "UUNDT"
  }
  ```
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "message": "Unidade criada com sucesso",
      "data": { "id": 1, "nome": "Uma unidade nova unidade de teste", "sigla": "UUNDT" }
  }
  ```

#### [GET] `/api/units`
- **Descrição:** Lista todas as unidades.
- **Cabeçalhos:** `Authorization`
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "data": [
          { "id": 1, "nome": "Uma unidade nova unidade de teste", "sigla": "UUNDT" },
          { "id": 2, "nome": "Unidade X", "sigla": "UX" }
      ]
  }
  ```

#### [GET] `/api/units/{id}`
- **Descrição:** Retorna os dados de uma unidade específica.
- **Cabeçalhos:** `Authorization`
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "data": { "id": 1, "nome": "Uma unidade nova unidade de teste", "sigla": "UUNDT" }
  }
  ```

#### [PUT] `/api/units/{id}`
- **Descrição:** Atualiza os dados de uma unidade.
- **Cabeçalhos:** `Authorization`, `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
      "nome": "Um update",
      "sigla": "UP"
  }
  ```
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "message": "Unidade atualizada com sucesso",
      "data": { "id": 1, "nome": "Um update", "sigla": "UP" }
  }
  ```

---

### Lotação

#### [POST] `/api/assignment`
- **Descrição:** Registra uma nova lotação.
- **Cabeçalhos:** `Authorization`, `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
      "pes_id": "1",
      "unid_id": "1",
      "data_lotacao": "2017-03-05",
      "data_remocao": "2019-01-25",
      "portaria": "Portaria de Teste"
  }
  ```
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "message": "Lotação registrada com sucesso",
      "data": { "id": 1, "pes_id": "1", "unid_id": "1", ... }
  }
  ```

#### [GET] `/api/assignment`
- **Descrição:** Lista todas as lotações.
- **Cabeçalhos:** `Authorization`
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "data": [
          { "id": 1, "pes_id": "1", "unid_id": "1", ... },
          { "id": 2, "pes_id": "2", "unid_id": "2", ... }
      ]
  }
  ```

#### [GET] `/api/assignment/{id}`
- **Descrição:** Retorna os dados de uma lotação específica.
- **Cabeçalhos:** `Authorization`
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "data": { "id": 1, "pes_id": "1", "unid_id": "1", ... }
  }
  ```

#### [PUT] `/api/assignment/{id}`
- **Descrição:** Atualiza os dados de uma lotação.
- **Cabeçalhos:** `Authorization`, `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
      "data_lotacao": "2019-03-01",
      "data_remocao": "2022-08-04",
      "portaria": "Portaria de Outra 2"
  }
  ```
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "message": "Lotação atualizada com sucesso",
      "data": { "id": 1, ... }
  }
  ```

---

### Consultas Específicas

#### [GET] `/api/servers/by-unit`
- **Descrição:** Consulta os servidores efetivos lotados em determinada unidade.
- **Parâmetro:**  
  - `unid_id` (via query string)
- **Resposta:** Retorna os seguintes campos para cada servidor efetivo:
  - **Nome**
  - **Idade**
  - **Unidade de lotação**
  - **Fotografia** (link temporário com expiração de 5 minutos)
- **Exemplo de Requisição:**
  ```bash
  curl -X GET "http://localhost:8000/api/servers/by-unit?unid_id=1" \
       -H "Authorization: Bearer seu_token_aqui" \
       -H "Accept: application/json"
  ```
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "data": [
          {
              "nome": "Servidor Exemplo",
              "idade": 35,
              "unidade": "Unidade X",
              "fotografia": "http://minio.example.com/temp/abc123?expires=300"
          },
          ...
      ]
  }
  ```

#### [GET] `/api/address/by-name`
- **Descrição:** Consulta o endereço funcional (da unidade onde o servidor é lotado) a partir de parte do nome do servidor efetivo.
- **Parâmetro:**  
  - `nome` (via query string)
- **Exemplo de Requisição:**
  ```bash
  curl -X GET "http://localhost:8000/api/address/by-name?nome=Fulano" \
       -H "Authorization: Bearer seu_token_aqui" \
       -H "Accept: application/json"
  ```
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "data": {
          "nome": "Fulano de Tal",
          "endereco_funcional": "Rua Exemplo, 123, Bairro X, Cidade Y"
      }
  }
  ```

---

### Upload de Fotografias

#### [POST] `/api/upload`
- **Descrição:** Realiza o upload de uma ou mais fotografias.  
  As imagens são enviadas para o Min.IO e, após o upload, são gerados links temporários com expiração de 5 minutos para recuperação.
- **Cabeçalhos:** `Authorization`, `Accept: application/json`
- **Body:** (form-data)
  - **file:** Arquivo a ser enviado.
  - **pes_id:** ID relacionado (ex.: "1").
- **Resposta Exemplo:**
  ```json
  {
      "status": "success",
      "message": "Arquivo enviado com sucesso",
      "data": { "file_url": "http://minio.example.com/temp/xyz789?expires=300" }
  }
  ```

---

## Exemplos de Erros

### Erro 400 – Requisição Inválida
```json
{
    "status": "error",
    "message": "Dados inválidos fornecidos.",
    "errors": {
        "email": ["O campo email é obrigatório."]
    }
}
```

### Erro 401 – Não Autorizado
```json
{
    "status": "error",
    "message": "Token inválido ou expirado."
}
```

### Erro 404 – Recurso Não Encontrado
```json
{
    "status": "error",
    "message": "Recurso não encontrado."
}
```

### Erro 500 – Erro Interno do Servidor
```json
{
    "status": "error",
    "message": "Erro interno do servidor. Tente novamente mais tarde."
}
```

---

## Considerações Gerais

- **Autenticação:** Utilize sempre o token JWT para acessar os endpoints protegidos.
- **Formato:** Todas as requisições e respostas são em JSON, exceto no upload, que utiliza form-data.
- **Links Temporários:** Os links gerados para as fotografias expiram após 5 minutos.
- **Versionamento:** Mantenha a documentação atualizada conforme novos endpoints ou alterações na API forem implementadas.

---

## Contribuição

Contribuições para melhorar a documentação ou a API são bem-vindas!  
Para contribuir, abra uma _issue_ ou envie um _pull request_ com suas sugestões.  
**Importante:** Após o prazo de entrega, nenhum commit adicional deverá ser enviado ao repositório.

---

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

---

## Dados do Desenvolvedor

- **Nome:** Seu Nome
- **Contato:** seu.email@exemplo.com
- **GitHub:** [seu-usuario](https://github.com/seu-usuario)

---

Esta documentação reúne os detalhes do edital e as orientações do processo, fornecendo um guia completo para instalação, configuração e uso da Seplag API. Se houver necessidade de mais ajustes ou informações adicionais, estou à disposição!