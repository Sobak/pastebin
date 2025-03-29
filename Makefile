default: bash

bash:
	docker-compose exec --user=app app bash

node:
	docker-compose exec node bash

build-assets:
	docker-compose exec node bash -c "npm run dev"
