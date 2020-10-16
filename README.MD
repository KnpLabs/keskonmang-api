# Keskonmange

## Setup on local environment

There is a `.env.dist` at the root directory that can be clone into `.env`.
It's up to you to edit these env vars according to your local environment.

**Note**: the `make dev` command will copy the `.env.dist` file into `.env` if it does not exist.

### Installation

```sh
$ make dev
```

This command will build the docker containers and update dependency.

### Services

- API: [http://127.0.0.1:8081](http://127.0.0.1:8081)

## Make commands

| Command              | Description                                                                            |
| -------------------- | -------------------------------------------------------------------------------------- |
| `dev`                | Build and start the stack. (Combination of `cp-env`, `build`, `install-deps`, `start`) |
| `start`              | Start the docker-compose stack.                                                        |
| `stop`               | Stop the docker-compose stack.                                                         |
| `install-deps`       | Install dependencies                                                                   |

## Make commands to run linters

| Command              | Description                             |
| -------------------- | ----------------------------------------|
| `lint-compose-files` | Run linter for compose files            |
