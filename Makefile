up:
	@echo '************  Create my_project network ************'
	@echo '*'
	@echo '*'
	docker network inspect my_project >/dev/null 2>&1 || docker network create my_project

	@echo '************  Waking UP Containers ************'
	@echo '*'
	@echo '*'
	docker-compose up -d

	@echo '************  Configuring env ************'
	@echo '*'
	@echo '*'
	cp .env.dist .env
	@echo '*'
	@echo '*'
	@echo '************  Starting API ************'
	@echo '*'
	@echo '*'
	@echo '************  Installing symfony ************'
	docker exec -it my-php composer install
	@echo '*'
	@echo '*'
	@echo '*'
	@echo '*'
	@echo '************  Creating Database  ************'
	docker exec -it my-php php bin/console doctrine:database:create
	@echo '*'
	@echo '*'
	@echo '************  Running migrates  ************'
	docker exec -it my-php php bin/console doctrine:migration:migrate
	@echo '*'
	@echo '*'
	@echo '************  Running tests  ************'
	docker exec -it my-php ./phpunit.sh
