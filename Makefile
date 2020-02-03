PLUGIN_NAME=wordpress-remember-the-wordpress

all:
	mkdir -p tmp/
	cp ./*.php ./tmp/

build:
	mv ./tmp ./${PLUGIN_NAME}
	zip -r ${PLUGIN_NAME}.zip ./${PLUGIN_NAME}
	mv ./${PLUGIN_NAME} ./tmp

test:
	./vendor/bin/phpunit

clean:
	rm -rf ./tmp

