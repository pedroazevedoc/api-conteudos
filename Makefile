.PHONY: up down build restart logs bash migrate seed

up: ## Inicia os containers
	docker-compose up -d
	@echo "$(GREEN)✓ Containers iniciados$(NC)"

down: ## Para os containers
	docker-compose down
	@echo "$(GREEN)✓ Containers parados$(NC)"

build: ## Faz o build das imagens
	docker-compose build
	@echo "$(GREEN)✓ Build completo$(NC)"

restart: ## Reinicia os containers
	docker-compose restart
	@echo "$(GREEN)✓ Containers reiniciados$(NC)"

logs: ## Mostra os logs da aplicação
	docker-compose logs -f app

bash: ## Entra no shell do container
	docker-compose exec app bash

migrate: ## Executa as migrações
	docker-compose exec app php artisan migrate

seed: ## Executa os seeders
	docker-compose exec app php artisan db:seed
