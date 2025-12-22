# Quick Start - Testing Authentication

## Run Tests in 3 Steps

### Step 1: Start the Server
```bash
composer dev
```
Or manually:
```bash
cd public
php -S localhost:8000
```

### Step 2: Run Tests (in a new terminal)
```bash
composer test-auth
```
Or directly:
```bash
php tests/test_auth.php
```

### Step 3: Check Results
Look for:
- ✓ Green checkmarks = Tests passed
- ✗ Red X marks = Tests failed
- Summary at the bottom

## What Gets Tested

✅ **Registration**
- New user registration
- Duplicate username rejection

✅ **Login**
- Successful login
- Wrong password rejection

✅ **Session Management**
- Protected page access before login
- Protected page access after login
- Protected page access after logout

✅ **Security**
- CSRF token validation
- Invalid token rejection

## Troubleshooting

**"Could not retrieve CSRF token"**
→ Make sure server is running: `composer dev`

**"Connection refused"**
→ Check the port (default: 8000)
→ Update `$base_url` in `test_auth.php` if needed

**"Tests failing"**
→ Check database connection
→ Verify tables exist
→ Check PHP error logs

## Example Output

```
============================================================
FOOTPOWER AUTHENTICATION SYSTEM TEST
Base URL: http://localhost:8000
Test Username: test_user_1704067200
============================================================

============================================================
TEST: Get CSRF Token from Login Page
============================================================
✓ PASSED

============================================================
TEST: User Registration
============================================================
✓ PASSED

============================================================
TEST SUMMARY
============================================================
Total Tests: 11
Passed: 11
Failed: 0
```

## Manual Testing Alternative

If you prefer manual testing:
1. Open browser: `http://localhost:8000/register.php`
2. Create account
3. Login at: `http://localhost:8000/login.php`
4. Access: `http://localhost:8000/index.php`
5. Logout and verify redirect

