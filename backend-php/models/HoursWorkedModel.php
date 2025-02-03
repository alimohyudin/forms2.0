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

    public function getByWeekAndEmployee($week_start_date, $employee_id) {
        try {
            // error_log("SELECT * FROM hours_worked WHERE week_start_date = :week_start_date AND employee_id = :employee_id");
            // error_log("week_start_date: $week_start_date, employee_id: $employee_id");
            $stmt = $this->db->prepare('SELECT * FROM hours_worked WHERE week_start_date = :week_start_date AND employee_id = :employee_id');
            $stmt->execute([
                'week_start_date' => $week_start_date,
                'employee_id' => $employee_id,
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // echo "Error fetching hours worked: " . $e->getMessage();
            error_log("Error fetching hours worked: " . $e->getMessage());

            return null;
        }
    }

    public function getByWeekAndEmployeeIds($week_start_date, $employee_ids) {
        try {
            // Generate named parameters for each employee_id
            $placeholders = implode(', ', array_map(function($key) {
                return ":employee_id_$key";
            }, array_keys($employee_ids)));
            
            // Prepare the SQL statement with named parameters for employee_ids
            $stmt = $this->db->prepare("SELECT * FROM hours_worked WHERE week_start_date = :week_start_date AND employee_id IN ($placeholders)");
            
            // Combine the parameters
            $params = ['week_start_date' => $week_start_date];
            foreach ($employee_ids as $key => $employee_id) {
                $params["employee_id_$key"] = $employee_id;
            }
            
            error_log("SELECT * FROM hours_worked WHERE week_start_date = :week_start_date AND employee_id IN ($placeholders)");
            error_log(print_r($params, true));
            
            // Execute with the merged parameters
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching hours worked: " . $e->getMessage());
            return [];
        }
    }
    

    public function create($data) {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO hours_worked (
                    week_start_date, employee_id, mon, tue, wed, thu, fri, sat, sun
                ) VALUES (
                    :week_start_date, :employee_id, :mon, :tue, :wed, :thu, :fri, :sat, :sun
                )');
            $stmt->execute([
                'week_start_date' => $data['week_start_date'],
                'employee_id' => $data['employee_id'],
                'mon' => $data['mon'],
                'tue' => $data['tue'],
                'wed' => $data['wed'],
                'thu' => $data['thu'],
                'fri' => $data['fri'],
                'sat' => $data['sat'],
                'sun' => $data['sun'],
            ]);
        } catch (PDOException $e) {
            echo "Error creating hours worked: " . $e->getMessage();
        }
    }

    public function update($data) {
        try {
            $stmt = $this->db->prepare('
                UPDATE hours_worked 
                SET mon = :mon, tue = :tue, wed = :wed, thu = :thu, fri = :fri, sat = :sat, sun = :sun, updated_at = CURRENT_TIMESTAMP
                WHERE week_start_date = :week_start_date AND employee_id = :employee_id
            ');
            $stmt->execute([
                'week_start_date' => $data['week_start_date'],
                'employee_id' => $data['employee_id'],
                'mon' => $data['mon'],
                'tue' => $data['tue'],
                'wed' => $data['wed'],
                'thu' => $data['thu'],
                'fri' => $data['fri'],
                'sat' => $data['sat'],
                'sun' => $data['sun'],
            ]);
        } catch (PDOException $e) {
            echo "Error updating hours worked: " . $e->getMessage();
        }
    }

    public function delete($week_start_date, $employee_id) {
        try {
            $stmt = $this->db->prepare('DELETE FROM hours_worked WHERE week_start_date = :week_start_date AND employee_id = :employee_id');
            $stmt->execute([
                'week_start_date' => $week_start_date,
                'employee_id' => $employee_id,
            ]);
        } catch (PDOException $e) {
            echo "Error deleting hours worked: " . $e->getMessage();
        }
    }
}
?>
