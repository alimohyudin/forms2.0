<?php

class UsersModel {
    private $db;

    public function __construct() {
        // Use the constants from config.php for database connection
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        try {
            $this->db = new PDO($dsn, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception mode for error handling
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verifyPassword($inputPassword, $storedHash) {
        return password_verify($inputPassword, $storedHash);
    }

    public function create($inputData) {
        $username = $inputData['username'];
        $password = $inputData['password'];

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getAll() {
        try {
            $stmt = $this->db->query('SELECT * FROM users WHERE deleted_at IS NULL ORDER BY user_id DESC');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching users: " . $e->getMessage();
            return [];
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE user_id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching user: " . $e->getMessage();
            return null;
        }
    }

    /* update username and password by id and hash the password */
    public function update($id, $data) {
        try {
            $id = $id;
            $username = $data['username'];
            $password = $data['password'];

            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("UPDATE users SET username = :username, password = :password WHERE user_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error updating user: " . $e->getMessage();
        }
    }

    public function delete($id) {
        try {
            // Soft delete the user with the given ID
            $stmt = $this->db->prepare('UPDATE users SET deleted_at = NOW() WHERE user_id = :id');
            $stmt->execute(['id' => $id]);
            return true;
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error deleting user: " . $e->getMessage();
        }
    }
}