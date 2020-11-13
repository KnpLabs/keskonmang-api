STAGE ?= dev
REMOTE ?= deploy@keskonmang.knpnet.net
REMOTE_PATH=/home/deploy/api
IMAGE_TAG ?= dev

.PHONY: dev
dev: cp-env build install-deps start

.PHONY: .remote-edit-env
.remote-edit-env:
	ssh -t ${REMOTE} 'vim ${REMOTE_PATH}/.env'

.PHONY: cp-env
cp-env:
	cp -n .env.dist .env

.PHONY: install-deps
install-deps:
	docker-compose run --rm php composer install --prefer-dist

.PHONY: start
start:
	docker-compose -f docker/$(STAGE).yml up -d --no-build --remove-orphans

.PHONY: stop
stop:
	docker-compose down

.PHONY: build
build: .ensure-stage-exists .validate-image-tag
	docker-compose -f docker/$(STAGE).yml build $(SERVICES)

.PHONY: lint-dockerfiles
lint-dockerfiles:
	@./bin/lint-dockerfiles

.PHONY: lint-compose-files
lint-compose-files:
	@for file in docker/*.yml; do \
		docker-compose -f $$file config >/dev/null; \
	done

.PHONY: lint-yaml
lint-yaml:
	docker-compose run --rm php bin/console lint:yaml src --parse-tags

.PHONY: push
push: .ensure-stage-exists .validate-image-tag
	docker-compose -f docker/$(STAGE).yml push

.PHONY: remote-deploy
remote-deploy: .ensure-stage-exists .validate-image-tag .remote-edit-env
	scp docker/$(STAGE).yml ${REMOTE}:${REMOTE_PATH}/docker/$(STAGE).yml
	ssh -t ${REMOTE} '\
		cd ${REMOTE_PATH} && \
		export IMAGE_TAG=$(IMAGE_TAG) && \
		sed -i "s/^IMAGE_TAG=.*$$/IMAGE_TAG=$(IMAGE_TAG)/" .env && \
		docker-compose -f docker/${STAGE}.yml pull --include-deps && \
		docker-compose -f docker/$(STAGE).yml up -d --no-build --remove-orphans && \
		docker-compose -f docker/$(STAGE).yml ps'

.PHONY: .ensure-stage-exists
.ensure-stage-exists:
ifeq (,$(wildcard docker/$(STAGE).yml))
	@echo "Env $(STAGE) not supported."
	@exit 1
endif

.PHONY: .validate-image-tag
.validate-image-tag:
ifneq ($(STAGE),dev)
ifeq ($(IMAGE_TAG),)
	@echo "You can't build, push or deploy to prod without an IMAGE_TAG.\n"
	@exit 1
endif
endif

.PHONY: phpspec
phpspec:
	docker-compose run --rm php vendor/bin/phpspec run -fpretty -v
