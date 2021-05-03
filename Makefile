build:
	docker-compose build

up:
	docker-compose up -d

ssh:
	docker-compose exec -u www-data app sh

ssh-root:
	docker-compose exec app sh
