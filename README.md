# **Seplag API**

## **Descrição Geral**

A **Seplag API** é uma solução back-end robusta desenvolvida em **Laravel**, que oferece recursos completos para o gerenciamento de servidores efetivos, temporários, unidades administrativas e operações relacionadas, como lotação e upload de fotografias. O sistema utiliza autenticação via **Laravel Sanctum** e segue práticas modernas de segurança e desenvolvimento.

---

# **Índice**

1. [Descrição Geral](#descrição-geral)
2. [Requisitos do Sistema](#requisitos-do-sistema)
3. [Configuração do Ambiente](#configuração-do-ambiente)
    - [Com Docker Compose](#com-docker-compose)
    - [Sem Docker](#sem-docker)
4. [Documentação Interativa da API (Swagger)](#documentação-interativa-da-api-swagger)
5. [Testes Automatizados](#testes-automatizados)
6. [Configurações para Diferentes Ambientes](#configurações-para-diferentes-ambientes)
7. [Changelog](#changelog)
8. [Observações Importantes](#observações-importantes)

---

## **Dados da Inscrição**

- **Concurso**: SEPLAG - PSS 02/2025/SEPLAG (Analista de TI - Perfis Júnior, Pleno e Sênior)
- **Nome**: Raphael Rychard Soares de Almeida Souza
- **E-mail**: raph.rych@gmail.com
- **Número da inscrição**: 8463
- **Perfil**: Desenvolvedor PHP - Júnior

---

## **Requisitos do Sistema**

Antes de configurar e executar o projeto, certifique-se de que as dependências necessárias estão instaladas:

### **Dependências Principais**

- **PHP**: Versão mínima **8.3**
- **Composer**: Para gerenciamento de pacotes PHP
- **Banco de Dados**: **PostgreSQL** (ou compatível)
- **Laravel**: Versão **^12.0**
- **Node.js**: Versão recomendada **18+** (necessário para scripts com `npx`, como `concurrently`)
- **Docker**: Para configuração rápida do ambiente
- **Extensões PHP necessárias**:
    - `pdo_pgsql`
    - `mbstring`
    - `openssl`
    - `fileinfo`
    - `json`
    - `xml`

---

## **Configuração do Ambiente**

O ambiente deve ser configurado com Docker Compose, pois a aplicação depende do **MinIO** e de outros serviços. Siga os passos:

### **Com Docker Compose**

1. Clone o repositório:
   ```bash
   git clone https://github.com/RaphaelRychard/api-seplag
   cd api-seplag
   ```

2. Copie o `.env` de exemplo:
   ```bash
   cp .env.example .env
   ```

3. Inicie os serviços:
   ```bash
   docker-compose up -d
   ```

   > Isso configura os containers da aplicação, PostgreSQL e MinIO.

4. Configure o bucket no MinIO:
    - Acesse [http://localhost:9001](http://localhost:9001) (credenciais no `.env`).
    - Crie um bucket com o nome da variável `AWS_BUCKET`.

5. Rode as migrações:
   ```bash
   php artisan migrate
   ```

6. Gere a chave da aplicação:
   ```bash
   php artisan key:generate
   ```

7. Inicie o servidor local:
   ```bash
   php artisan serve
   ```

> A API estará disponível em: [http://localhost:8000](http://localhost:8000)

---

## 📚 **Documentação Interativa da API (Swagger)**

A documentação está disponível em:

🔗 [http://localhost:8000/docs/api](http://localhost:8000/docs/api)

> Nela, você pode testar requisições, visualizar endpoints, schemas e exemplos de respostas.

---

## **Testes Automatizados**

A API tem suporte a testes com **Pest**.

### Como executar:

1. Configure o banco de testes no `.env.testing`
2. Rode as migrações para o ambiente de testes:
   ```bash
   php artisan migrate --env=testing
   ```

3. Execute os testes:
   ```bash
   php artisan test
   ```

---

## **Configurações para Diferentes Ambientes**

### Exemplos de arquivos `.env`

#### `.env.development`
```ini
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=dev_db
DB_USERNAME=usuario_dev
DB_PASSWORD=senha_dev

FILESYSTEM_DISK=minio
MINIO_ENDPOINT=http://localhost:9000
MINIO_KEY=chave_dev
MINIO_SECRET=segredo_dev
MINIO_REGION=us-east-1
MINIO_BUCKET=my-bucket
```

#### `.env.production`
```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://minha-api.com

DB_CONNECTION=pgsql
DB_HOST=prod-db-host
DB_PORT=5432
DB_DATABASE=prod_db
DB_USERNAME=usuario_prod
DB_PASSWORD=senha_prod

FILESYSTEM_DISK=minio
MINIO_ENDPOINT=https://prod-minio-endpoint
MINIO_KEY=chave_prod
MINIO_SECRET=segredo_prod
MINIO_REGION=us-east-1
MINIO_BUCKET=prod-bucket
```

#### `.env.testing`
```ini
APP_ENV=testing
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=test_db
DB_USERNAME=usuario_test
DB_PASSWORD=senha_test

FILESYSTEM_DISK=minio
MINIO_ENDPOINT=http://localhost:9000
MINIO_KEY=chave_test
MINIO_SECRET=segredo_test
MINIO_REGION=us-east-1
MINIO_BUCKET=my-bucket
```

### Como utilizar

- **Desenvolvimento**:
  ```bash
  cp .env.development .env
  ```

- **Produção**:
  ```bash
  cp .env.production .env
  ```

- **Testes E2E**:
  ```bash
  php artisan migrate --env=testing
  php artisan test
  ```

---

## **Changelog**

### Versão 1.0.0

- Autenticação com Laravel Sanctum
- CRUD completo para:
    - Servidores efetivos
    - Servidores temporários
    - Unidades
    - Lotações
- Upload de fotografias
- Ambiente Docker
- Testes com Pest
