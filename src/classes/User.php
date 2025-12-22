<?php

class User {
    private $_mysqli;
    public function __construct($mysqli = null) {
        $this->_mysqli = $mysqli;
    }
    public function createUser() {
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);
        $stmt->execute();
    }
    public function readUser() {
        $sql = "SELECT * FROM users";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function updateUser() {
        $sql = "UPDATE users SET username = ?, password = ?, email = ? WHERE id = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("sssi", $username, $password, $email, $id);
        $stmt->execute();
    }
    public function deleteUser() {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("i", $id);    
        $stmt->execute();
    }
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }   


    public function getUserByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("s", $email); 

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getUserByVerificationCode($verification_code) {
        $sql = "SELECT * FROM users WHERE verification_code = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("s", $verification_code);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }   

    public function getUserByDistanceUnit($distance_unit) {
        $sql = "SELECT * FROM users WHERE distance_unit = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("s", $distance_unit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getUserByVerified($verified) {
        $sql = "SELECT * FROM users WHERE verified = ?";

        $stmt->bind_param("i", $verified);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getUserByCreationDate($creation_date) {
        $sql = "SELECT * FROM users WHERE creation_date = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("s", $creation_date);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getUserByLastLogin($last_login) {
        $sql = "SELECT * FROM users WHERE last_login = ?";
        $stmt = $this->_mysqli->prepare($sql);
        $stmt->bind_param("s", $last_login);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

}
