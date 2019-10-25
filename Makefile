.PHONY: init fixtures codacy codecov
init: qa

fixtures:
	php bin/console doctrine:schema:drop --force
	php bin/console doctrine:schema:update --force
	php bin/console doctrine:fixtures:load -n

.PHONY: cc-dev cc-prod cc-test purge
cc: cc-dev cc-prod cc-test

cc-dev:
	php bin/console cache:clear --env=dev

cc-prod:
	php bin/console cache:clear --env=prod --no-debug

cc-test:
	php bin/console cache:clear --env=test

purge:
	rm -rf var/cache/* var/logs/*

.PHONY: test test-unit test-integration test-functional
test:
	php bin/phpunit

test-unit:
	php bin/phpunit --stop-on-failure --group unit

test-integration:
	php bin/phpunit --stop-on-failure --group integration

test-functional:
	php bin/phpunit --stop-on-failure --group functional

.PHONY: coverage coverage-unit coverage-integration coverage-functional
coverage:
	php bin/phpunit --coverage-text --coverage-html public/coverage --coverage-clover 'coverage.xml'

coverage-unit:
	php bin/phpunit --group unit --coverage-html public/coverage/unit

coverage-integration:
	php bin/phpunit --group integration --coverage-html public/coverage/integration

coverage-functional:
	php bin/phpunit --group functional --coverage-html public/coverage/functional

coverage-temp:
	php bin/phpunit tests/Integration/Service --coverage-html public/coverage

.PHONY: external codacy codecov
external: codacy codecov

codacy:
	php codacy-coverage.phar phpunit docs/coverage/clover

codecov:
	bash <(curl -s https://codecov.io/bash)

qa: qa-lint qa-phpcs qa-phpcsf qa-phpstan qa-phpmd qa-phpcpd

qa-lint:
	./vendor/bin/phplint src/ tests/

qa-phpcs:
	./vendor/bin/phpcs src/ tests/ -p --colors --cache --standard=PSR1,PSR2 --report-source --report-summary --report-code=reports/phpcs.txt --report-diff=reports/phpcs.diff.txt

qa-phpcbf:
	./vendor/bin/phpcbf src/ tests/ --standard=PSR1,PSR2

qa-phpcs-a:
	./vendor/bin/phpcs src/ tests/ -a --colors --cache --standard=PSR1,PSR2

qa-phpcsf:
	./vendor/bin/php-cs-fixer fix src/ -v --rules=@PSR1,@PSR2,@Symfony,@PhpCsFixer,@DoctrineAnnotation --allow-risky=yes --dry-run
	./vendor/bin/php-cs-fixer fix tests/ -v --rules=@PSR1,@PSR2,@Symfony,@PhpCsFixer,@DoctrineAnnotation --allow-risky=yes --dry-run

qa-phpcsf-force:
	./vendor/bin/php-cs-fixer fix src/ -v --rules=@PSR1,@PSR2,@Symfony,@PhpCsFixer,@DoctrineAnnotation --allow-risky=yes
	./vendor/bin/php-cs-fixer fix tests/ -v --rules=@PSR1,@PSR2,@Symfony,@PhpCsFixer,@DoctrineAnnotation --allow-risky=yes

qa-phpcsf-diff:
	./vendor/bin/php-cs-fixer fix src/ -v --diff --rules=@PSR1,@PSR2,@Symfony,@PhpCsFixer,@DoctrineAnnotation --allow-risky=yes --dry-run
	./vendor/bin/php-cs-fixer fix tests/ -v --diff --rules=@PSR1,@PSR2,@Symfony,@PhpCsFixer,@DoctrineAnnotation --allow-risky=yes --dry-run

qa-phpcsf-report:
	./vendor/bin/php-cs-fixer fix src/ --dry-run -v --diff --rules=@PSR1,@PSR2,@Symfony,@PhpCsFixer,@DoctrineAnnotation --allow-risky=yes > reports/phpcsf.src.txt
	./vendor/bin/php-cs-fixer fix tests/ --dry-run -v --diff --rules=@PSR1,@PSR2,@Symfony,@PhpCsFixer,@DoctrineAnnotation --alow-risky=yes > reports/phpcsf.tests.txt

qa-phpstan:
	./vendor/bin/phpstan analyse src/ tests/ --level 6

qa-phpmd:
	./vendor/bin/phpmd src/ text phpmd.xml
	./vendor/bin/phpmd tests/ text phpmd.xml

qa-phpcpd:
	./vendor/bin/phpcpd src/ tests/ --min-lines 5 --min-tokens 70

make push:
	git add *
	git commit -m 'init'
	git push origin master

qa2:
	#./vendor/bin/php-cs-fixer fix src/ --verbose --diff
    #./vendor/bin/php-cs-fixer fix tests/ --verbose --diff
    #./vendor/bin/phpmd src/ text cleancode,codesize,controversial,design,naming,unusedcode
    #./vendor/bin/phpmd test/ text cleancode,codesize,controversial,design,naming,unusedcode




