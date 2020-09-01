## Build Env
build:
	touch .env.local
	cat .env.default .env.local > .env
    
## Install PHP libs
install:
	composer self-update
	composer install --prefer-dist --no-scripts
