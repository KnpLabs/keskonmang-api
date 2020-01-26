REMOTE ?= deploy@keskonmange.knpnet.net
REMOTE_PATH ?= ~/apps/keskonmange.api
STAGE ?= dev

# Expose env vars from .env to this Makefile
-include .env

.PHONY: .ensure-stage-exists
.ensure-stage-exists:
ifeq (,$(wildcard ./docker-compose.$(STAGE).yml))
	@echo "Env $(STAGE) not supported."
	@exit 1
endif

.PHONY: .validate-tag
.validate-tag:
ifneq ($(STAGE),dev)
ifeq ($(IMAGE_TAG),)
	@echo "You can't build, push or deploy to production without an IMAGE_TAG.\n"
	@exit 1
endif
endif

.PHONY: .remote-edit-env
.remote-edit-env:
	ssh -t ${REMOTE} 'vim ${REMOTE_PATH}/.env'

.PHONY: cp-env
cp-env:
	cp .env.dist .env

.PHONY:
install-deps:
	docker-compose -f ./docker-compose.$(STAGE).yml run --rm php composer install

.PHONY:
start:
	docker-compose -f ./docker-compose.$(STAGE).yml up

.PHONY: build
build: .ensure-stage-exists .validate-tag
	docker-compose -f ./docker-compose.$(STAGE).yml build

.PHONY: push
push: .ensure-stage-exists .validate-tag
ifeq ($(STAGE),dev)
	@echo "You can't push dev env to remote repo.\n"
	@exit 1
endif
	docker-compose -f ./docker-compose.$(STAGE).yml push

.PHONY: remote-deploy
remote-deploy: .ensure-stage-exists .validate-tag .remote-edit-env
	scp ./docker-compose.$(STAGE).yml ${REMOTE}:${REMOTE_PATH}/docker-compose.$(STAGE).yml
	ssh -t ${REMOTE} '\
		cd ${REMOTE_PATH} && \
		source .env && \
		export IMAGE_TAG=$(IMAGE_TAG) && \
		docker-compose -f ./docker-compose.${STAGE}.yml pull --include-deps && \
		docker-compose -f ./docker-compose.$(STAGE).yml up -d --no-build --remove-orphans && \
		docker-compose -f ./docker-compose.$(STAGE).yml ps'
