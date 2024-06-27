## [🔍]  Run all PHP validations
validate: syntax stan cs cs-fixer md rector

## Validate security
audit:
	@$(call msg_validate, 'security check')
	@${CONTAINER_COMMAND} composer audit
	@echo ''

## [🔍] Validate the PHP syntax
syntax:
	$(call msg_validate,'syntax check')
	$(eval SYNTAX_DIRS=src tests)
	@echo "Checking syntax of $(shell find $(SYNTAX_DIRS) -name '*.php' | wc -l) files..."
	@find ${SYNTAX_DIRS} \
	  -name "*.php" -print0 \
	  | xargs -0 -n1 -P8 php -l 2>&1 >/dev/null \
	  && echo 'done' || echo "❌ error detected"

## [🔍] Validate the PHP code-style (phpcs)
cs:
	$(call msg_validate, 'phpcs') && \
	./vendor/bin/phpcs --standard=.dev/config/phpcs.xml --colors -ps

## [🔍] Validate the PHP code-style (php-cs-fixer)
cs-fixer:
	$(call msg_validate, 'php-cs-fixer') && \
	./vendor/bin/php-cs-fixer fix src tests \
	--dry-run --diff --allow-risky=yes --config=.dev/config/php-cs-fixer.php


## [🔨] Fix the PHP code-style (php-cs-fixer)
cs-fixer-fix:
	$(call msg_fix, 'php-cs-fixer') && \
	./vendor/bin/php-cs-fixer fix src tests \
	--diff --allow-risky=yes --config=.dev/config/php-cs-fixer.php

## [🔍] Validate the PHP code-style (phpmd)
md:
	$(call msg_validate, 'phpmd') && \
	./vendor/bin/phpmd src ansi .dev/config/phpmd.xml

## [🔍] Validate the PHP code statically (phpstan)
stan:
	$(call msg_validate, 'phpstan') && \
	./vendor/bin/phpstan analyse --memory-limit=2G --configuration=.dev/config/phpstan.neon

## [🔍] Validate the PHP code statically (rector)
rector:
	@$(call msg_validate, 'rector') && ${CONTAINER_COMMAND} \
	vendor/bin/rector process --config=.dev/config/rector.php --dry-run

## [🔨] Fix the PHP code statically (rector)
rector-fix:
	@$(call msg_fix, 'rector') && ${CONTAINER_COMMAND} \
	vendor/bin/rector process --config=.dev/config/rector.php

## [🔨] Fix the PHP code-style (phpcbf)
phpcbf-fix:
	$(call msg_fix, 'phpcbf') && \
	./vendor/bin/phpcbf src tests --colors

## [🔨] Fix the PHP code-style
fix: cs-fixer-fix phpcbf-fix
