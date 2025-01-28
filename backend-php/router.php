<?php
require_once('config.php');
// require_once 'path/to/ResourceModel.php';

class Router {
    public function route() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        error_log('URI: ' . $uri);
        // remove BASE_DIRECTORY from URI
        $uri = str_replace(BASE_DIRECTORY, '', $uri);
        error_log('URI: ' . $uri);
        switch ($uri) {
            case '/api/weeklytimesheet':
                require 'controllers/WeeklyTimesheetController.php';
                $controller = new WeeklyTimesheetController();
                if ($method == 'GET') {
                    $controller->get();
                } elseif ($method == 'POST') {
                    $controller->post();
                }
                break;
            case '/api/resource':
                error_log('Resource');
                require 'controllers/ResourceController.php';
                $controller = new ResourceController();
                if ($method == 'GET') {
                    $controller->get();
                } elseif ($method == 'POST') {
                    $controller->post();
                }
                break;
            default:
                http_response_code(404);
                echo json_encode(['message' => 'Not Found']);
                break;
        }
    }
}
?>