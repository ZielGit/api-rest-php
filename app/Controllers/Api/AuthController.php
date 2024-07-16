<?php

namespace App\Controllers\Api;

use App\Entity\User;
use Config\PHPJWT;
use Doctrine\ORM\EntityManager;

class AuthController
{
    private $entityManager;
    private $PHPJWT;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->PHPJWT = new PHPJWT();
    }

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Aquí deberías verificar las credenciales del usuario (email y password)
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials']);
            return;
        }

        // Generar token JWT
        $token = $this->PHPJWT->generateToken([
            'user_id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);

        echo json_encode([
            'message' => 'Hi '. $user->getName(),
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function register()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validar los datos
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Missing required fields']);
            return;
        }

        // Verificar si el usuario ya existe
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            http_response_code(409);
            echo json_encode(['message' => 'Email already in use']);
            return;
        }

        // Hash de la contraseña
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Crear un nuevo usuario
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($hashedPassword);

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            http_response_code(201);
            echo json_encode(['message' => 'User registered successfully']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred']);
        }
    }

    public function logout()
    {
        // Despues crear una black list en el AuthMiddleware para los tokens
        echo json_encode(['message' => 'Logged out successfully']);
    }
}