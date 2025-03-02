<?php
require_once('config.php');

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Week Overview table
    $sql = "
    CREATE TABLE IF NOT EXISTS weekly_timesheet (
        week_start_date DATE NOT NULL,
        week_end_date DATE NOT NULL,
        foreman_name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        PRIMARY KEY (week_start_date)
    )";
    $pdo->exec(statement: $sql);

    // Create Foreman Report table
    $sql = "
    CREATE TABLE foreman_reports (
        id INT AUTO_INCREMENT PRIMARY KEY,
        report_date DATE,
        foreman_name VARCHAR(255),
        project_name VARCHAR(255),
        weather_condition VARCHAR(50),
        safety_meeting BOOLEAN,
        soil_condition VARCHAR(50),

        work_performed_today JSON,
        problems_delays JSON,
        equipment_rented JSON,
        trucks JSON,
        equipment JSON
    )";
    $pdo->exec($sql);

    //insert data into foreman_reports
    $workPerformed = json_encode([["task" => "Installed 5 sewer service"]]);
    $trucksUsed = json_encode([
        ["name" => "Brian Brewer 2004 Ford F350 (red) Diesel", "hours" => 8],
        ["name" => "Juan Arroyo 2023 F250 Ford Diesel", "hours" => null]
    ]);

    $sql = "INSERT INTO foreman_reports (
            report_date, foreman_name, project_name, weather_condition, safety_meeting, soil_condition, 
            work_performed_today, problems_delays, equipment_rented, trucks, equipment
        ) VALUES (
            '2024-02-01', 'Elman', '9418', 'Hot', TRUE, NULL,
            '$workPerformed',
            '[]',
            '[]',
            '$trucksUsed',
            '[]'
        );";
    $pdo->exec(statement: $sql);

    // Create Jobs table
    $sql = "
    CREATE TABLE IF NOT EXISTS jobs (
        job_id INT AUTO_INCREMENT PRIMARY KEY,
        week_start_date DATE NOT NULL,
        job_name VARCHAR(255),
        type BOOLEAN DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        FOREIGN KEY (week_start_date) REFERENCES weekly_timesheet(week_start_date)
            ON DELETE CASCADE ON UPDATE CASCADE
    )";
    $pdo->exec($sql);

    // Create Employees table
    $sql = "
    CREATE TABLE IF NOT EXISTS employees (
        employee_id INT AUTO_INCREMENT PRIMARY KEY,
        employee_name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL
    )";
    $pdo->exec($sql);

    // Create Employees Jobs table
    $sql = "
    CREATE TABLE IF NOT EXISTS employees_jobs (
        emp_job_id INT AUTO_INCREMENT PRIMARY KEY,
        job_id INT NOT NULL,
        employee_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
            ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (job_id) REFERENCES jobs(job_id)
            ON DELETE CASCADE ON UPDATE CASCADE
        
    )";
    $pdo->exec($sql);

    // Create Hours Worked table
    $sql = "
    CREATE TABLE IF NOT EXISTS hours_worked (
        id INT AUTO_INCREMENT PRIMARY KEY,
        week_start_date DATE NOT NULL,
        emp_job_id INT NOT NULL,
        mon DECIMAL(4,2) DEFAULT 0,
        tue DECIMAL(4,2) DEFAULT 0,
        wed DECIMAL(4,2) DEFAULT 0,
        thu DECIMAL(4,2) DEFAULT 0,
        fri DECIMAL(4,2) DEFAULT 0,
        sat DECIMAL(4,2) DEFAULT 0,
        sun DECIMAL(4,2) DEFAULT 0,
        total DECIMAL(4,2) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        UNIQUE (week_start_date, emp_job_id),
        FOREIGN KEY (emp_job_id) REFERENCES employees_jobs(emp_job_id) 
            ON DELETE CASCADE ON UPDATE CASCADE
    )";
    $pdo->exec($sql);

    // Create Users table
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL
    )";
    $pdo->exec($sql);

    echo "Tables created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

/**
 * This script creates the necessary database tables for the application.
 * Run this script once after setting up the database configuration in config.php.
 * 
 * lets write down what is needed for the two forms we have
 * 
 * Form 1:
 * week start date, end date, foreman name
 * job names, each job has employees, each employee can work from mon to sunday and we only need to know the hours worked
 */

?>