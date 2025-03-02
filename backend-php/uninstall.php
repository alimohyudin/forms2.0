<?php
require_once('config.php');

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Drop tables in reverse order of creation to handle foreign key constraints
    $tables = ['users', 'hours_worked', 'employees', 'employees_jobs', 'jobs', 'weekly_timesheet', 'foreman_reports'];

    foreach ($tables as $table) {
        $sql = "DROP TABLE IF EXISTS $table";
        $pdo->exec($sql);
    }

    echo "Tables dropped successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

/**
 * This script drops the database tables created by install.php.
 * Run this script if you need to remove the tables from the database.
 */

?>