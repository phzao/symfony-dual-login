up:
	@echo '************  Create Sym network ************'
	@echo '*'
	@echo '*'
	docker network inspect sym_network >/dev/null 2>&1 || docker network create sym_network

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
	docker exec -it sym-php composer install
	@echo '*'
	@echo '*'
	@echo '************  Configuring env ************'
	@echo '*'
	@echo '*'
	cp .env.dist .env
	@echo '*'
	@echo '*'
	@echo '************  Running migrations symfony ************'
	docker exec -it sym-php composer install
	@echo '*'
	@echo '*'
	@echo '************  Configuring env ************'
	@echo '*'
	@echo '*'
	cp .env.dist .env
