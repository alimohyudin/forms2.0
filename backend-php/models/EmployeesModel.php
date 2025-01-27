<?php
require_once('config.php');
class EmployeesModel {

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
            $stmt = $this->db->query('SELECT * FROM employees');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching employees: " . $e->getMessage();
            return [];
        }
    }

    public function get($id) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM employees WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching employee: " . $e->getMessage();
            return null;
        }
    }

    public function getEmployeesByJobId($job_id) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM employees WHERE job_id = :job_id');
            $stmt->execute(['job_id' => $job_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching employees: " . $e->getMessage();
            return [];
        }
    }

    public function create($data) {
        try {
            $stmt = $this->db->prepare('INSERT INTO employees (job_id, employee_name) VALUES (:job_id, :employee_name)');
            $stmt->execute([
                'job_id' => $data['job_id'],
                'employee_name' => $data['employee_name'],
            ]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error creating employee: " . $e->getMessage();
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->db->prepare('UPDATE employees SET employee_name = :employee_name WHERE id = :id');
            $stmt->execute([
                'id' => $id,
                'employee_name' => $data['employee_name'],
            ]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error updating employee: " . $e->getMessage();
        }
    }

    public function delete($id) {
        try {
            // Update the deleted_at timestamp to mark the record as deleted
            $stmt = $this->db->prepare('UPDATE employees SET deleted_at = NOW() WHERE id = :id');
            $stmt->execute([
                'id' => $id,
            ]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error deleting employee: " . $e->getMessage();
        }
    }

}

