<?php

namespace App\Middleware;

use Config\PHPJWT;
use Firebase\JWT\ExpiredException;

class AuthMiddleware
{
    private $PHPJWT;

    public function __construct()
    {
        $this->PHPJWT = new PHPJWT();
    }

    public function handle()
    {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Authorization header not found']);
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            $this->PHPJWT->validateToken($token);
        } catch (ExpiredException $e) {
            http_response_code(401);
            echo json_encode(['message' => 'Token has expired']);
            exit;
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid token']);
            exit;
        }
    }
}