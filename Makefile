migration generate:
	./vendor/bin/doctrine-migrations migrations:generate --configuration=/app/src/migrations.php
migration migrate:
	./vendor/bin/doctrine-migrations  migrations:migrate --configuration=/app/src/migrations.php --db-configuration=/app/src/migrations-db.php