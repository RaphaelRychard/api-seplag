# Seplag API

## Descrição Geral

A **Seplag API** é uma solução back-end desenvolvida em **Laravel**, que oferece recursos para gerenciamento de servidores efetivos, temporários, unidades administrativas e operações como lotação e upload de fotografias.

Utiliza autenticação via **Laravel Sanctum**, com arquitetura segura e moderna.  
Projeto construído como parte da inscrição para o concurso **SEPLAG - PSS 02/2025/SEPLAG**.

---

## Dados da Inscrição

- **Concurso**: SEPLAG - PSS 02/2025/SEPLAG (Analista de TI - Perfis Júnior, Pleno e Sênior)
- **Nome**: Raphael Rychard Soares de Almeida Souza
- **E-mail**: raph.rych@gmail.com
- **Número da inscrição**: 8463
- **Perfil**: Desenvolvedor PHP - Júnior

---

## Índice

1. [Requisitos do Sistema](#requisitos-do-sistema)
2. [Configuração do Ambiente](#configuração-do-ambiente)
    - [Com Docker Compose](#com-docker-compose)
3. [Configuração de Ambiente Laravel](#configuração-de-ambiente-laravel)
4. [Documentação da API (Swagger)](#documentação-da-api-swagger)
5. [Testes Automatizados](#testes-automatizados)
6. [Changelog](#changelog)

---

## Requisitos do Sistema

### Principais Dependências

- **PHP**: 8.3+
- **Laravel**: ^12.0
- **Composer**: Gerenciador de pacotes PHP
- **PostgreSQL**: Banco de dados principal
- **Node.js**: 18+ (para comandos com `npx`)
- **Docker + Docker Compose**: Ambiente completo

### Extensões PHP Necessárias

- `pdo_pgsql`
- `mbstring`
- `openssl`
- `fileinfo`
- `json`
- `xml`

---

## Configuração do Ambiente

### Com Docker Compose

Siga os passos para subir o projeto com Docker:

```bash
git clone https://github.com/RaphaelRychard/api-seplag
cd api-seplag

# Sobe os containers e constrói a imagem
docker compose up -d --build

# Instala dependências
docker compose exec php composer install

# Copia env e configurações iniciais
docker compose exec php cp .env.example .env
docker compose exec php php artisan key:generate
docker compose exec php php artisan config:clear
docker compose exec php php artisan cache:clear

# Migração do banco
docker compose exec php php artisan migrate
```

Acesse a interface do MinIO (armazenamento de fotos):

- http://localhost:9001
- Use as credenciais definidas no `.env`
- Crie o bucket com o nome configurado em `seplag` se não existir

---

## Configuração de Ambiente Laravel

O Laravel utiliza um único arquivo `.env` com todas as variáveis de ambiente.

Mantenha um `.env.example` sempre atualizado no repositório para servir de base para todos os ambientes.

### Exemplo de `.env.example`

```ini
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_KEY=

DB_CONNECTION=pgsql
DB_HOST=pg
DB_PORT=5432
DB_DATABASE=seplag
DB_USERNAME=docker
DB_PASSWORD=docker

FILESYSTEM_DISK=minio

# MinIO Config
MINIO_ENDPOINT=http://minio:9000
MINIO_KEY=myadmin
MINIO_SECRET=mysecurepassword
MINIO_REGION=us-east-1
MINIO_BUCKET=seplag
```

###  Desenvolvimento

```bash
cp .env.example .env
php artisan key:generate
```

### Produção

```bash
cp .env.example .env
# Edite os valores com os dados de produção
php artisan config:cache
```

> Defina `APP_ENV=production` e `APP_DEBUG=false`

### Testes

Laravel usa automaticamente `.env.testing`, se existir:

```bash
docker compose exec php php artisan migrate --env=testing
docker compose exec php php artisan test
```

Ou sobrescreva temporariamente o `.env` para testes.

Sempre que editar o `.env`, rode:

```bash
php artisan config:clear
```

---

## Documentação da API (Swagger)

Documentação interativa disponível em:

[http://localhost:8000/docs/api](http://localhost:8000/docs/api)

Nela é possível:

- Testar endpoints via interface
- Ver schemas de entrada e resposta
- Explorar toda a API de forma interativa

---

## Testes Automatizados

Os testes utilizam **PestPHP**.

### Comandos:

```bash
php artisan migrate --env=testing
php artisan test
```

> Certifique-se que o banco de dados de testes está configurado corretamente.

---

## Changelog

### Versão 1.0.0

- Autenticação com Laravel Sanctum
- CRUD completo para:
    - Servidores efetivos
    - Servidores temporários
    - Unidades administrativas
    - Lotações
- Upload de fotografias com MinIO
- Ambiente Docker configurado
- Testes com Pest

---

👉 Projeto mantido por Raphael Rychard • [raph.rych@gmail.com](mailto:raph.rych@gmail.com)
