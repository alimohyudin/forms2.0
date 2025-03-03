<?php

require_once 'models/ForemanReportModel.php';

class ForemanReportController
{
    private $model;

    public function __construct()
    {
        $this->model = new ForemanReportModel();
    }

    public function createReport($data)
    {
        $this->model->createReport($data);
    }

    // public function getReport($id)
    // {
    //     return $this->model->getReport($id);
    // }

    public function getReport($week_start_date)
    {
        $data = $this->model->getByWeek($week_start_date);

        if ($data) {
            return $data;
        } else {
            //create a new report using the report from date 1999-02-01
            $data = $this->model->getByWeek('1999-02-01');
            $data['report_date'] = $week_start_date;
            unset($data['id']);
            $this->model->createReport($data);
            return $this->model->getByWeek($week_start_date);
        }
        
    }

    public function updateReport($id, $data)
    {
        $this->model->updateReport($id, $data);
    }

    public function deleteReport($id)
    {
        $this->model->deleteReport($id);
    }

    public function getAllReports()
    {
        return $this->model->getAllReports();
    }
}