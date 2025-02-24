<?php

// require 'models/UsersModel.php';//already required so giving error in dashboard api
require 'models/EmployeesModel.php';
require 'models/WeeklyTimesheetModel.php';
require 'models/JobsModel.php';

class DashboardController
{
    public function get()
    {
        $usersModel = new UsersModel();
        $users = $usersModel->getAll();
        $totalUsers = count($users);

        $employeesModel = new EmployeesModel();
        $totalEmployees = $employeesModel->countEmployees();
        // $totalEmployees = count($employees);

        $weeklyTimesheetModel = new WeeklyTimesheetModel();
        $weeklyTimesheets = $weeklyTimesheetModel->getAll();
        $totalWeeklyTimesheets = count($weeklyTimesheets);


        // get total number of employees


        echo json_encode(['totalUsers' => $totalUsers, 'totalEmployees' => $totalEmployees, 'totalWeeklyTimesheets' => $totalWeeklyTimesheets]);

    }
}
?>