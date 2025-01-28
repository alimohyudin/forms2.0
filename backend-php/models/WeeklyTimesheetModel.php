<?php
require_once('config.php');
class WeeklyTimesheetModel {
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
            $stmt = $this->db->query('SELECT * FROM weekly_timesheet');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching weekly_timesheet: " . $e->getMessage();
            return [];
        }
    }

    public function getByWeek($week_start_date) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM weekly_timesheet WHERE week_start_date = :week_start_date');
            $stmt->execute([
                'week_start_date' => $week_start_date,
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching weekly_timesheet: " . $e->getMessage();
            return [];
        }
    }

    public function create($data) {
        try {
            // Insert the new week into the weekly_timesheet table
            $stmt = $this->db->prepare('INSERT INTO weekly_timesheet (week_start_date, week_end_date, foreman_name) VALUES (:week_start_date, :week_end_date, :foreman_name)');
            $stmt->execute([
                'week_start_date' => $data['week_start_date'],
                'week_end_date' => $data['week_end_date'],
                'foreman_name' => $data['foreman_name'],
            ]);
    
            // Insert 4 default jobs for the new week
            $jobNames = ['Job A', 'Job B', 'Job C', 'Job D']; // Default job names
            $jobIds = [];
            foreach ($jobNames as $jobName) {
                $stmt = $this->db->prepare('INSERT INTO jobs (week_start_date, job_name) VALUES (:week_start_date, :job_name)');
                $stmt->execute([
                    'week_start_date' => $data['week_start_date'],
                    'job_name' => $jobName,
                ]);
                $jobIds[] = $this->db->lastInsertId(); // Store job IDs for adding employees
            }
    
            // Insert 4 default employees for each job
            foreach ($jobIds as $jobId) {
                for ($i = 1; $i <= 4; $i++) {
                    $employeeName = "Employee $i for Job $jobId"; // Customize employee name
                    $stmt = $this->db->prepare('INSERT INTO employees (job_id, employee_name) VALUES (:job_id, :employee_name)');
                    $stmt->execute([
                        'job_id' => $jobId,
                        'employee_name' => $employeeName,
                    ]);
                }
            }
    
            echo "Week, jobs, and employees created successfully.";
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error creating resource: " . $e->getMessage();
        }
    }
    
    public function delete($id) {
        try {
            // Update the deleted_at timestamp to mark the record as deleted
            $stmt = $this->db->prepare('UPDATE weekly_timesheet SET deleted_at = NOW() WHERE id = :id');
            $stmt->execute([
                'id' => $id,
            ]);
            echo "Resource marked as deleted.";
        } catch (PDOException $e) {
            echo "Error deleting resource: " . $e->getMessage();
        }
    }
}
?>