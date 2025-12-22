<?php

/**
 * Authentication System Test Script
 * 
 * Tests the authentication system using CURL HTTP requests
 * Run from command line: php tests/test_auth.php
 * 
 * @Author: AJ Javadi
 * @Date: 2025-01-XX
 */

// Configuration
$base_url = 'http://localhost:8000'; // Adjust to your server URL
$test_username = 'test_user_' . time(); // Unique username for each test run
$test_password = 'testpass123';
$test_distance_unit = 'miles';

// Colors for terminal output
$colors = [
    'green' => "\033[32m",
    'red' => "\033[31m",
    'yellow' => "\033[33m",
    'blue' => "\033[34m",
    'reset' => "\033[0m",
    'bold' => "\033[1m"
];

// Test results
$tests_passed = 0;
$tests_failed = 0;
$test_results = [];

/**
 * Print colored output
 */
function print_color($message, $color = 'reset') {
    global $colors;
    echo $colors[$color] . $message . $colors['reset'] . "\n";
}

/**
 * Make CURL request
 */
function make_request($url, $data = [], $cookies = []) {
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => !empty($data),
        CURLOPT_POSTFIELDS => !empty($data) ? http_build_query($data) : null,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_COOKIEJAR => sys_get_temp_dir() . '/test_cookies.txt',
        CURLOPT_COOKIEFILE => sys_get_temp_dir() . '/test_cookies.txt',
        CURLOPT_HEADER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded',
        ],
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    
    curl_close($ch);
    
    $headers = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    
    return [
        'http_code' => $http_code,
        'headers' => $headers,
        'body' => $body,
        'json' => json_decode($body, true)
    ];
}

/**
 * Get CSRF token from login page
 */
function get_csrf_token($base_url) {
    $ch = curl_init($base_url . '/login.php');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => sys_get_temp_dir() . '/test_cookies.txt',
        CURLOPT_COOKIEFILE => sys_get_temp_dir() . '/test_cookies.txt',
    ]);
    
    $html = curl_exec($ch);
    curl_close($ch);
    
    // Extract token from hidden input field
    if (preg_match('/name="token"\s+value="([^"]+)"/', $html, $matches)) {
        return $matches[1];
    }
    
    return null;
}

/**
 * Run a test
 */
function run_test($name, $callback) {
    global $tests_passed, $tests_failed, $test_results;
    
    print_color("\n" . str_repeat("=", 60), 'blue');
    print_color("TEST: " . $name, 'bold');
    print_color(str_repeat("=", 60), 'blue');
    
    try {
        $result = $callback();
        
        if ($result['success']) {
            print_color("✓ PASSED", 'green');
            $tests_passed++;
            $test_results[] = ['name' => $name, 'status' => 'PASSED', 'message' => $result['message'] ?? ''];
        } else {
            print_color("✗ FAILED", 'red');
            print_color("  Error: " . ($result['message'] ?? 'Unknown error'), 'red');
            $tests_failed++;
            $test_results[] = ['name' => $name, 'status' => 'FAILED', 'message' => $result['message'] ?? ''];
        }
        
        if (isset($result['data'])) {
            print_color("  Data: " . json_encode($result['data'], JSON_PRETTY_PRINT), 'yellow');
        }
        
    } catch (Exception $e) {
        print_color("✗ FAILED - Exception: " . $e->getMessage(), 'red');
        $tests_failed++;
        $test_results[] = ['name' => $name, 'status' => 'FAILED', 'message' => $e->getMessage()];
    }
}

// ============================================
// TEST SUITE
// ============================================

print_color("\n" . str_repeat("=", 60), 'bold');
print_color("FOOTPOWER AUTHENTICATION SYSTEM TEST", 'bold');
print_color("Base URL: " . $base_url, 'blue');
print_color("Test Username: " . $test_username, 'blue');
print_color(str_repeat("=", 60), 'bold');

// Clean up old cookies
@unlink(sys_get_temp_dir() . '/test_cookies.txt');

// Test 1: Get CSRF Token
$csrf_token = null;
run_test('Get CSRF Token from Login Page', function() use ($base_url, &$csrf_token) {
    $csrf_token = get_csrf_token($base_url);
    if ($csrf_token) {
        return ['success' => true, 'message' => 'Token retrieved', 'data' => ['token' => substr($csrf_token, 0, 20) . '...']];
    }
    return ['success' => false, 'message' => 'Could not retrieve CSRF token'];
});

if (!$csrf_token) {
    print_color("\n⚠ Cannot continue without CSRF token. Make sure the server is running.", 'yellow');
    exit(1);
}

// Test 2: Registration
$registration_result = null;
run_test('User Registration', function() use ($base_url, $test_username, $test_password, $test_distance_unit, $csrf_token, &$registration_result) {
    $response = make_request($base_url . '/handlers/user_handler.php', [
        'user-verb' => 'register',
        'username' => $test_username,
        'password' => $test_password,
        'password-confirm' => $test_password,
        'distance-unit' => $test_distance_unit,
        'token' => $csrf_token
    ]);
    
    $registration_result = $response['json'];
    
    if ($response['json'] && $response['json']['status'] === 'success') {
        return ['success' => true, 'message' => 'User registered successfully', 'data' => $response['json']];
    }
    
    return ['success' => false, 'message' => $response['json']['message'] ?? 'Registration failed', 'data' => $response['json']];
});

// Test 3: Registration with Duplicate Username
run_test('Registration with Duplicate Username (Should Fail)', function() use ($base_url, $test_username, $test_password, $csrf_token) {
    $response = make_request($base_url . '/handlers/user_handler.php', [
        'user-verb' => 'register',
        'username' => $test_username, // Same username
        'password' => $test_password,
        'password-confirm' => $test_password,
        'distance-unit' => 'km',
        'token' => $csrf_token
    ]);
    
    if ($response['json'] && $response['json']['status'] === 'error') {
        return ['success' => true, 'message' => 'Correctly rejected duplicate username', 'data' => $response['json']];
    }
    
    return ['success' => false, 'message' => 'Should have rejected duplicate username', 'data' => $response['json']];
});

// Test 4: Login
$login_result = null;
run_test('User Login', function() use ($base_url, $test_username, $test_password, $csrf_token, &$login_result) {
    $response = make_request($base_url . '/handlers/user_handler.php', [
        'user-verb' => 'login',
        'username' => $test_username,
        'password' => $test_password,
        'token' => $csrf_token
    ]);
    
    $login_result = $response['json'];
    
    if ($response['json'] && $response['json']['status'] === 'success' && isset($response['json']['user'])) {
        return ['success' => true, 'message' => 'Login successful', 'data' => $response['json']];
    }
    
    return ['success' => false, 'message' => $response['json']['message'] ?? 'Login failed', 'data' => $response['json']];
});

// Test 5: Login with Wrong Password
run_test('Login with Wrong Password (Should Fail)', function() use ($base_url, $test_username, $csrf_token) {
    $response = make_request($base_url . '/handlers/user_handler.php', [
        'user-verb' => 'login',
        'username' => $test_username,
        'password' => 'wrongpassword',
        'token' => $csrf_token
    ]);
    
    if ($response['json'] && $response['json']['status'] === 'error') {
        return ['success' => true, 'message' => 'Correctly rejected wrong password', 'data' => $response['json']];
    }
    
    return ['success' => false, 'message' => 'Should have rejected wrong password', 'data' => $response['json']];
});

// Test 6: Access Protected Page (Should Redirect)
run_test('Access Protected Page (index.php)', function() use ($base_url) {
    $ch = curl_init($base_url . '/index.php');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_COOKIEJAR => sys_get_temp_dir() . '/test_cookies.txt',
        CURLOPT_COOKIEFILE => sys_get_temp_dir() . '/test_cookies.txt',
        CURLOPT_HEADER => true,
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Should redirect to login.php (302 or 301) or show login page
    if ($http_code == 302 || $http_code == 301 || strpos($response, 'login.php') !== false || strpos($response, 'Login') !== false) {
        return ['success' => true, 'message' => 'Protected page correctly requires authentication'];
    }
    
    return ['success' => false, 'message' => 'Protected page should redirect or show login'];
});

// Test 7: Login Again (to get session)
run_test('Login Again (Establish Session)', function() use ($base_url, $test_username, $test_password, $csrf_token) {
    $csrf_token = get_csrf_token($base_url); // Get fresh token
    $response = make_request($base_url . '/handlers/user_handler.php', [
        'user-verb' => 'login',
        'username' => $test_username,
        'password' => $test_password,
        'token' => $csrf_token
    ]);
    
    if ($response['json'] && $response['json']['status'] === 'success') {
        return ['success' => true, 'message' => 'Session established', 'data' => $response['json']];
    }
    
    return ['success' => false, 'message' => 'Could not establish session'];
});

// Test 8: Access Protected Page After Login
run_test('Access Protected Page After Login', function() use ($base_url) {
    $ch = curl_init($base_url . '/index.php');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_COOKIEJAR => sys_get_temp_dir() . '/test_cookies.txt',
        CURLOPT_COOKIEFILE => sys_get_temp_dir() . '/test_cookies.txt',
        CURLOPT_HEADER => true,
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Should NOT redirect (200 OK) and should show content
    if ($http_code == 200 && strpos($response, 'Activity Log') !== false) {
        return ['success' => true, 'message' => 'Protected page accessible after login'];
    }
    
    return ['success' => false, 'message' => 'Protected page should be accessible after login', 'data' => ['http_code' => $http_code]];
});

// Test 9: Logout
run_test('User Logout', function() use ($base_url, $csrf_token) {
    $csrf_token = get_csrf_token($base_url); // Get fresh token
    $response = make_request($base_url . '/handlers/user_handler.php', [
        'user-verb' => 'logout',
        'token' => $csrf_token
    ]);
    
    if ($response['json'] && $response['json']['status'] === 'success') {
        return ['success' => true, 'message' => 'Logout successful', 'data' => $response['json']];
    }
    
    return ['success' => false, 'message' => $response['json']['message'] ?? 'Logout failed', 'data' => $response['json']];
});

// Test 10: Access Protected Page After Logout
run_test('Access Protected Page After Logout (Should Redirect)', function() use ($base_url) {
    $ch = curl_init($base_url . '/index.php');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_COOKIEJAR => sys_get_temp_dir() . '/test_cookies.txt',
        CURLOPT_COOKIEFILE => sys_get_temp_dir() . '/test_cookies.txt',
        CURLOPT_HEADER => true,
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Should redirect to login.php after logout
    if ($http_code == 302 || $http_code == 301 || strpos($response, 'login.php') !== false) {
        return ['success' => true, 'message' => 'Correctly requires login after logout'];
    }
    
    return ['success' => false, 'message' => 'Should redirect to login after logout'];
});

// Test 11: Invalid CSRF Token
run_test('Request with Invalid CSRF Token (Should Fail)', function() use ($base_url, $test_username, $test_password) {
    $response = make_request($base_url . '/handlers/user_handler.php', [
        'user-verb' => 'login',
        'username' => $test_username,
        'password' => $test_password,
        'token' => 'invalid_token_12345'
    ]);
    
    if ($response['json'] && $response['json']['status'] === 'error' && strpos($response['json']['message'], 'token') !== false) {
        return ['success' => true, 'message' => 'Correctly rejected invalid token', 'data' => $response['json']];
    }
    
    return ['success' => false, 'message' => 'Should have rejected invalid token', 'data' => $response['json']];
});

// ============================================
// SUMMARY
// ============================================

print_color("\n" . str_repeat("=", 60), 'bold');
print_color("TEST SUMMARY", 'bold');
print_color(str_repeat("=", 60), 'bold');

print_color("\nTotal Tests: " . ($tests_passed + $tests_failed), 'blue');
print_color("Passed: " . $tests_passed, 'green');
print_color("Failed: " . $tests_failed, $tests_failed > 0 ? 'red' : 'green');

print_color("\n" . str_repeat("-", 60), 'blue');
print_color("Detailed Results:", 'bold');
foreach ($test_results as $result) {
    $color = $result['status'] === 'PASSED' ? 'green' : 'red';
    print_color(sprintf("%-40s %s", $result['name'], $result['status']), $color);
    if (!empty($result['message'])) {
        print_color("  → " . $result['message'], 'yellow');
    }
}

print_color("\n" . str_repeat("=", 60), 'bold');

// Cleanup
@unlink(sys_get_temp_dir() . '/test_cookies.txt');

// Exit with appropriate code
exit($tests_failed > 0 ? 1 : 0);

