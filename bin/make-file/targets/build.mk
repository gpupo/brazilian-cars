## Build Env
build:
	touch .env.local
	cat .env.default .env.local > .env
