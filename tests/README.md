# Authentication System Tests

This directory contains test scripts for the FootPower authentication system.

## Test Scripts

### 1. `test_auth.php` (Recommended)
PHP-based test script with comprehensive testing and colored output.

**Usage:**
```bash
# Make sure your server is running (e.g., php -S localhost:8000 -t public)
php tests/test_auth.php
```

**Features:**
- Tests all authentication flows
- Colored terminal output
- Detailed test results
- CSRF token handling
- Session management
- Cookie handling

**Tests Included:**
1. Get CSRF Token
2. User Registration
3. Duplicate Username Rejection
4. User Login
5. Wrong Password Rejection
6. Protected Page Access (before login)
7. Protected Page Access (after login)
8. User Logout
9. Protected Page Access (after logout)
10. Invalid CSRF Token Rejection

### 2. `test_auth.sh`
Shell script version (simpler, fewer tests).

**Usage:**
```bash
chmod +x tests/test_auth.sh
./tests/test_auth.sh
```

## Configuration

Before running tests, update the `$base_url` variable in `test_auth.php`:

```php
$base_url = 'http://localhost:8000'; // Adjust to your server URL
```

Or update `BASE_URL` in `test_auth.sh`:

```bash
BASE_URL="http://localhost:8000"
```

## Running Tests

### Prerequisites
1. Start your PHP development server:
   ```bash
   cd public
   php -S localhost:8000
   ```
   Or use the Composer script:
   ```bash
   composer dev
   ```

2. Make sure the database is set up and accessible.

### Run PHP Tests
```bash
php tests/test_auth.php
```

### Run Shell Tests
```bash
bash tests/test_auth.sh
```

## Expected Output

The PHP test script will show:
- ✓ Green checkmarks for passed tests
- ✗ Red X marks for failed tests
- Detailed JSON responses
- Summary at the end

Example:
```
============================================================
FOOTPOWER AUTHENTICATION SYSTEM TEST
Base URL: http://localhost:8000
Test Username: test_user_1234567890
============================================================

============================================================
TEST: Get CSRF Token from Login Page
============================================================
✓ PASSED
  Data: {
    "token": "73657373696f6e267374726f6e67..."
}

============================================================
TEST: User Registration
============================================================
✓ PASSED
  Data: {
    "status": "success",
    "message": "Registration successful!",
    "user_id": 5
  }
...
```

## Troubleshooting

### "Could not retrieve CSRF token"
- Make sure the server is running
- Check the `$base_url` is correct
- Verify `login.php` is accessible

### "Connection refused"
- Start the PHP development server
- Check the port number matches

### "Tests failing"
- Check database connection
- Verify database tables exist
- Check PHP error logs
- Ensure session storage is writable

## Adding New Tests

To add a new test, add a `run_test()` call in `test_auth.php`:

```php
run_test('My New Test', function() use ($base_url, $csrf_token) {
    $response = make_request($base_url . '/handlers/user_handler.php', [
        'user-verb' => 'some-action',
        'token' => $csrf_token,
        // ... other data
    ]);
    
    if ($response['json'] && $response['json']['status'] === 'success') {
        return ['success' => true, 'message' => 'Test passed'];
    }
    
    return ['success' => false, 'message' => 'Test failed'];
});
```

## Notes

- Tests use temporary cookie files for session management
- Each test run uses a unique username (timestamp-based)
- Tests clean up after themselves
- CSRF tokens are retrieved fresh for each request when needed

