.PHONY: up down build restart logs bash migrate seed key install

up: ## Inicia os containers
	sudo docker compose up -d

down: ## Para os containers
	sudo docker compose down

build: ## Faz o build das imagens
	sudo docker compose build

restart: ## Reinicia os containers
	sudo docker compose restart

logs: ## Mostra os logs da aplicação
	sudo docker compose logs -f app

bash: ## Entra no shell do container
	sudo docker compose exec app bash

migrate: ## Executa as migrações
	sudo docker compose exec app php artisan migrate

seed: ## Executa os seeders
	sudo docker compose exec app php artisan db:seed

key: ## Gera a chave da aplicação
	sudo docker compose exec app php artisan key:generate

install: ## Instala as dependências com Composer
	sudo docker compose exec app composer install