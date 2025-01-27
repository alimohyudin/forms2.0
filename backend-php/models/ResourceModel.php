<?php
require_once('config.php');
class ResourceModel {
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

    public function getAll() {
        try {
            $stmt = $this->db->query('SELECT * FROM resources');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching resources: " . $e->getMessage();
            return [];
        }
    }

    public function create($data) {
        try {
            $stmt = $this->db->prepare('INSERT INTO resources (name, value) VALUES (:name, :value)');
            $stmt->execute([
                'name' => $data['name'],
                'value' => $data['value'],
            ]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error creating resource: " . $e->getMessage();
        }
    }
}
?>