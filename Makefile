start:
	docker network create -d bridge net
	docker compose up -d
	docker exec symfony_api_php composer install
