<?php
require_once('config.php');
// require_once 'path/to/ResourceModel.php';

class Router
{
    public function route()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        error_log('URI: ' . $uri);
        // remove BASE_DIRECTORY from URI
        $uri = str_replace(BASE_DIRECTORY, '', $uri);
        error_log('URI: ' . $uri);
        switch ($uri) {
            case '/api/login':
                require 'controllers/UsersController.php';
                $controller = new UsersController();
                if ($method == 'POST') {
                    $controller->login();
                }
                break;
            case '/api/register':
                require 'controllers/UsersController.php';
                $controller = new UsersController();
                if ($method == 'POST') {
                    $controller->register();
                }
                break;
            case '/api/dashboard':
                // verify token:
                require 'controllers/UsersController.php';
                $userController = new UsersController();
                $isVerified = $userController->verifyToken();
                if (!$isVerified) {
                    http_response_code(401);
                    echo json_encode(['error' => 'Unauthorized']);
                    exit(0);
                }
                require 'controllers/DashboardController.php';
                $controller = new DashboardController();
                if ($method == 'GET') {
                    $controller->get();
                }
                break;
            case '/api/weeklytimesheet':
                // verify token:
                require 'controllers/UsersController.php';
                $userController = new UsersController();
                $isVerified = $userController->verifyToken();
                if (!$isVerified) {
                    http_response_code(401);
                    echo json_encode(['error' => 'Unauthorized']);
                    exit(0);
                }


                require 'controllers/WeeklyTimesheetController.php';
                $controller = new WeeklyTimesheetController();
                if ($method == 'GET') {
                    //if has query parameters
                    $params = $_GET;
                    if (count($params) > 0) {
                        error_log('Params: ' . json_encode($params));
                        $controller->getSingle($params["week_start_date"]);
                    } else {
                        $controller->getAll();
                    }
                } elseif ($method == 'POST') {
                    $controller->post();
                } elseif ($method == 'PUT') {
                    $params = $_GET;
                    $input = json_decode(file_get_contents('php://input'), true);
                    if(count($params) > 0) {
                        error_log('Put: Params: ' . json_encode($params));
                        if($params["hours_worked"] == "true") {
                            $controller->putHoursOnly($input);
                        }
                    } else {
                        error_log("Put: " . json_encode($input));
                        $controller->put($input);
                    }

                    
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