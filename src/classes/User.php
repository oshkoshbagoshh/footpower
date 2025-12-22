<?php

/**
 * User Class
 * Handles user authentication and CRUD operations
 * 
 * @Author: AJ Javadi
 * @Email: amirjavadi25@gmail.com
 * @Date: 2024-09-18 16:11:52
 * @Last Modified by: AJ Javadi
 * @Last Modified time: 2025-01-XX
 * @Description: User management class for FootPower ERP system
 */
class User
{
    private $_mysqli;

    /**
     * Constructor
     * @param mysqli|null $mysqli MySQLi database connection
     */
    public function __construct($mysqli = null)
    {
        $this->_mysqli = $mysqli;
    }

    // ============================================
    // AUTHENTICATION METHODS
    // ============================================

    /**
     * Register a new user
     * @param string $username Username
     * @param string $password Password (will be hashed when security is implemented)
     * @param string $distance_unit Distance unit preference (miles/km)
     * @return array Result array with status and message
     */
    public function registerUser($username, $password, $distance_unit = 'miles'): array
    {
        $server_results = ['status' => 'success', 'message' => ''];

        // Validate username
        if (empty($username)) {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Error: Username is required';
            return $server_results;
        }

        // Check if username already exists
        $existing_user = $this->getUserByUsername($username);
        if ($existing_user) {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Error: Username already exists';
            return $server_results;
        }

        // Validate password
        if (empty($password)) {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Error: Password is required';
            return $server_results;
        }

        // Generate verification code (simple for demo - you can enhance this)
        $verification_code = bin2hex(random_bytes(16));

        // Insert new user
        $sql = "INSERT INTO users (username, password, distance_unit, verification_code, verified) VALUES (?, ?, ?, ?, 1)";
        $stmt = $this->_mysqli->prepare($sql);
        
        if (!$stmt) {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Database error: ' . $this->_mysqli->error;
            return $server_results;
        }

        $stmt->bind_param("ssss", $username, $password, $distance_unit, $verification_code);
        $stmt->execute();

        if ($this->_mysqli->errno === 0) {
            $user_id = $this->_mysqli->insert_id;
            
            // Create a log entry for the new user
            $this->createUserLog($user_id);
            
            $server_results['message'] = 'Registration successful!';
            $server_results['user_id'] = $user_id;
        } else {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Database error: ' . $this->_mysqli->error;
        }

        return $server_results;
    }

    /**
     * Login user
     * @param string $username Username
     * @param string $password Password
     * @return array Result array with status, message, and user data
     */
    public function loginUser($username, $password): array
    {
        $server_results = ['status' => 'success', 'message' => ''];

        // Validate inputs
        if (empty($username)) {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Error: Username is required';
            return $server_results;
        }

        if (empty($password)) {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Error: Password is required';
            return $server_results;
        }

        // Get user by username
        $user = $this->getUserByUsername($username);

        if (!$user) {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Error: Invalid username or password';
            return $server_results;
        }

        // Check password (plain text for demo - you'll implement hashing)
        if ($user['password'] !== $password) {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Error: Invalid username or password';
            return $server_results;
        }

        // Check if user is verified
        if ($user['verified'] != 1) {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Error: Account not verified';
            return $server_results;
        }

        // Get user's log_id
        $log_id = $this->getUserLogId($user['user_id']);

        $server_results['message'] = 'Login successful!';
        $server_results['user'] = [
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'distance_unit' => $user['distance_unit'],
            'log_id' => $log_id
        ];

        return $server_results;
    }

    /**
     * Create a log entry for a user
     * @param int $user_id User ID
     * @return int|false Log ID or false on failure
     */
    private function createUserLog($user_id): int|false
    {
        $sql = "INSERT INTO logs (user_id) VALUES (?)";
        $stmt = $this->_mysqli->prepare($sql);
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        if ($this->_mysqli->errno === 0) {
            return $this->_mysqli->insert_id;
        }

        return false;
    }

    /**
     * Get user's log ID
     * @param int $user_id User ID
     * @return int|false Log ID or false if not found
     */
    public function getUserLogId($user_id): int|false
    {
        $sql = "SELECT log_id FROM logs WHERE user_id = ? LIMIT 1";
        $stmt = $this->_mysqli->prepare($sql);
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row ? (int)$row['log_id'] : false;
    }

    // ============================================
    // CRUD METHODS
    // ============================================

    /**
     * Create a new user (legacy method - use registerUser instead)
     * @param string $username Username
     * @param string $password Password
     * @param string $email Email (not in current schema, kept for compatibility)
     * @return array Result array
     */
    public function createUser($username, $password, $email = ''): array
    {
        return $this->registerUser($username, $password);
    }

    /**
     * Read all users
     * @return array Array of all users
     */
    public function readUser(): array
    {
        $sql = "SELECT user_id, username, distance_unit, verified, creation_date FROM users";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Update user information
     * @param int $user_id User ID
     * @param string|null $username New username
     * @param string|null $password New password
     * @param string|null $distance_unit New distance unit
     * @return array Result array
     */
    public function updateUser($user_id, $username = null, $password = null, $distance_unit = null): array
    {
        $server_results = ['status' => 'success', 'message' => ''];

        $updates = [];
        $params = [];
        $types = '';

        if ($username !== null) {
            $updates[] = "username = ?";
            $params[] = $username;
            $types .= 's';
        }

        if ($password !== null) {
            $updates[] = "password = ?";
            $params[] = $password;
            $types .= 's';
        }

        if ($distance_unit !== null) {
            $updates[] = "distance_unit = ?";
            $params[] = $distance_unit;
            $types .= 's';
        }

        if (empty($updates)) {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Error: No fields to update';
            return $server_results;
        }

        $params[] = $user_id;
        $types .= 'i';

        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE user_id = ?";
        $stmt = $this->_mysqli->prepare($sql);

        if (!$stmt) {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Database error: ' . $this->_mysqli->error;
            return $server_results;
        }

        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        if ($this->_mysqli->errno === 0) {
            $server_results['message'] = 'User updated successfully';
        } else {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Database error: ' . $this->_mysqli->error;
        }

        return $server_results;
    }

    /**
     * Delete a user
     * @param int $user_id User ID
     * @return array Result array
     */
    public function deleteUser($user_id): array
    {
        $server_results = ['status' => 'success', 'message' => ''];

        // Check if user has logs/activities (prevent deletion if they do)
        $log_id = $this->getUserLogId($user_id);
        if ($log_id) {
            $sql = "SELECT COUNT(*) as count FROM activities WHERE log_id = ?";
            $stmt = $this->_mysqli->prepare($sql);
            $stmt->bind_param("i", $log_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['count'] > 0) {
                $server_results['status'] = 'error';
                $server_results['message'] = 'Cannot delete user with existing activities';
                return $server_results;
            }
        }

        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $this->_mysqli->prepare($sql);

        if (!$stmt) {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Database error: ' . $this->_mysqli->error;
            return $server_results;
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        if ($this->_mysqli->errno === 0) {
            $server_results['message'] = 'User deleted successfully';
        } else {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Database error: ' . $this->_mysqli->error;
        }

        return $server_results;
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    /**
     * Get user by ID
     * @param int $id User ID
     * @return array|null User data or null if not found
     */
    public function getUserById($id): ?array
    {
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user ?: null;
    }

    /**
     * Get user by username
     * @param string $username Username
     * @return array|null User data or null if not found
     */
    public function getUserByUsername($username): ?array
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user ?: null;
    }

    /**
     * Get user by verification code
     * @param string $verification_code Verification code
     * @return array|null User data or null if not found
     */
    public function getUserByVerificationCode($verification_code): ?array
    {
        $sql = "SELECT * FROM users WHERE verification_code = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("s", $verification_code);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user ?: null;
    }

    /**
     * Get user by distance unit
     * @param string $distance_unit Distance unit
     * @return array|null User data or null if not found
     */
    public function getUserByDistanceUnit($distance_unit): ?array
    {
        $sql = "SELECT * FROM users WHERE distance_unit = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("s", $distance_unit);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user ?: null;
    }

    /**
     * Get user by verified status
     * @param int $verified Verified status (0 or 1)
     * @return array|null User data or null if not found
     */
    public function getUserByVerified($verified): ?array
    {
        $sql = "SELECT * FROM users WHERE verified = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("i", $verified);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user ?: null;
    }

    /**
     * Get user by creation date
     * @param string $creation_date Creation date
     * @return array|null User data or null if not found
     */
    public function getUserByCreationDate($creation_date): ?array
    {
        $sql = "SELECT * FROM users WHERE creation_date = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("s", $creation_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user ?: null;
    }
}
