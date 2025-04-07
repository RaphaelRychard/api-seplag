# **Seplag API**

## **Descrição Geral**

Seplag API é uma solução back-end robusta desenvolvida em Laravel, que oferece recursos completos para o gerenciamento de servidores efetivos, temporários, unidades administrativas e operações relacionadas, como lotação e upload de fotografias. O sistema utiliza autenticação via **Laravel Sanctum** e segue práticas avançadas de segurança e desenvolvimento.

---

# **Índice**

1. [Descrição Geral](#descrição-geral)
2. [Requisitos do Sistema](#requisitos-do-sistema)
3. [Configuração do Ambiente](#configuração-do-ambiente)
    - [Com Docker Compose](#com-docker-compose)
    - [Sem Docker](#sem-docker)
4. [Rotas da API](#rotas-da-api)
    - [Autenticação](#autenticação)
        - [Login](#login)
        - [Registro](#registro)
    - [Servidores Efetivos](#servidores-efetivos)
        - [Listar Servidores Efetivos](#listar-servidores-efetivos)
        - [Inserir Servidor Efetivo](#inserir-servidor-efetivo)
    - [Servidores Temporários](#servidores-temporários)
        - [Listar Servidores Temporários](#listar-servidores-temporários)
    - [Lotações](#lotações)
        - [Atualizar Lotação de Funcionário](#atualizar-lotação-de-funcionário)
        - [Remover Lotação de Funcionário](#remover-lotação-de-funcionário)
    - [Unidades](#unidades)
        - [Listar Unidades](#listar-unidades)
    - [Fotografias](#fotografias)
        - [Upload de Fotografia](#upload-de-fotografia)
    - [Endereços Funcionais](#endereços-funcionais)
        - [Buscar Endereços Funcionais](#buscar-endereços-funcionais)
5. [Coleção Postman](#coleção-postman)
6. [Testes Automatizados](#testes-automatizados)
7. [Changelog](#changelog)
8. [Observações Importantes](#observações-importantes)

---

## **Dados da Inscrição**

- **Nome**: Raphael Rychard Soares de Almeida Souza
- **E-mail**: raph.rych@gmail.com
- **Concurso**: SEPLAG - Sistema de Gerenciamento Administrativo - PSS 02/2025/SEPLAG (Analista de TI - Perfil Junior, Pleno e Sênior)
- **Identificador**: 8463
- **Perfil**: DESENVOLVEDOR PHP - JÚNIOR

---


## **Requisitos do Sistema**

Antes de configurar e executar o projeto, certifique-se de que as dependências necessárias estão instaladas:

### **Dependências Principais**

- **PHP**: Versão mínima **8.3**
- **Composer**: Para gerenciamento de pacotes PHP
- **Banco de Dados**: **PostgreSQL** (ou compatível)
- **Laravel**: Versão **^12.0**
- **Docker** *(Opcional)*: Para configuração rápida do ambiente
- **Extensões PHP necessárias**:
    - `pdo_pgsql`
    - `mbstring`
    - `openssl`
    - `fileinfo`
    - `json`
    - `xml`

---

## **Configuração do Ambiente**

O ambiente pode ser configurado utilizando Docker Compose ou de forma manual.

### **Com Docker Compose**

1. Clone o repositório:
   ```bash
   git clone https://github.com/seplag/api.git
   cd api
   ```

2. Configure o arquivo `.env`:
   ```bash
   cp .env.example .env
   ```

3. Inicie os serviços Docker:
   ```bash
   docker-compose up -d
   ```

4. Execute as migrações:
   ```bash
   docker exec -it seplag-app php artisan migrate
   ```

5. Gere a chave da aplicação:
   ```bash
   docker exec -it seplag-app php artisan key:generate
   ```

A API estará disponível em [http://localhost:8000](http://localhost:8000).

---

### **Sem Docker**

1. Configure o ambiente:
   ```bash
   cp .env.example .env
   ```

2. Instale as dependências com o Composer:
   ```bash
   composer install
   ```

3. Configure o banco de dados PostgreSQL no arquivo `.env`.

4. Execute as migrações:
   ```bash
   php artisan migrate
   ```

5. Suba o servidor local:
   ```bash
   php artisan serve
   ```
   A API estará disponível em [http://localhost:8000](http://localhost:8000).

---

# Rotas da API

## Autenticação

### **Login**
`POST /api/login`

Realiza o login do usuário, retornando um token de autenticação.

- **Requisição**:
    - Corpo do Request:
      ```json
      {
        "email": "usuario@dominio.com",
        "password": "sua_senha"
      }
      ```
    - Validação realizada pelo arquivo: `LoginRequest.php`
        - `email`: string, obrigatório, formato de e-mail válido.
        - `password`: string, obrigatório.

- **Resposta**:
    ```json
    {
      "token": "token-informado-pelo-laravel-sanctum"
    }
    ```

---

### **Registro**
`POST /api/register`

Permite criar um novo usuário.

- **Requisição**:
    - Corpo do Request:
      ```json
      {
        "name": "Nome do Usuário",
        "email": "usuario@dominio.com",
        "password": "senha",
        "password_confirmation": "senha"
      }
      ```
    - Validação realizada pelo arquivo: `RegisterRequest.php`
        - `name`: string, obrigatório, 1-255 caracteres.
        - `email`: string, obrigatório, único em `users`, formato válido.
        - `password`: string, obrigatório, deve coincidir com `password_confirmation`.

- **Resposta**:
    ```json
    {
      "token": "novo-token-gerado"
    }
    ```

---

## Servidores Efetivos

### **Listar Servidores Efetivos**
`GET /api/permanent-servants`

Retorna uma listagem paginada de servidores efetivos com base nos filtros aplicados.

- **Parâmetros de Consulta (Query Params)**:
    - `nome`: string, opcional, filtra servidores pelo nome.
    - `unid_id`: integer, opcional, filtra servidores pela unidade específica.
    - `with`: string, opcional, carrega relacionamentos adicionais (ex.: `person,assignment`).
    - `per_page`: integer, opcional, número de registros por página (default: 10).
    - `page`: integer, opcional, número da página.

- **Resposta**:
    - Validação realizada pelo arquivo: `PermanentServantsController.php`.
    - Recurso retornado: `FetchPermanentServantResource.php`.
      ```json
      {
        "data": [
          {
            "id": 1,
            "pes_id": 1,
            "nome": "João da Silva",
            "idade": 40,
            "fotografia": "http://url-temporaria/path",
            "unidade_lotacao": {
              "id": 1,
              "nome": "Administração",
              "sigla": "ADM"
            }
          }
        ],
        "pagination": {
          "total": 50,
          "per_page": 10,
          "current_page": 1,
          "last_page": 5
        }
      }
      ```

---

### **Inserir Servidor Efetivo**
`POST /api/permanent-servants`

Adiciona um novo servidor efetivo.

- **Requisição**:
    - Corpo do Request:
      ```json
      {
        "nome": "João da Silva",
        "data_nascimento": "1980-01-01",
        "sexo": "Masculino",
        "mae": "Maria da Silva",
        "pai": "José da Silva",
        "se_matricula": "X12345"
      }
      ```
    - Validação realizada pelo arquivo: `StorePermanentServantRequest.php`.

- **Resposta**:
    - Recurso retornado: `StorePermanentServantResource.php`.
      ```json
      {
        "id": 1,
        "pes_id": 1,
        "se_matricula": "X12345"
      }
      ```

---

## Servidores Temporários

### **Listar Servidores Temporários**
`GET /api/temporary-servants`

Funciona de forma semelhante ao endpoint de listagem de servidores efetivos, mas opera sobre a tabela e o modelo de servidores temporários.

- **Resposta**:
    - Validação realizada no controlador: `TemporaryServantsController.php`.
    - Recurso retornado: `FetchTemporaryServantResource.php`.

---

## Lotações

### **Atualizar Lotação de Funcionário**
`PUT /api/assignments/{id}`

Atualiza os dados da lotação de um funcionário.

- **Requisição**:
    ```json
    {
      "data_lotacao": "2023-01-01",
      "portaria": "Portaria XYZ"
    }
    ```
    - Validação realizada pelo arquivo: `UpdateAssignmentRequest.php`.

- **Resposta**:
    ```json
    {
      "id": 1,
      "pes_id": 1,
      "unid_id": 2,
      "data_lotacao": "2023-01-01",
      "data_remocao": null,
      "portaria": "Portaria XYZ"
    }
    ```

---

### **Remover Lotação de Funcionário**
`PATCH /api/assignment/{assignment}/remove`

Remove um funcionário de sua lotação atual.

- **Requisição**:
    - Endpoint requer a identificação da lotação (`id`).
    - Validado no controlador: `AssignmentController`.

- **Resposta**:
  ```json
  {
    "id": 1,
    "mensagem": "Lotação removida com sucesso."
  }
  ```

---

## Unidades

### **Listar Unidades**
`GET /api/units`

Retorna as unidades disponíveis com paginação.

- **Parâmetros de Consulta (Query Params)**:
    - `nome`: string, opcional.
    - `sigla`: string, opcional.
    - `per_page`: integer, opcional, número por página (default: 10).

- **Resposta**:
    - Validação realizada pelo controlador: `UnitController.php`.
    - Recurso retornado: `UnitResource.php`.

---

## Fotografias

### **Upload de Fotografia**
`POST /api/photograph`

Faz o upload de uma fotografia para um funcionário específico.

- **Requisição**:
    ```json
    {
      "file": "arquivo.jpg",
      "pes_id": 1
    }
    ```
    - Validação realizada via arquivo: `FileUploadRequest.php`.
    - Regras:
        - `file`: obrigatório, formatos suportados: JPEG e PNG, até 4MB.
        - `pes_id`: obrigatório e deve existir na tabela `pessoa`.

- **Resposta**:
    ```json
    {
      "id": 1,
      "path": "uploads/arquivo_hash.jpg",
      "url": "http://url-temporaria/arquivo_hash.jpg"
    }
    ```

---

## Endereços Funcionais

### **Buscar Endereços Funcionais**
`GET /api/functional-address/search`

Realiza uma busca de endereços funcionais com base nos parâmetros fornecidos.

- **Parâmetros de Consulta (Query Params)**:
    - `nome`: string, opcional.
    - `localidade`: string, opcional.

- **Resposta**:
    - Validado no controlador: `FunctionalAddressController`.
    - Exemplo de retorno:
      ```json
      {
        "data": [
          {
            "id": 1,
            "nome": "Departamento Administrativo",
            "localidade": "Cidade XYZ"
          }
        ]
      }
      ```

## **Respostas de Erro**

Todas as requisições que gerem erros retornarão uma resposta similar ao exemplo abaixo:

```json
{
  "message": "Erro descritivo",
  "errors": {
    "campo": ["Descrição do erro no campo"]
  },
  "status": 400
}
```

**Códigos de Status Comuns**:
- `200`: Sucesso.
- `201`: Criado.
- `400`: Erro de validação.
- `401`: Não autorizado.
- `403`: Acesso proibido.
- `404`: Recurso não encontrado.
- `500`: Erro interno do servidor.

---


## **Coleção Postman**

Uma coleção Postman com todos os endpoints da API está disponível para facilitar os testes. Faça o download e importe-a no Postman para começar a usá-la:

[Baixar Coleção Postman](#)

---

## **Testes Automatizados**

O projeto foi desenvolvido com suporte a testes automatizados utilizando **Pest**.

### **Como Executar os Testes**

1. Configure o banco de dados de teste no arquivo `.env.testing`.
2. Execute as migrações para o ambiente de teste:
   ```bash
   php artisan migrate --env=testing
   ```

3. Execute os testes:
   ```bash
   ./vendor/bin/pest
   ```

---

## **Changelog**

### **Versão 1.0.0**
- Implementação de:
    - Autenticação com Laravel Sanctum.
    - CRUD completo para:
        - Servidores Efetivos.
        - Servidores Temporários.
        - Unidades.
        - Lotações.
    - Upload de fotografias.
- Configuração do ambiente Docker.
- Implementação de testes automatizados com Pest.

---

## **Observações Importantes**

1. Nenhum commit deve ser enviado após o prazo de entrega.
2. Apenas dependências do `composer.json` foram utilizadas.
3. O projeto foi desenvolvido integrando boas práticas de segurança e escalabilidade.
