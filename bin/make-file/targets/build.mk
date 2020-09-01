## Build Env
build:
	touch .env.local
	cat .env.default .env.local > .env
    
## Install PHP libs
install:
	composer install --prefer-dist --ignore-platform-reqs --no-scripts
