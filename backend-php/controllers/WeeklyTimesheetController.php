<?php
require 'models/WeeklyTimesheetModel.php';
require 'models/JobsModel.php';
require 'models/EmployeesModel.php';

class WeeklyTimesheetController {
    public function get() {
        // Fetch data from WeeklyTimesheetModel
        $timesheetModel = new WeeklyTimesheetModel();    
        $timesheetData = $timesheetModel->getAll();
    
        // Fetch data from JobsModel
        $jobsModel = new JobsModel();
        $jobsData = $jobsModel->getAll();
    
        // Fetch data from EmployeesModel
        $employeesModel = new EmployeesModel();
        $employeesData = $employeesModel->getAll();
    
        // Prepare final response data
        $response = [];
    
        // Loop through weekly timesheets
        foreach ($timesheetData as $timesheet) {
            $weekStartDate = $timesheet['week_start_date'];
            $weekEndDate = $timesheet['week_end_date'];
    
            // Filter jobs based on timesheet week range
            $filteredJobs = array_filter($jobsData, function ($job) use ($weekStartDate, $weekEndDate) {
                return $job['week_start_date'] >= $weekStartDate ;
            });
    
            // Get employee data based on filtered job IDs
            $jobIds = array_column($filteredJobs, 'job_id');
            $filteredEmployees = array_filter($employeesData, function ($employee) use ($jobIds) {
                return in_array($employee['job_id'], $jobIds);
            });
    
            // Add timesheet, jobs, and employee data to response
            $response[] = [
                'timesheet' => $timesheet,
                'jobs' => array_values($filteredJobs),
                'employees' => array_values($filteredEmployees)
            ];
        }
    
        // Return the data as JSON
        echo json_encode($response);
    }
    
    
    public function post() {
        $input = json_decode(file_get_contents('php://input'), true);
        $model = new WeeklyTimesheetModel();
        $model->create($input);
        echo json_encode(['message' => 'Timesheet created']);
    }
}
?>
