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
            $stmt = $this->db->query('SELECT DISTINCT employee_name FROM employees');
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching employees: " . $e->getMessage();
            return [];
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM employees WHERE employee_id = :id AND deleted_at IS NULL');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching employee: " . $e->getMessage();
            return null;
        }
    }

    public function getByJob($job_id) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM employees WHERE job_id = :job_id AND deleted_at IS NULL');
            $stmt->execute(['job_id' => $job_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching employees: " . $e->getMessage();
            return [];
        }
    }

    public function getByJobs($job_ids) {
        try {
            $in = str_repeat('?,', count($job_ids) - 1) . '?';
            $stmt = $this->db->prepare("SELECT * FROM employees WHERE job_id IN ($in) AND deleted_at IS NULL");
            $stmt->execute($job_ids);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching employees: " . $e->getMessage();
            return [];
        }
    }
    public function getByJobId($job_id) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM employees WHERE job_id = :job_id AND deleted_at IS NULL');
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
            // return the id of the inserted row
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error creating employee: " . $e->getMessage();
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->db->prepare('UPDATE employees SET employee_name = :employee_name WHERE employee_id = :employee_id');
            $stmt->execute([
                'employee_id' => $id,
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
            $stmt = $this->db->prepare('UPDATE employees SET deleted_at = NOW() WHERE employee_id = :employee_id');
            $stmt->execute([
                'employee_id' => $id,
            ]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error deleting employee: " . $e->getMessage();
        }
    }

    public function deleteByJobId($job_id) {
        try {
            // Update the deleted_at timestamp to mark the record as deleted
            $stmt = $this->db->prepare('UPDATE employees SET deleted_at = NOW() WHERE job_id = :job_id');
            $stmt->execute([
                'job_id' => $job_id,
            ]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error deleting employees: " . $e->getMessage();
        }
    }

}

