<?php
require_once('config.php');

require 'models/UsersModel.php';
require_once 'vendors/jwt/src/JWTExceptionWithPayloadInterface.php';
require_once 'vendors/jwt/src/BeforeValidException.php';
require_once 'vendors/jwt/src/ExpiredException.php';
require_once 'vendors/jwt/src/SignatureInvalidException.php';
require_once 'vendors/jwt/src/Key.php';
require_once 'vendors/jwt/src/JWT.php';


use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class UsersController
{
    public function register()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['username']) || empty($input['password'])) {
            echo json_encode(['error' => 'Username and password are required']);
            return;
        }

        $username = $input['username'];
        $password = $input['password'];

        $usersModel = new UsersModel();
        $existingUser = $usersModel->getByUsername($username);

        if ($existingUser) {
            echo json_encode(['error' => 'Username already exists']);
            return;
        }

        $usersModel->create(['username' => $username, 'password' => $password]);
        echo json_encode(['message' => 'User registered successfully']);
    }

    public function login()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        // error_log("1131- Input: ".json_encode($input));
        // error_log("1131- Input: ".json_encode($input['username']));
        if (empty($input['username']) || empty($input['password'])) {
            echo json_encode(['error' => 'Username and password are required']);
            return;
        }

        $username = $input['username'];
        $password = $input['password'];

        $usersModel = new UsersModel();
        $user = $usersModel->getByUsername($username);

        // error_log("1131 - User: ".json_encode($user));
        // error_log(password_verify($password, $user['password']) ? 'true' : 'false');
        if (!$user || !password_verify($password, $user['password'])) {
            echo json_encode(['error' => 'Invalid username or password']);
            return;
        }

        $key = JWT_KEY;
        $token = array(
            "iss" => "https://tclmunderground.com",
            "aud" => "https://tclmunderground.com",
            "sub" => $user['user_id'],
            "iat" => 1356999524,
            "nbf" => 1357000000
        );
        $jwtToken = JWT::encode($token, $key, 'HS256');
        // $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        // print_r($decoded);

        //delete password key
        unset($user['password']);
        unset($user['created_at']);
        unset($user['updated_at']);
        unset($user['deleted_at']);

        echo json_encode(['message' => 'Login successful', 'token' => $jwtToken, 'user' => $user]);
    }

    public function verifyToken()
    {
        //get token from header
        $headers = getallheaders();
        error_log(json_encode($headers));
        if (!isset($headers["Authorization"])) {
            // http_response_code(401);
            // echo json_encode(['error' => 'Unauthorized']);
            error_log("Authorization header doesn't exist");
            return false;
        }
        $token = $headers["Authorization"];
        error_log("Token: ".$token);
        $token = str_replace("Bearer ", "", $token);
        $token = str_replace("bearer ", "", $token);
        $token = trim($token);
        $token = str_replace('"', "", $token);

        $key = JWT_KEY;

        try {
            JWT::decode($token, new Key($key, 'HS256'));
            error_log("Token verified");
            return true;
            
        } catch (Exception $e) {
            error_log("Token verification failed");
            error_log($e->getMessage());
            return false;
        }
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        echo json_encode(['message' => 'Logged out']);
    }
}
?>