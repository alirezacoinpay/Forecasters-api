# Test Quality Checklist

## ✅ Mocking Verification

### Database Operations
- ✅ All tests use `factory()->make()` instead of `factory()->create()` to avoid database inserts
- ✅ All repository calls are mocked in feature tests
- ✅ No `RefreshDatabase`, `DatabaseTransactions`, or `DatabaseMigrations` traits used

### External Services
- ✅ SMS service (SmsService, MelliPayamakProvider) - All mocked
- ✅ OpenAI service - Mocked in tests
- ✅ HTTP client - Mocked using `Http::fake()`
- ✅ Queue jobs - Mocked using `Bus::fake()`
- ✅ Cache - Used in-memory array driver for testing

### Repository Pattern
- ✅ All feature tests mock repository interfaces
- ✅ Repository methods are stubbed with expected return values
- ✅ No actual database queries in feature tests

## ✅ Test Structure

### Naming Conventions
- ✅ All tests use descriptive names following pattern: `it_does_something` or `test_it_does_something`
- ✅ Test files follow naming convention: `*Test.php`
- ✅ Test methods clearly describe what is being tested

### Arrange-Act-Assert Pattern
- ✅ All tests follow AAA pattern:
  - **Arrange**: Set up mocks and test data
  - **Act**: Execute the code under test
  - **Assert**: Verify expected outcomes

### Test Organization
- ✅ Tests organized by component type (Unit/Feature)
- ✅ Unit tests grouped by domain (Repositories, Services, Models, etc.)
- ✅ Feature tests grouped by feature area (Auth, Questions, Comments, etc.)

## ✅ Test Coverage

### Unit Tests
- ✅ Repositories: All repository methods tested
- ✅ Services: All service methods tested
- ✅ Models: All model relationships tested
- ✅ Jobs: All job handlers tested
- ✅ Observers: All observer events tested
- ✅ Middleware: All middleware tested
- ✅ Enums: All enum methods tested
- ✅ Helpers: All helper methods tested
- ✅ Traits: All trait methods tested

### Feature Tests
- ✅ Authentication endpoints
- ✅ Question endpoints
- ✅ Comment endpoints (including likes)
- ✅ Prediction endpoints (including likes)
- ✅ Category/Topic/Tag endpoints
- ✅ Feed and Search endpoints
- ✅ User management endpoints
- ✅ Request validation
- ✅ Resource transformation
- ✅ Middleware integration

## ✅ Best Practices

### Test Isolation
- ✅ Each test is independent
- ✅ Tests don't depend on execution order
- ✅ Mocks are reset between tests

### Test Data
- ✅ Model factories used for generating test data
- ✅ Factories use `make()` to avoid database persistence
- ✅ Test data is minimal and focused

### Assertions
- ✅ Clear and specific assertions
- ✅ Both positive and negative test cases
- ✅ Edge cases covered where applicable

## 📊 Coverage Configuration

Coverage reporting configured in `phpunit.xml`:
- HTML report: `coverage/html/`
- Text report: `coverage/coverage.txt`
- Excludes: Console, Exceptions, Middleware, Kernel, Service Providers

## 🚀 Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run specific test file
php artisan test tests/Unit/Repositories/BaseRepositoryTest.php
```

