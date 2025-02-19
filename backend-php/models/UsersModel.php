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
            $stmt = $this->db->query('SELECT * FROM users');
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

    public function update($id, $data) {
        try {
            $stmt = $this->db->prepare('UPDATE users SET username = :username WHERE user_id = :id');
            $stmt->execute([
                'id' => $id,
                'username' => $data['username'],
            ]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error updating user: " . $e->getMessage();
        }
    }
}