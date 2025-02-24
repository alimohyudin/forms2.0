<?php
require 'models/WeeklyTimesheetModel.php';
require 'models/JobsModel.php';
require 'models/EmployeesModel.php';
require 'models/HoursWorkedModel.php';

class WeeklyTimesheetController
{
    public function getAll()
    {
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

        //sort timesheets based on week_start_date
        usort($timesheetData, function ($a, $b) {
            return strtotime($b['week_start_date']) - strtotime($a['week_start_date']);
        });

        // Loop through weekly timesheets
        foreach ($timesheetData as $timesheet) {
            $weekStartDate = $timesheet['week_start_date'];
            $weekEndDate = $timesheet['week_end_date'];

            // Filter jobs based on timesheet week range
            $filteredJobs = array_filter($jobsData, function ($job) use ($weekStartDate, $weekEndDate) {
                return $job['week_start_date'] == $weekStartDate;
            });

            // // Get employee data based on filtered job IDs
            $jobIds = array_column($filteredJobs, 'job_id');
            // error_log(json_encode($employeesData));
            // error_log(json_encode($jobIds));
            // $filteredEmployees = array_filter($employeesData, function ($employee) use ($jobIds) {
            //     return in_array($employee['job_id'], $jobIds);
            // });
            // // error_log(json_decode($filteredEmployees));

            // // Group employees by job_id
            // $employeesByJob = [];
            // foreach ($filteredEmployees as $employee) {
            //     $employeeId = $employee['employee_id'];
            //     $employeeHoursWorked = array_filter($hourWorkedData, function ($hoursWorked) use ($employeeId) {
            //         return $hoursWorked['employee_id'] == $employeeId;
            //     });
            //     $employee['hours_worked'] = array_values($employeeHoursWorked);
            //     $employeesByJob[$employee['job_id']][] = $employee;
            // }

            // // Add employees to their respective jobs
            // foreach ($filteredJobs as &$job) {
            //     $jobId = $job['job_id'];
            //     $job['employees'] = isset($employeesByJob[$jobId]) ? $employeesByJob[$jobId] : [];
            // }

            // Add timesheet, jobs, and employee data to response
            $response[] = [
                'timesheet' => $timesheet,
                'jobs' => array_values($filteredJobs),
                'employees' => $employeesModel->countEmployees($jobIds)
                // 'employees' => array_values($filteredEmployees),
            ];
        }

        // Return the data as JSON
        echo json_encode($response);
    }

    public function getSingle($week_start_date)
    {
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
            // echo json_encode(['error' => 'Timesheet not found for the given week start date']);
            // return;
            // If no timesheet found, create a new one
            $timesheetModel = new WeeklyTimesheetModel();
            $week_end_date = date('Y-m-d', strtotime($week_start_date . ' + 6 days'));
            $foreman_name = "";
            $timesheetModel->create(['week_start_date' => $week_start_date, 'week_end_date' => $week_end_date, 'foreman_name' => $foreman_name]);
            $timesheetData = $timesheetModel->getByWeek($week_start_date);
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
        $hoursWorkedData = $hoursWorkedModel->getByWeekAndEmployeeIds($week_start_date, array_column($employeesData, 'employee_id'));

        // Group hours worked by employee_id
        $hoursByEmployee = [];
        foreach ($hoursWorkedData as $hours) {
            $hoursByEmployee[$hours['employee_id']] = [
                'mon' => $hours['mon'],
                'tue' => $hours['tue'],
                'wed' => $hours['wed'],
                'thu' => $hours['thu'],
                'fri' => $hours['fri'],
                'sat' => $hours['sat'],
                'sun' => $hours['sun'],
            ];
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
                $employee['hours_worked'] = isset($hoursByEmployee[$employee['employee_id']]) ? $hoursByEmployee[$employee['employee_id']] : [
                    'mon' => null,
                    'tue' => null,
                    'wed' => null,
                    'thu' => null,
                    'fri' => null,
                    'sat' => null,
                    'sun' => null
                ];
            }
        }

        // Add jobs to the timesheet
        $timesheetData['jobs'] = $jobsData;


        // Return the data as JSON
        echo json_encode($timesheetData);
    }

    public function put($inputData)
    {
        $week_start_date = $inputData['week_start_date'];
        if (!$week_start_date || empty($inputData)) {
            echo json_encode(['error' => 'week_start_date and input data are required']);
            return;
        }

        $timesheetModel = new WeeklyTimesheetModel();
        $timesheetModel->update($inputData);

        $timesheetData = $timesheetModel->getByWeek($week_start_date);
        if (empty($timesheetData)) {
            echo json_encode(['error' => 'Timesheet not found for the given week start date']);
            return;
        }

        $jobsModel = new JobsModel();
        $employeesModel = new EmployeesModel();

        // Fetch existing jobs from the database
        $existingJobsData = $jobsModel->getByWeek($week_start_date);
        $existingJobIds = array_column($existingJobsData, 'job_id');

        // Extract job IDs from inputData
        $inputJobIds = array_column($inputData['jobs'], 'job_id');

        // Delete jobs that are missing from inputData
        foreach ($existingJobIds as $existingJobId) {
            if (!in_array($existingJobId, $inputJobIds)) {
                $jobsModel->delete($existingJobId); // Delete job
                $employeesModel->deleteByJobId($existingJobId); // Delete associated employees
            }
        }

        // Process jobs from inputData
        foreach ($inputData['jobs'] as $inputJob) {
            $jobId = $inputJob['job_id'];

            if (in_array($jobId, $existingJobIds)) {
                // Job exists -> Update
                $jobsModel->update($jobId, ['job_name' => $inputJob['job_name']]);
            } else {
                // Job doesn't exist -> Create new
                $jobsModel->create([
                    'job_id' => $jobId,
                    'week_start_date' => $week_start_date,
                    'job_name' => $inputJob['job_name']
                ]);
            }

            // Fetch existing employees for this job
            $existingEmployeesData = $employeesModel->getByJobId($jobId);
            $existingEmployeeIds = array_column($existingEmployeesData, 'employee_id');
            $inputEmployeeIds = array_column($inputJob['employees'], 'employee_id');

            // Delete employees that are missing in inputData
            foreach ($existingEmployeeIds as $existingEmployeeId) {
                if (!in_array($existingEmployeeId, $inputEmployeeIds)) {
                    $employeesModel->delete($existingEmployeeId);
                }
            }

            // Process employees for this job
            foreach ($inputJob['employees'] as $inputEmployee) {
                if (!empty($inputEmployee['employee_id'])) {
                    $employeeId = $inputEmployee['employee_id'];
                    if (in_array($employeeId, $existingEmployeeIds)) {
                        // Employee exists -> Update
                        $employeesModel->update($employeeId, ['employee_name' => $inputEmployee['employee_name']]);
                    }
                } else {
                    // Employee doesn't exist -> Create new
                    $employeesModel->create([
                        'job_id' => $jobId,
                        'employee_name' => $inputEmployee['employee_name']
                    ]);
                }
            }
        }

        // Fetch the updated timesheet data
        $timesheetData['jobs'] = $jobsModel->getByWeek($week_start_date);

        echo json_encode($timesheetData);
    }

    public function putHoursOnly($inputData)
    {
        $week_start_date = $inputData['week_start_date'];
        if (!$week_start_date || empty($inputData)) {
            echo json_encode(['error' => 'week_start_date and input data are required']);
            return;
        }

        $timesheetModel = new WeeklyTimesheetModel();
        $timesheetData = $timesheetModel->getByWeek($week_start_date);
        if (empty($timesheetData)) {
            echo json_encode(['error' => 'Timesheet not found for the given week start date']);
            return;
        }

        $jobsModel = new JobsModel();
        $employeesModel = new EmployeesModel();
        $hoursWorkedModel = new HoursWorkedModel(); // Create instance of the HoursWorkedModel

        // Fetch existing jobs from the database
        $existingJobsData = $jobsModel->getByWeek($week_start_date);
        $existingJobIds = array_column($existingJobsData, 'job_id');

        // Extract job IDs from inputData
        $inputJobIds = array_column($inputData['jobs'], 'job_id');


        // Process jobs from inputData
        foreach ($inputData['jobs'] as $inputJob) {
            $jobId = $inputJob['job_id'];


            // Fetch existing employees for this job
            $existingEmployeesData = $employeesModel->getByJobId($jobId);
            // Process employees for this job
            foreach ($inputJob['employees'] as $inputEmployee) {
                if (!empty($inputEmployee['employee_id'])) {
                    $employeeId = $inputEmployee['employee_id'];

                    // Handle hours worked for this employee
                    $hoursWorkedData = $inputEmployee['hours_worked'];
                    error_log(json_encode($hoursWorkedData));

                    // Check if hours worked record exists for this employee and week
                    $existingHours = $hoursWorkedModel->getByWeekAndEmployee($week_start_date, $employeeId);
                    error_log(json_encode($existingHours));
                    if ($existingHours) {
                        // Update existing hours worked
                        $hoursWorkedModel->update([
                            'week_start_date' => $week_start_date,
                            'employee_id' => $employeeId,
                            'mon' => $hoursWorkedData["mon"],
                            'tue' => $hoursWorkedData["tue"],
                            'wed' => $hoursWorkedData["wed"],
                            'thu' => $hoursWorkedData["thu"],
                            'fri' => $hoursWorkedData["fri"],
                            'sat' => $hoursWorkedData["sat"],
                            'sun' => $hoursWorkedData["sun"],
                        ]);
                    } else {
                        // Create new hours worked record
                        $hoursWorkedModel->create([
                            'week_start_date' => $week_start_date,
                            'employee_id' => $employeeId,
                            'mon' => $hoursWorkedData["mon"],
                            'tue' => $hoursWorkedData["tue"],
                            'wed' => $hoursWorkedData["wed"],
                            'thu' => $hoursWorkedData["thu"],
                            'fri' => $hoursWorkedData["fri"],
                            'sat' => $hoursWorkedData["sat"],
                            'sun' => $hoursWorkedData["sun"],
                        ]);
                    }
                }
            }
        }

        // Fetch the updated timesheet data
        $timesheetData['jobs'] = $jobsModel->getByWeek($week_start_date);

        echo json_encode($timesheetData);
    }


    public function post()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $model = new WeeklyTimesheetModel();
        $model->create($input);
        echo json_encode(['message' => 'Timesheet created']);
    }
}
?>