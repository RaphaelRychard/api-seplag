# Seplag API

Uma API REST desenvolvida para gerenciar os dados da SEPLAG, de acordo com o diagrama do banco de dados e as orientações do edital. A solução implementa um CRUD para Servidores Efetivos, Servidores Temporários, Unidades e Lotações, além de endpoints dedicados à consulta e ao upload de fotografias utilizando Min.IO.

---

## Sumário

- [Instalação](#instalação)
    - [Com Docker Compose](#com-docker-compose)
    - [Sem Docker](#sem-docker)
- [Configuração do Min.IO](#configuração-do-minio)
- [Rotas da API](#rotas-da-api)
- [Uso dos Endpoints](#uso-dos-endpoints)
    - [Upload de Fotografias](#upload-de-fotografias)
    - [Autenticação](#autenticação)
- [Documentação no Postman](#documentação-no-postman)
- [Testes Automatizados](#testes-automatizados)
- [Observações Finais](#observações-finais)

---

## Instalação

### Com Docker Compose

1. Clone o repositório e acesse o diretório:
   ```bash
   git clone https://github.com/RaphaelRychard/api-seplag.git
   cd api-seplag
   ```

2. Copie o arquivo de exemplo do ambiente e faça as devidas alterações:
   ```bash
   cp .env.example .env
   ```

3. Suba os containers utilizando o Docker Compose (o arquivo já contém os serviços configurados para PostgreSQL e Min.IO):
   ```bash
   docker-compose up -d
   ```

4. Após a execução, o serviço Min.IO estará disponível:
    - **Administração:** [http://localhost:9001](http://localhost:9001)
    - **API:** [http://localhost:9000](http://localhost:9000)

---

### Sem Docker

1. Clone o repositório e instale as dependências do projeto:
   ```bash
   git clone https://github.com/RaphaelRychard/api-seplag.git
   cd api-seplag
   composer install
   ```

2. Configure o ambiente Laravel e gere a chave da aplicação:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Execute as migrations para a estrutura do banco de dados:
   ```bash
   php artisan migrate
   ```

4. Por fim, inicie o servidor:
   ```bash
   php artisan serve
   ```

---

## Configuração do Min.IO

Após iniciar os containers (ou o serviço Min.IO na forma que preferir), siga os passos abaixo para configurar o armazenamento de fotografias:

### Acessando o Painel do Min.IO

1. Abra o navegador e acesse o painel de administração:  
   **URL:** [http://localhost:9001](http://localhost:9001)  
   **Usuário (ROOT):** `myadmin`  
   **Senha:** `mysecurepassword`

2. **Criação do Bucket:**
    - No menu principal, clique em **Buckets**.
    - Clique em **Create Bucket**.
    - Insira um nome para o bucket (por exemplo, `my-bucket`) e confirme.

3. **Gerando Chaves de Acesso:**
    - No painel, vá até a aba **Access Keys**.
    - Clique em **Create Access Key**.
    - Após a criação, guarde as credenciais geradas (Access Key e Secret Key).

### Atualizando o Arquivo `.env`

Configure o Laravel para utilizar o Min.IO como sistema de armazenamento de arquivos. Atualize as variáveis no seu arquivo `.env`:

```dotenv
FILESYSTEM_DISK=s3

MINIO_ENDPOINT=http://localhost:9000
MINIO_KEY=myadmin
MINIO_SECRET=mysecurepassword
MINIO_REGION=us-east-1
MINIO_BUCKET=my-bucket
AWS_USE_PATH_STYLE_ENDPOINT=true
```

Observações:

- As variáveis `MINIO_KEY` e `MINIO_SECRET` devem corresponder às credenciais definidas no painel do Min.IO.
- Certifique-se de que o driver (`FILESYSTEM_DISK`) esteja configurado como `s3` para que o Laravel utilize o Min.IO de forma compatível com a API do AWS S3.

---

## Rotas da API

Todos os endpoints estão protegidos por autenticação via **Laravel Sanctum**. Segue um resumo de todas as rotas disponíveis:

### 1. Servidores Efetivos
- **GET** `/api/permanent-servants`  
  Lista todos os servidores efetivos. Permite filtros opcionais, exemplos:
    - `per_page` – Itens por página.
    - `page` – Número da página.
    - `unid_id` – Filtrar por unidade.
    - `nome` – Filtrar por nome.

- **POST** `/api/permanent-servants`  
  Cria um novo servidor efetivo.

- **GET** `/api/permanent-servants/{id}`  
  Exibe os detalhes de um servidor efetivo específico.

- **PUT/PATCH** `/api/permanent-servants/{id}`  
  Atualiza as informações de um servidor efetivo.

### 2. Servidores Temporários
- **GET** `/api/temporary-servants`  
  Lista todos os servidores temporários, podendo receber filtros semelhantes aos servidores efetivos.

- **POST** `/api/temporary-servants`  
  Registra um novo servidor temporário.

- **GET** `/api/temporary-servants/{id}`  
  Exibe os detalhes de um servidor temporário específico.

- **PUT/PATCH** `/api/temporary-servants/{id}`  
  Atualiza as informações de um servidor temporário.

### 3. Unidades
- **GET** `/api/units`  
  Lista todas as unidades.

- **POST** `/api/units`  
  Cria uma nova unidade.

- **GET** `/api/units/{id}`  
  Exibe os detalhes de uma unidade.

- **PUT/PATCH** `/api/units/{id}`  
  Atualiza os dados de uma unidade.

### 4. Lotações (Assignment)
- **GET** `/api/assignment`  
  Exibe todas as relações de lotação.

- **POST** `/api/assignment`  
  Registra uma nova atribuição.

- **GET** `/api/assignment/{id}`  
  Exibe os detalhes de uma atribuição específica.

- **PUT/PATCH** `/api/assignment/{id}`  
  Atualiza os dados de uma atribuição.

- **PATCH** `/api/assignment/{assignment}/remove`  
  Endpoint para remover (desassociar) uma atribuição específica.

### 5. Upload de Fotografias
- **POST** `/api/photograph`  
  Realiza o upload de uma fotografia para o bucket configurado no Min.IO.

---

## Uso dos Endpoints

### Upload de Fotografias

O endpoint de upload permite enviar imagens para o bucket configurado.  
**Exemplo utilizando `cURL`:**

```bash
curl -X POST "http://localhost/api/photograph" \
     -H "Authorization: Bearer {SEU_TOKEN}" \
     -F "file=@/caminho/para/o/arquivo.jpg"
```

### Autenticação

A API utiliza o **Laravel Sanctum** para autenticação. Todos os endpoints estão protegidos e requerem a inclusão do header com o token:

```http
Authorization: Bearer {SEU_TOKEN}
```

Para gerar um token, utilize o endpoint de login:

```bash
curl -X POST "http://localhost/api/login" \
     -H "Content-Type: application/json" \
     -d '{"email": "email@example.com", "password": "senha123"}'
```

---

## Documentação no Postman

A documentação completa da API está disponível no Postman. Acesse através do link:

- [Documentação no Postman](https://documenter.getpostman.com/view/32616805/2sB2cPkR88)

---

## Testes Automatizados

Os testes da Seplag API foram implementados utilizando a ferramenta **Pest**. Segue um resumo de como executar e utilizar esses testes:

1. **Instalação das Dependências de Desenvolvimento:**

   Certifique-se de que as dependências de desenvolvimento foram instaladas:
   ```bash
   composer install --dev
   ```

2. **Configuração do Ambiente de Testes:**

   Copie o arquivo de exemplo para o ambiente de testes:
   ```bash
   cp .env.example .env.testing
   ```

   Ajuste as variáveis de conexão no arquivo `.env.testing`:
   ```dotenv
   DB_CONNECTION=pgsql
   DB_HOST=0.0.0.0
   DB_PORT=5432
   DB_DATABASE=seplag_test
   DB_USERNAME=docker
   DB_PASSWORD=docker
   ```

3. **Execução das Migrations no Ambiente de Teste:**

   Rode as migrations para preparar o banco de dados de testes:
   ```bash
   php artisan migrate --env=testing
   ```

4. **Execução dos Testes:**

   Para rodar todos os testes:
   ```bash
   ./vendor/bin/pest
   ```

   Se desejar gerar relatórios de cobertura de código (necessário ter o Xdebug ou outra ferramenta compatível instalado):
   ```bash
   ./vendor/bin/pest --coverage
   ```

### O que os Testes Abrangem

- **Testes de Integração:**  
  Validação dos endpoints e das operações CRUD para Servidores Efetivos, Servidores Temporários, Unidades e Lotações, garantindo que as rotas funcionem conforme esperado.

- **Testes de Upload:**  
  Verificação do fluxo de upload de fotografias para o Min.IO, assegurando que os arquivos sejam corretamente enviados e retornem os links temporários de acesso.

- **Testes de Autenticação:**  
  Testes referentes à geração e validação dos tokens utilizando o Sanctum, garantindo que o acesso às rotas protegidas seja devidamente restrito.

---

## Observações Finais

- O **Min.IO** é compatível com a API do Amazon S3, o que facilita futuras migrações para outras soluções de armazenamento, alterando apenas as variáveis de ambiente.
- Mantenha sempre as chaves e credenciais em ambiente seguro, evitando expor informações sensíveis publicamente.
- Para dúvidas ou reportar problemas, consulte a documentação ou abra uma issue no repositório.

---

Desfrute da Seplag API e bons desenvolvimentos!
