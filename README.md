# Seplag API

## Descrição Geral

A **Seplag API** é uma solução back-end desenvolvida em **Laravel**, que oferece recursos para gerenciamento de servidores efetivos, temporários, unidades administrativas e operações como lotação e upload de fotografias.

Utiliza autenticação via **Laravel jwt-auth**, com arquitetura segura e moderna.  
A autenticação usa **JSON Web Tokens (JWT)** via `tymon/jwt-auth`.  
Ao fazer login, um `access token` é gerado com validade de 5 minutos.  
É possível renová-lo usando o endpoint de **refresh**.

> Utilize o header:  
> `Authorization: Bearer {token}`  
> em todas as requisições autenticadas.

> Para mais detalhes e testes interativos, acesse:  
> [http://localhost/docs/api](http://localhost/docs/api)

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

- [Seplag API](#seplag-api)
    - [Descrição Geral](#descrição-geral)
    - [Dados da Inscrição](#dados-da-inscrição)
    - [Índice](#índice)
    - [Requisitos do Sistema](#requisitos-do-sistema)
        - [Principais Dependências](#principais-dependências)
        - [Extensões PHP Necessárias](#extensões-php-necessárias)
    - [Configuração do Ambiente](#configuração-do-ambiente)
        - [Com Docker Compose](#com-docker-compose)
        - [Configuração do .env](#configuração-do-env)
        - [Acessos](#acessos)
        - [Parar os Serviços](#parar-os-serviços)
    - [Documentação da API (Swagger)](#documentação-da-api-swagger)
    - [Testes Automatizados](#testes-automatizados)
    - [Changelog](#changelog)

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

```bash
# Clone o repositório
git clone https://github.com/RaphaelRychard/api-seplag
cd api-seplag

# Copie o .env base e configure se necessário
cp .env.example .env

# Suba os containers (PHP, NGINX, Postgres, MinIO)
docker-compose up -d

# Instale as dependências PHP via container
docker exec -it php composer install

# Gere chave da aplicação e rode as migrations com seeds
docker exec -it php php artisan key:generate
docker exec -it php php artisan jwt:secret
docker exec -it php php artisan migrate:fresh --seed
```

### Configuração do .env

```env
DB_CONNECTION=pgsql
DB_HOST=pg
DB_PORT=5432
DB_DATABASE=seplag
DB_USERNAME=docker
DB_PASSWORD=docker

# Minio
MINIO_ENDPOINT=http://minio:9000
MINIO_KEY=myadmin
MINIO_SECRET=mysecurepassword
MINIO_REGION=us-east-1
MINIO_BUCKET=seplag

# JWT
JWT_SECRET=
JWT_TTL=5
JWT_REFRESH_TTL=60
```

> Após configurar o `.env`, execute o comando abaixo para gerar a chave secreta usada na assinatura dos tokens JWT:
> ```bash
> php artisan jwt:secret
> ```

### Acessos

- **Sistema**: http://localhost
- **MinIO Console**: http://localhost:9001
    - Usuário: `myadmin`
    - Senha: `mysecurepassword`

**Após acessar o MinIO, crie um bucket com o nome:**

```txt
seplag
```

### Parar os Serviços

```bash
docker-compose down
```

---

## Documentação da API (Swagger)

Documentação interativa disponível em:

[http://localhost/docs/api](http://localhost/docs/api)

Nela é possível:

- Testar endpoints via interface
- Ver schemas de entrada e resposta
- Explorar toda a API de forma interativa

---

## Testes Automatizados

Os testes utilizam **PestPHP**.

### Comandos:

> Laravel usará automaticamente um arquivo `.env.testing` se estiver presente.

```bash
docker exec -it php php artisan migrate:fresh --seed --env=testing
docker exec -it php php artisan test
```

Para garantir o uso correto de configurações após edições no `.env`:

```bash
docker exec -it php php artisan config:clear
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

