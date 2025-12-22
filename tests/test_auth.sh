#!/bin/bash

# Authentication System Test Script (Shell Version)
# Run: bash tests/test_auth.sh
# Or: chmod +x tests/test_auth.sh && ./tests/test_auth.sh

BASE_URL="http://localhost:8000"
TEST_USERNAME="test_user_$(date +%s)"
TEST_PASSWORD="testpass123"
COOKIE_FILE="/tmp/footpower_test_cookies.txt"

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
BOLD='\033[1m'
NC='\033[0m' # No Color

PASSED=0
FAILED=0

# Cleanup
rm -f "$COOKIE_FILE"

echo -e "${BOLD}============================================================${NC}"
echo -e "${BOLD}FOOTPOWER AUTHENTICATION SYSTEM TEST${NC}"
echo -e "${BLUE}Base URL: $BASE_URL${NC}"
echo -e "${BLUE}Test Username: $TEST_USERNAME${NC}"
echo -e "${BOLD}============================================================${NC}"

# Function to get CSRF token
get_csrf_token() {
    curl -s -c "$COOKIE_FILE" -b "$COOKIE_FILE" "$BASE_URL/login.php" | \
    grep -oP 'name="token"\s+value="\K[^"]+' | head -1
}

# Function to make request
make_request() {
    local url=$1
    local data=$2
    curl -s -c "$COOKIE_FILE" -b "$COOKIE_FILE" \
         -X POST \
         -H "Content-Type: application/x-www-form-urlencoded" \
         -d "$data" \
         "$url"
}

# Test function
run_test() {
    local test_name=$1
    local test_command=$2
    
    echo -e "\n${BLUE}============================================================${NC}"
    echo -e "${BOLD}TEST: $test_name${NC}"
    echo -e "${BLUE}============================================================${NC}"
    
    if eval "$test_command"; then
        echo -e "${GREEN}✓ PASSED${NC}"
        ((PASSED++))
    else
        echo -e "${RED}✗ FAILED${NC}"
        ((FAILED++))
    fi
}

# Test 1: Get CSRF Token
CSRF_TOKEN=$(get_csrf_token)
if [ -z "$CSRF_TOKEN" ]; then
    echo -e "${RED}Error: Could not retrieve CSRF token. Is the server running?${NC}"
    exit 1
fi

echo -e "${GREEN}CSRF Token retrieved: ${CSRF_TOKEN:0:20}...${NC}"

# Test 2: Registration
run_test "User Registration" "
    RESPONSE=\$(make_request \"$BASE_URL/handlers/user_handler.php\" \
        \"user-verb=register&username=$TEST_USERNAME&password=$TEST_PASSWORD&password-confirm=$TEST_PASSWORD&distance-unit=miles&token=$CSRF_TOKEN\")
    echo \"Response: \$RESPONSE\"
    echo \"\$RESPONSE\" | grep -q '\"status\":\"success\"'
"

# Test 3: Login
run_test "User Login" "
    CSRF_TOKEN=\$(get_csrf_token)
    RESPONSE=\$(make_request \"$BASE_URL/handlers/user_handler.php\" \
        \"user-verb=login&username=$TEST_USERNAME&password=$TEST_PASSWORD&token=\$CSRF_TOKEN\")
    echo \"Response: \$RESPONSE\"
    echo \"\$RESPONSE\" | grep -q '\"status\":\"success\"'
"

# Test 4: Access Protected Page
run_test "Access Protected Page After Login" "
    HTTP_CODE=\$(curl -s -o /dev/null -w '%{http_code}' -b \"$COOKIE_FILE\" \"$BASE_URL/index.php\")
    [ \"\$HTTP_CODE\" = \"200\" ]
"

# Test 5: Logout
run_test "User Logout" "
    CSRF_TOKEN=\$(get_csrf_token)
    RESPONSE=\$(make_request \"$BASE_URL/handlers/user_handler.php\" \
        \"user-verb=logout&token=\$CSRF_TOKEN\")
    echo \"Response: \$RESPONSE\"
    echo \"\$RESPONSE\" | grep -q '\"status\":\"success\"'
"

# Summary
echo -e "\n${BOLD}============================================================${NC}"
echo -e "${BOLD}TEST SUMMARY${NC}"
echo -e "${BOLD}============================================================${NC}"
echo -e "${BLUE}Total Tests: $((PASSED + FAILED))${NC}"
echo -e "${GREEN}Passed: $PASSED${NC}"
if [ $FAILED -gt 0 ]; then
    echo -e "${RED}Failed: $FAILED${NC}"
else
    echo -e "${GREEN}Failed: $FAILED${NC}"
fi
echo -e "${BOLD}============================================================${NC}"

# Cleanup
rm -f "$COOKIE_FILE"

exit $FAILED

