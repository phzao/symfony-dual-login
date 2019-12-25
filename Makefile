up:
	@echo '************  Create My network ************'
	@echo '*'
	@echo '*'
	docker network inspect my_network >/dev/null 2>&1 || docker network create my_network

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
	docker exec -it my-php composer install
	@echo '*'
	@echo '*'
	@echo '************  Configuring env ************'
	@echo '*'
	@echo '*'
	cp .env.dist .env
	@echo '*'
	@echo '*'
	@echo '************  Running tests  ************'
	docker exec -it my-php php bin/phpunit
