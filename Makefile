.PHONY: up down build restart logs bash migrate seed

up: ## Inicia os containers
	docker compose up -d

down: ## Para os containers
	docker compose down

build: ## Faz o build das imagens
	docker compose build

restart: ## Reinicia os containers
	docker compose restart

logs: ## Mostra os logs da aplicação
	docker compose logs -f app

bash: ## Entra no shell do container
	docker compose exec app bash

migrate: ## Executa as migrações
	docker compose exec app php artisan migrate

seed: ## Executa os seeders
	docker compose exec app php artisan db:seed

key: ## Gera a chave da aplicação
	docker compose exec app php artisan key:generate

install: ## Instala as dependências com Composer
	docker compose exec app composer install