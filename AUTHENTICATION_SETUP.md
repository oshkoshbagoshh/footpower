# Authentication System Setup - FootPower ERP

## Overview
This document describes the authentication system that has been set up for the FootPower LAMP stack application.

## What Was Implemented

### 1. User Class (`src/classes/User.php`)
- **Fixed:** Removed duplicate class declarations and broken require statements
- **Added Authentication Methods:**
  - `registerUser($username, $password, $distance_unit)` - Register new users
  - `loginUser($username, $password)` - Authenticate users
  - `getUserLogId($user_id)` - Get user's log ID for activity tracking
  - `createUserLog($user_id)` - Create log entry for new users
- **Fixed CRUD Methods:**
  - `createUser()` - Now properly accepts parameters
  - `updateUser()` - Now properly accepts parameters and handles partial updates
  - `deleteUser()` - Now properly accepts parameters and checks for dependencies
- **Helper Methods:** All existing helper methods maintained and fixed

### 2. Login Page (`public/login.php`)
- Clean, accessible login form
- AJAX-based submission
- Client-side validation
- Error/success message display
- Redirects to home page on success
- Link to registration page

### 3. Registration Page (`public/register.php`)
- User registration form with:
  - Username (min 3 characters)
  - Password (min 4 characters)
  - Password confirmation
  - Distance unit preference (miles/km)
- Client-side password matching validation
- AJAX-based submission
- Auto-creates log entry for new users
- Redirects to login page on success

### 4. User Handler (`public/handlers/user_handler.php`)
- Processes login requests
- Processes registration requests
- Processes logout requests
- Sets session variables on successful login:
  - `$_SESSION['user_id']`
  - `$_SESSION['username']`
  - `$_SESSION['distance_unit']`
  - `$_SESSION['log_id']`
- CSRF token validation
- Returns JSON responses

### 5. Logout Page (`public/logout.php`)
- Simple logout handler
- Destroys session
- Redirects to login page

### 6. Initialization Updates (`src/common/initialization.php`)
- Added autoloader inclusion
- Improved session token management
- Automatic log_id retrieval for logged-in users
- Database connection before session operations

### 7. Class Autoloader (`src/common/autoload.php`)
- Simple autoloader for classes in `src/classes/`
- No PSR-4 namespaces required (keeps it simple for demo)
- Automatically loads classes when needed

### 8. Navigation Updates (`public/common/top.php`)
- Shows username and account links when logged in
- Shows login/register links when not logged in
- Logout link functionality

### 9. Protected Pages
- `public/index.php` - Now requires authentication
- Redirects to login page if not authenticated

### 10. CSS Styling (`public/css/styles.css`)
- Added styles for authentication forms
- Navigation styles
- Error/success message styles
- Responsive design maintained

## File Structure

```
footpower/
├── src/
│   ├── classes/
│   │   └── User.php          # User class with auth methods
│   └── common/
│       ├── initialization.php # Updated with auth support
│       └── autoload.php       # Simple class autoloader
├── public/
│   ├── login.php              # Login page
│   ├── register.php           # Registration page
│   ├── logout.php             # Logout handler
│   ├── handlers/
│   │   └── user_handler.php  # User authentication handler
│   └── common/
│       └── top.php           # Updated navigation
└── public/css/
    └── styles.css             # Added auth form styles
```

## How It Works

### Registration Flow
1. User fills out registration form
2. Form submits via AJAX to `user_handler.php`
3. Handler validates input and calls `User::registerUser()`
4. User class creates user record and log entry
5. Returns success JSON
6. Frontend redirects to login page

### Login Flow
1. User enters username/password
2. Form submits via AJAX to `user_handler.php`
3. Handler calls `User::loginUser()`
4. User class validates credentials
5. On success, session variables are set
6. Returns user data in JSON
7. Frontend redirects to home page

### Session Management
- Session starts in `initialization.php`
- CSRF tokens generated and validated
- User data stored in session:
  - `user_id` - For database queries
  - `username` - For display
  - `distance_unit` - For activity display
  - `log_id` - For activity CRUD operations

### Protected Pages
- Pages check for `$_SESSION['user_id']`
- If not set, redirect to `login.php`
- Example: `public/index.php`

## Security Notes (For Demo)

⚠️ **Current Implementation:**
- Passwords stored in **plain text** (you mentioned you'll implement hashing)
- CSRF tokens use simple `bin2hex()` (you mentioned you'll improve this)
- Session management is basic

✅ **What's Already Secure:**
- Prepared statements prevent SQL injection
- Input validation on all forms
- CSRF token validation
- Session regeneration on login

## Next Steps (For You)

1. **Implement Password Hashing:**
   - Use `password_hash()` in `registerUser()`
   - Use `password_verify()` in `loginUser()`

2. **Improve CSRF Tokens:**
   - Use `random_bytes()` instead of `bin2hex('session&strong')`
   - Store tokens securely

3. **Add Email Field:**
   - Update database schema if needed
   - Add email to registration form
   - Add email validation

4. **Complete CRUD Operations:**
   - Implement `readDataItem()` in Data class
   - Implement `updateData()` in Data class
   - Implement `deleteData()` in Data class

## Testing

### Test Registration:
1. Go to `register.php`
2. Fill out form with new username
3. Submit and verify redirect to login

### Test Login:
1. Go to `login.php`
2. Enter registered credentials
3. Verify redirect to home page
4. Verify username shows in navigation

### Test Logout:
1. Click logout link
2. Verify redirect to login page
3. Try accessing `index.php` - should redirect to login

### Test Protected Pages:
1. Try accessing `index.php` without login
2. Should redirect to `login.php`
3. After login, should access normally

## Database Requirements

The system expects these tables:
- `users` - User accounts
- `logs` - User activity logs (one per user)
- `activities` - Individual activities

See `database/footpower.sql` for schema.

## Notes

- All authentication is session-based
- No cookies used (except PHP session cookie)
- AJAX used for seamless user experience
- Error messages displayed inline
- Form validation on both client and server side

