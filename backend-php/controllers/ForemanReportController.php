<?php

require_once 'models/ForemanReportModel.php';

class ForemanReportController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new ForemanReportModel($db);
    }

    public function createReport($data)
    {
        $this->model->createReport($data);
    }

    public function getReport($id)
    {
        return $this->model->getReport($id);
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