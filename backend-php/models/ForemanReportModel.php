<?php

class ForemanReportModel
{
    private $db;

    public function __construct($db)
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

    public function createReport($data)
    {
        $sql = "INSERT INTO foreman_reports (report_date, foreman_name, project_name, weather_condition, safety_meeting, soil_condition, work_performed_today, problems_delays, equipment_rented, trucks, equipment) 
                VALUES (:report_date, :foreman_name, :project_name, :weather_condition, :safety_meeting, :soil_condition, :work_performed_today, :problems_delays, :equipment_rented, :trucks, :equipment)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
    }

    public function getReport($id)
    {
        $sql = "SELECT * FROM foreman_reports WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function updateReport($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE foreman_reports SET 
                report_date = :report_date, 
                foreman_name = :foreman_name, 
                project_name = :project_name, 
                weather_condition = :weather_condition, 
                safety_meeting = :safety_meeting, 
                soil_condition = :soil_condition, 
                work_performed_today = :work_performed_today, 
                problems_delays = :problems_delays, 
                equipment_rented = :equipment_rented, 
                trucks = :trucks, 
                equipment = :equipment 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
    }

    public function deleteReport($id)
    {
        $sql = "DELETE FROM foreman_reports WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function getAllReports()
    {
        $sql = "SELECT * FROM foreman_reports";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}