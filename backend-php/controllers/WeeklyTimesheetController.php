<?php
require 'models/WeeklyTimesheetModel.php';
require 'models/JobsModel.php';
require 'models/EmployeesModel.php';
require 'models/HoursWorkedModel.php';

class WeeklyTimesheetController {
    public function getAll() {
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

    public function getSingle($week_start_date){
        if (!$week_start_date) {
            echo json_encode(['error' => 'week_start_date is required']);
            return;
        }
    
        // Fetch data for the given week_start_date from the WeeklyTimesheetModel
        $timesheetModel = new WeeklyTimesheetModel();
        $timesheetData = $timesheetModel->getByWeek($week_start_date);

        error_log(json_encode($timesheetData));
        
        // Check if timesheet data exists
        if (empty($timesheetData)) {
            echo json_encode(['error' => 'Timesheet not found for the given week start date']);
            return;
        }
        
        // Fetch jobs related to the timesheet's week_start_date
        $jobsModel = new JobsModel();
        $jobsData = $jobsModel->getByWeek($week_start_date);
        
        // If no jobs found for this timesheet, return empty response
        if (empty($jobsData)) {
            echo json_encode(['error' => 'No jobs found for the given timesheet']);
            return;
        }
        
        // Preprocess employees and hours worked data for the relevant jobs
        $employeesModel = new EmployeesModel();
        //print array_column($jobsData, 'job_id')
        $employeesData = $employeesModel->getByJobs(array_column($jobsData, 'job_id'));
        
        // Fetch hours worked data for employees
        $hoursWorkedModel = new HoursWorkedModel();
        $hoursWorkedData = $hoursWorkedModel->getByEmployeeIds(array_column($employeesData, 'employee_id'));
    
        // Group hours worked by employee_id
        $hoursByEmployee = [];
        foreach ($hoursWorkedData as $hours) {
            $hoursByEmployee[$hours['employee_id']][] = $hours;
        }
    
        // Group employees by job_id
        $employeesByJob = [];
        foreach ($employeesData as $employee) {
            $employeesByJob[$employee['job_id']][] = $employee;
        }
    
        // Now prepare the final nested data structure
        foreach ($jobsData as &$job) {
            // Get employees for this job
            $job['employees'] = isset($employeesByJob[$job['job_id']]) ? $employeesByJob[$job['job_id']] : [];
            
            foreach ($job['employees'] as &$employee) {
                // Get hours worked for this employee
                $employee['hours_worked'] = isset($hoursByEmployee[$employee['employee_id']]) ? $hoursByEmployee[$employee['employee_id']] : [];
            }
        }
    
        // Add jobs to the timesheet
        $timesheetData['jobs'] = $jobsData;
        
        // Return the data as JSON
        echo json_encode($timesheetData);        
    }
    
    public function put($inputData) {
        $week_start_date = $inputData['week_start_date'];
        if (!$week_start_date || empty($inputData)) {
            echo json_encode(['error' => 'week_start_date and input data are required']);
            return;
        }
    
        // Fetch data for the given week_start_date from the WeeklyTimesheetModel
        $timesheetModel = new WeeklyTimesheetModel();
        
        $timesheetModel->update($inputData);

        $timesheetData = $timesheetModel->getByWeek($week_start_date);

        // update timesheet data
        
        
        // Check if the timesheet exists
        if (empty($timesheetData)) {
            echo json_encode(['error' => 'Timesheet not found for the given week start date']);
            return;
        }
        
        // Fetch jobs related to the timesheet's week_start_date
        $jobsModel = new JobsModel();
        $existingJobsData = $jobsModel->getByWeek($week_start_date);
        
        // Create an array to keep track of updated jobs
        $updatedJobs = [];
        
        // Now handle updating the employees
        $employeesModel = new EmployeesModel();
        $hoursWorkedModel = new HoursWorkedModel();
    
        // Loop through the updated jobs and process employees
        foreach($inputData['jobs'] as $inputJob){
            $jobId = $inputJob['job_id'];
            $jobExists = false;

            $jobData = $jobsModel->getById($jobId);
            if($jobData){
                $jobData['job_name'] = $inputJob['job_name'];
                $jobsModel->update($jobId, $jobData);
                $jobExists = true;
            }
            if(!$jobExists){
                $newJob = [
                    'job_id' => $jobId,
                    'week_start_date' => $week_start_date,
                    'job_name' => $inputJob['job_name']
                ];
                $jobsModel->create($newJob);
            }
            
            // Now handle updating the employees for this job
            foreach($inputJob['employees'] as $inputEmployee){

                if(isset($inputEmployee['employee_id'])){
                    $employeeId = $inputEmployee['employee_id'];
                    $employeeName = $inputEmployee['employee_name'];
                    $jobId = $inputJob['job_id'];

                    $employeeData = $employeesModel->getById($employeeId);
                    if($employeeData){
                        $employeeData['employee_name'] = $employeeName;
                        $employeesModel->update($employeeId, $employeeData);
                    }
                }else{
                    $newEmployee = [
                        'job_id' => $jobId,
                        'employee_name' => $inputEmployee['employee_name']
                    ];
                    $employeesModel->create($newEmployee);
                }
            }
        }
    
        // Update the timesheet with the new jobs
        $timesheetData['jobs'] = $jobsModel->getByWeek($week_start_date);
        // $timesheetModel->update( $timesheetData);  // Assuming update method exists
    
        // Return the updated data as JSON
        echo json_encode($timesheetData);
    }
    
    
    public function post() {
        $input = json_decode(file_get_contents('php://input'), true);
        $model = new WeeklyTimesheetModel();
        $model->create($input);
        echo json_encode(['message' => 'Timesheet created']);
    }
}
?>
