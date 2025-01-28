<?php
require 'models/WeeklyTimesheetModel.php';
require 'models/JobsModel.php';
require 'models/EmployeesModel.php';
require 'models/HoursWorkedModel.php';

class WeeklyTimesheetController {
    public function get(week_start_date = null) {
        // Fetch data from WeeklyTimesheetModel
        $timesheetModel = new WeeklyTimesheetModel();    
        $timesheetData = $timesheetModel->getAll();
    
        // Fetch data from JobsModel
        $jobsModel = new JobsModel();
        $jobsData = $jobsModel->getAll();
    
        // Fetch data from EmployeesModel
        $employeesModel = new EmployeesModel();
        $employeesData = $employeesModel->getAll();

        // Fetch data from HoursworkedModel
        $hourWorkedModel = new HoursWorkedModel();
        $hourWorkedData = $hourWorkedModel->getAll();
    
        // Prepare final response data
        $response = [];
    
        // Loop through weekly timesheets
        foreach ($timesheetData as $timesheet) {
            $weekStartDate = $timesheet['week_start_date'];
            $weekEndDate = $timesheet['week_end_date'];
    
            // Filter jobs based on timesheet week range
            $filteredJobs = array_filter($jobsData, function ($job) use ($weekStartDate, $weekEndDate) {
                return $job['week_start_date'] >= $weekStartDate;
            });
    
            // Get employee data based on filtered job IDs
            $jobIds = array_column($filteredJobs, 'job_id');
            $filteredEmployees = array_filter($employeesData, function ($employee) use ($jobIds) {
                return in_array($employee['job_id'], $jobIds);
            });
            
            // Group employees by job_id
            $employeesByJob = [];
            foreach ($filteredEmployees as $employee) {
                $employeeId = $employee['employee_id'];
                $employeeHoursWorked = array_filter($hourWorkedData, function ($hoursWorked) use ($employeeId) {
                    return $hoursWorked['employee_id'] == $employeeId;
                });
                $employee['hours_worked'] = array_values($employeeHoursWorked);
                $employeesByJob[$employee['job_id']][] = $employee;
            }
    
            // Add employees to their respective jobs
            foreach ($filteredJobs as &$job) {
                $jobId = $job['job_id'];
                $job['employees'] = isset($employeesByJob[$jobId]) ? $employeesByJob[$jobId] : [];
            }
    
            // Add timesheet, jobs, and employee data to response
            $response[] = [
                'timesheet' => $timesheet,
                'jobs' => array_values($filteredJobs)
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
