<?php
require_once('config.php');

class Router
{
    public function route()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        
        error_log('Original URI: ' . $uri);
        
        // Remove BASE_DIRECTORY from URI
        $uri = str_replace(BASE_DIRECTORY, '', $uri);
        
        error_log('Processed URI: ' . $uri);
        
        // Use switch(true) so preg_match() works
        switch (true) {
            case $uri === '/api/login':
            case $uri === '/api/register':
                require_once 'controllers/UsersController.php';
                $controller = new UsersController();
                
                if ($method === 'POST') {
                    if ($uri === '/api/login') {
                        $controller->login();
                    } else {
                        $controller->register();
                    }
                }
                break;

            case preg_match('#^/api/users(?:/(\d+))?/?$#', $uri, $matches):
                error_log('Matches: ' . json_encode($matches));
                
                require_once 'controllers/UsersController.php';
                $userController = new UsersController();

                // Verify token before proceeding
                if (!$userController->verifyToken()) {
                    http_response_code(401);
                    echo json_encode(['error' => 'Unauthorized']);
                    exit;
                }

                $controller = new UsersController();
                $userId = $matches[1] ?? null;

                if ($method === 'GET') {
                    $controller->get();
                } elseif ($method === 'POST') {
                    $controller->register();
                } elseif ($method === 'PUT') {
                    $controller->put();
                } elseif ($method === 'DELETE' && $userId) {
                    $controller->delete($userId);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Invalid request"]);
                }
                break;
            case preg_match("#^/api/employees(?:/(\d+))?/?$#", $uri, $matches):
                error_log("Matches: ". json_encode($matches));
                require_once "controllers/EmployeesController.php";
                $controller = new EmployeesController();
                $userId = $matches[1] ?? null;
                if ($method === "GET") {
                    $controller->get();
                } elseif ($method === "POST") {
                    $controller->post();
                } elseif ($method === "PUT") {
                    $controller->put();
                } elseif ($method === "DELETE" && $userId) {
                    $controller->delete($userId);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Invalid request"]);
                }
                break;

            case preg_match("#^/api/foreman_reports(?:/(\d+))?/?$#", $uri, $matches):
                error_log("Matches: ". json_encode($matches));
                require_once "controllers/ForemanReportController.php";
                $controller = new ForemanReportController();
                $reportId = $matches[1] ?? null;
                if ($method === "GET") {
                    $params = $_GET;

                    if (!empty($params["week_start_date"])) {
                        // error_log('Params: ' . json_encode($params));
                        // $controller->getSingle($params["week_start_date"]);
                        echo json_encode($controller->getReport($params["week_start_date"]));
                    } else {
                        echo json_encode($controller->getAllReports());
                    }
                } elseif ($method === "POST") {
                    $data = json_decode(file_get_contents('php://input'), true);
                    $controller->createReport($data);
                } elseif ($method === "PUT" && $reportId) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    $controller->updateReport($reportId, $data);
                } elseif ($method === "DELETE" && $reportId) {
                    $controller->deleteReport($reportId);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Invalid request"]);
                }
                break;

            case $uri === '/api/dashboard':
                require_once 'controllers/UsersController.php';
                require_once 'controllers/DashboardController.php';

                $userController = new UsersController();
                
                if (!$userController->verifyToken()) {
                    http_response_code(401);
                    echo json_encode(['error' => 'Unauthorized']);
                    exit;
                }

                $controller = new DashboardController();
                
                if ($method === 'GET') {
                    $controller->get();
                }
                break;

            case $uri === '/api/weeklytimesheet':
                require_once 'controllers/UsersController.php';
                require_once 'controllers/WeeklyTimesheetController.php';

                $userController = new UsersController();

                if (!$userController->verifyToken()) {
                    http_response_code(401);
                    echo json_encode(['error' => 'Unauthorized']);
                    exit;
                }

                $controller = new WeeklyTimesheetController();

                if ($method === 'GET') {
                    $params = $_GET;

                    if (!empty($params["week_start_date"])) {
                        error_log('Params: ' . json_encode($params));
                        $controller->getSingle($params["week_start_date"]);
                    } else {
                        $controller->getAll();
                    }
                } elseif ($method === 'POST') {
                    $controller->post();
                } elseif ($method === 'PUT') {
                    $params = $_GET;
                    $input = json_decode(file_get_contents('php://input'), true);

                    if (!empty($params["hours_worked"]) && $params["hours_worked"] === "true") {
                        error_log('Put: Hours worked update: ' . json_encode($input));
                        $controller->putHoursOnly($input);
                    } else {
                        error_log("Put: " . json_encode($input));
                        $controller->put($input);
                    }
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
