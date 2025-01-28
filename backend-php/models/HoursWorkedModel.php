<?php
require_once('config.php');

class HoursWorkedModel {
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

    // Create a new job record
    public function create($data) {
        try {
            $stmt = $this->db->prepare('INSERT INTO hours_worked (employee_id, date, hours_worked) VALUES (:employee_id, :date, :hours_worked)');
            $stmt->execute([
                'employee_id' => $data['employee_id'],
                'date' => $data['date'],
                'hours_worked' => $data['hours_worked'],
            ]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error creating job: " . $e->getMessage();
        }
    }

    // Update an existing job record
    public function update($id, $data) {
        try {
            $stmt = $this->db->prepare('UPDATE hours_worked SET employee_id = :employee_id, date = :date, hours_worked = :hours_worked, updated_at = NOW() WHERE id = :id');
            $stmt->execute([
                'id' => $id,
                'employee_id' => $data['employee_id'],
                'date' => $data['date'],
                'hours_worked' => $data['hours_worked'],
            ]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error updating job: " . $e->getMessage();
        }
    }

    // Get all jobs
    public function getAll() {
        try {
            $stmt = $this->db->query('SELECT * FROM hours_worked WHERE deleted_at IS NULL');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching jobs: " . $e->getMessage();
            return [];
        }
    }

    // Get a specific job by its ID
    public function get($id) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM hours_worked WHERE id = :id AND deleted_at IS NULL');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching job: " . $e->getMessage();
            return null;
        }
    }

    // Get jobs for a specific employee
    public function getJobsByEmployee($employee_id) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM hours_worked WHERE employee_id = :employee_id AND deleted_at IS NULL');
            $stmt->execute(['employee_id' => $employee_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching jobs: " . $e->getMessage();
            return [];
        }
    }

    // Soft delete a job by marking it as deleted
    public function delete($id) {
        try {
            // Update the deleted_at timestamp to mark the record as deleted
            $stmt = $this->db->prepare('UPDATE hours_worked SET deleted_at = NOW() WHERE id = :id');
            $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error deleting job: " . $e->getMessage();
        }
    }
}
?>
