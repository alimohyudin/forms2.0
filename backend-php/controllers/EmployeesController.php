<?php

require_once 'models/EmployeesModel.php';

class EmployeesController {

    private $employeesModel;

    public function __construct() {
        $this->employeesModel = new EmployeesModel();
    }

    public function get() {
        $employees = $this->employeesModel->getAll();
        echo json_encode($employees);
    }

    public function post() {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $this->employeesModel->create($input);
        echo json_encode(['message' => 'Employee created', 'id' => $id]);
    }

    public function put() {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['employee_id'];
        $this->employeesModel->update($id, $input);
        echo json_encode(['message' => 'Employee updated']);
    }

    public function delete($employeeId) {
        $this->employeesModel->delete($employeeId);
        echo json_encode(['message' => 'Employee deleted']);
    }
}

