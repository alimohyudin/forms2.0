<?php
require_once('config.php');

class HoursWorkedModel  {
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
    

    public function create($data) {
        try {
            $stmt = $this->db->prepare('INSERT INTO hours_worked (employee_id, hours_worked) VALUES (:employee_id, :hours_worked)');
            $stmt->execute([
                'employee_id' => $data['employee_id'],
                'hours_worked' => $data['hours_worked'],
            ]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error creating job: " . $e->getMessage();
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->db->prepare('UPDATE hours_worked SET job_name = :job_name WHERE id = :id');
            $stmt->execute([
                'id' => $id,
                'job_name' => $data['job_name'],
            ]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error updating job: " . $e->getMessage();
        }
    }

    public function getAll() {
        try {
            $stmt = $this->db->query('SELECT * FROM jobs');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching jobs: " . $e->getMessage();
            return [];
        }
    }

    public function get($id) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM jobs WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching job: " . $e->getMessage();
            return null;
        }
    }

    public function getJobsByWeekStartDate($week_start_date) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM jobs WHERE week_start_date = :week_start_date');
            $stmt->execute(['week_start_date' => $week_start_date]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching jobs: " . $e->getMessage();
            return [];
        }
    }

    public function delete($id) {
        try {
            // Update the deleted_at timestamp to mark the record as deleted
            $stmt = $this->db->prepare('UPDATE jobs SET deleted_at = NOW() WHERE id = :id');
            $stmt->execute([
                'id' => $id,
            ]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error deleting job: " . $e->getMessage();
        }
    }
}
