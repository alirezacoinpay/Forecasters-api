# Test Configuration

This project supports running tests with a MySQL database using a `.env.testing` file.

## Setup

1. Create a `.env.testing` file in the project root with your MySQL test database configuration:

```env
APP_ENV=testing
APP_KEY=base64:your-test-key-here
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_test_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

2. Make sure your test database exists and is empty (or will be migrated during tests).

3. Run tests:

```bash
php artisan test
# or
./vendor/bin/pest
```

## How It Works

- When `APP_ENV=testing` is set (as in `phpunit.xml`), Laravel automatically loads `.env.testing` if it exists
- The `phpunit.xml` file no longer hardcodes database connection settings, allowing `.env.testing` to override them
- The `TestCase` class automatically runs migrations on the configured test database before tests
- If using MySQL, all migrations will work properly (including `->change()` and `->renameColumn()`)
- If using SQLite (fallback), some MySQL-specific migrations may fail but base tables will be created

## Database Connection Priority

1. Values from `.env.testing` (if `APP_ENV=testing`)
2. Values from `phpunit.xml` `<env>` tags
3. Values from `.env` file
4. Default values from config files

## Notes

- The test database should be separate from your development database
- Migrations run once before all tests (not before each test)
- All tests use mocked repositories, so actual database queries are minimal
- Queues and buses are faked in all tests

