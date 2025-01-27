<?php
require 'models/ResourceModel.php';

class ResourceController {
    public function get() {        
        $model = new ResourceModel();
        $data = $model->getAll();
        echo json_encode($data);
    }

    public function post() {
        $input = json_decode(file_get_contents('php://input'), true);
        $model = new ResourceModel();
        $model->create($input);
        echo json_encode(['message' => 'Resource created']);
    }
}
?>