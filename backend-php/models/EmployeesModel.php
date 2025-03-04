<?php
require_once('config.php');
class EmployeesModel
{

    private $db;

    public function __construct()
    {
        // Use the constants from config.php for database connection
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        try {
            $this->db = new PDO($dsn, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception mode for error handling
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getAll()
    {
        try {
            $stmt = $this->db->query('SELECT * FROM employees WHERE deleted_at IS NULL');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching employees: " . $e->getMessage();
            return [];
        }
    }

    public function countEmployees($jobIds = null)
    {
        try {
            if ($jobIds) {
                $in = str_repeat('?,', count($jobIds) - 1) . '?';
                $stmt = $this->db->prepare("SELECT COUNT(*) FROM employees e JOIN employees_jobs ej ON e.employee_id = ej.employee_id WHERE ej.job_id IN ($in) AND e.deleted_at IS NULL");
                $stmt->execute($jobIds);
            } else {
                $stmt = $this->db->prepare('SELECT COUNT(*) FROM employees WHERE deleted_at IS NULL');
                $stmt->execute();
            }
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error counting employees: " . $e->getMessage();
            return 0;
        }
    }

    public function getById($id)
    {
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

    public function getByJob($job_id)
    {
        try {
            $stmt = $this->db->prepare('SELECT e.employee_id, e.employee_name, ej.emp_job_id, ej.job_id FROM employees e JOIN employees_jobs ej ON e.employee_id = ej.employee_id WHERE ej.job_id = :job_id AND e.deleted_at IS NULL');
            $stmt->execute(['job_id' => $job_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching employees: " . $e->getMessage();
            return [];
        }
    }

    public function getByJobs($job_ids)
    {
        try {
            $in = str_repeat('?,', count($job_ids) - 1) . '?';
            $stmt = $this->db->prepare("SELECT e.employee_id, e.employee_name, ej.emp_job_id, ej.job_id FROM employees e JOIN employees_jobs ej ON e.employee_id = ej.employee_id WHERE ej.job_id IN ($in) AND e.deleted_at IS NULL");
            $stmt->execute($job_ids);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error fetching employees: " . $e->getMessage();
            return [];
        }
    }

    public function create($data)
    {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare('INSERT INTO employees (employee_name) VALUES (:employee_name)');
            $stmt->execute(['employee_name' => $data['employee_name']]);
            $employee_id = $this->db->lastInsertId();

            // $stmt = $this->db->prepare('INSERT INTO employees_jobs (employee_id, job_id) VALUES (:employee_id, :job_id)');
            // $stmt->execute([
            //     'employee_id' => $employee_id,
            //     'job_id' => $data['job_id'],
            // ]);

            $this->db->commit();
            return $employee_id;
        } catch (PDOException $e) {
            $this->db->rollBack();
            // Handle query errors
            echo "Error creating employee: " . $e->getMessage();
            return null;
        }
    }

    public function update($id, $data)
    {
        try {

            if ($data['job_id']) {
                $stmt = $this->db->prepare('INSERT INTO employees_jobs (employee_id, job_id) VALUES (:employee_id, :job_id)');
                $stmt->execute([
                    'employee_id' => $id,
                    'job_id' => $data['job_id'],
                ]);
            } else {
                $stmt = $this->db->prepare('UPDATE employees SET employee_name = :employee_name WHERE employee_id = :employee_id');
                $stmt->execute([
                    'employee_id' => $id,
                    'employee_name' => $data['employee_name'],
                ]);
            }
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error updating employee: " . $e->getMessage();
        }
    }

    public function create_emp_job($data)
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO employees_jobs (employee_id, job_id) VALUES (:employee_id, :job_id)');
            $stmt->execute([
                'employee_id' => $data['employee_id'],
                'job_id' => $data['job_id'],
            ]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error creating employees_jobs: " . $e->getMessage();
        }
    }

    public function delete_employees_jobs($id)
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM employees_jobs WHERE emp_job_id = :employee_job_id');
            $stmt->execute(['employee_job_id' => $id]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error deleting employees_jobs: " . $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            // Update the deleted_at timestamp to mark the record as deleted
            $stmt = $this->db->prepare('UPDATE employees SET deleted_at = NOW() WHERE employee_id = :employee_id');
            $stmt->execute(['employee_id' => $id]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error deleting employee: " . $e->getMessage();
        }
    }

    public function deleteByJobId($job_id)
    {
        try {
            // Update the deleted_at timestamp to mark the record as deleted
            $stmt = $this->db->prepare('UPDATE employees e JOIN employees_jobs ej ON e.employee_id = ej.employee_id SET e.deleted_at = NOW() WHERE ej.job_id = :job_id');
            $stmt->execute(['job_id' => $job_id]);
        } catch (PDOException $e) {
            // Handle query errors
            echo "Error deleting employees: " . $e->getMessage();
        }
    }

}

