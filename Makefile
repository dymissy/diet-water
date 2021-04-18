build:
	docker-compose build

up:
	docker-compose up -d

ssh:
	docker exec -u www-data -it dietwater_app /bin/bash

ssh-root:
	docker exec -it dietwater_app /bin/bash
