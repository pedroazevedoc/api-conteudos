# API Conteúdos

Esse projeto é uma API REST para gerenciamento de conteúdos, incluindo posts, vídeos e comentários. Desenvolvida com **Laravel 13**, a API segue as melhores práticas de desenvolvimento RESTful, garantindo segurança, escalabilidade e facilidade de manutenção. A autenticação é implementada usando **Laravel Sanctum**, proporcionando uma experiência segura para os usuários. Além disso, a documentação da API é gerada automaticamente com **Scramble**, facilitando a compreensão e o uso dos endpoints disponíveis.

<p align="center">
  <a href="#tecnologias"><strong>Tecnologias</strong></a> •
  <a href="#requisitos"><strong>Requisitos</strong></a> •
  <a href="#instalação"><strong>Instalação</strong></a> •
  <a href="#como-usar"><strong>Como Usar</strong></a>
</p>

---

## Tecnologias

| Tecnologia | Versão |
|----------- |--------|
| **PHP**    | 8.3+ |
| **Laravel** | 13.0 |
| **Laravel Sanctum** | 4.0 |
| **MySQL** | 8.0 |
| **Apache** | 2.4 |
| **Docker** | Latest |
| **Scramble** | 0.13.17 |

---

## Requisitos

- **Docker** 20.0+
- **Docker Compose** 1.29+

---

## Instalação

### Passo 1: Clonar Repositório

```bash
git clone https://github.com/pedroazevedoc/api-conteudos.git
cd api-conteudos
```

### Passo 2: Copiar Arquivo de Ambiente

```bash
cp .env.example .env
```

### Passo 3: Gerar Chave da Aplicação (Necessário)

```bash
# Iniciar containers para acessar o terminal
make up

# Gerar chave da aplicação
make key
```

### Passo 4: Construir e Iniciar Containers

```bash
# Construir images
make build

# Iniciar containers em background
make up

# Verificar status
docker compose ps
```

**Esperado:** 3 containers rodando:
- `conteudos-api` (Laravel API)
- `conteudos-db` (MySQL)
- `conteudos-phpmyadmin` (Gerenciador DB)

### Passo 5: Instalar Dependências

```bash
# Instalar dependências com Composer
make install
```

### Passo 6: Executar Migrações

```bash
make migrate
```

### Passo 7: Executar Seeders

```bash
make seed
```
Isso irá popular o banco com os usuários padrão.

**API rodando em `http://localhost:8000`**

---

## Como Usar

### Acessar a Documentação da API (Scramble)

```bash
http://localhost:8000/docs/api
```

### Acessar o Banco de Dados

**PhpMyAdmin** está disponível em: `http://localhost:8080`

- **Host:** `db`
- **Usuário:** `user` (padrão)
- **Senha:** `password` (padrão)

### Importar Coleção Postman
Importe a coleção `postman_collection.json` para testar os endpoints da API.

### Parar os Containers

```bash
make down
```

### Reiniciar os Containers

```bash
make restart
```

### Ver os Logs da Aplicação

```bash
make logs
```

### Acessar o Terminal da Aplicação

```bash
make bash
```