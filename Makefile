up:
	@echo '************  Create Novesfora network ************'
	@echo '*'
	@echo '*'
	docker network inspect novesfora_network >/dev/null 2>&1 || docker network create novesfora_network

	@echo '************  Waking UP Containers ************'
	@echo '*'
	@echo '*'
	docker-compose up -d

	@echo '*'
	@echo '*'
	@echo '************  Starting API ************'
	@echo '*'
	@echo '*'
	@echo '************  Installing symfony ************'
	docker exec -it novesfora-php composer install
	@echo '*'
	@echo '*'
	@echo '************  Configuring env ************'
	@echo '*'
	@echo '*'
	cp .env.dist .env
