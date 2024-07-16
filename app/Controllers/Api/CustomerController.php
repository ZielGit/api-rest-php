<?php

namespace App\Controllers\Api;

use App\Entity\Customer;
use App\Middleware\AuthMiddleware;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class CustomerController
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $normalizers = [
            new DateTimeNormalizer(['datetime_format' => 'Y-m-d H:i:s']),
            new ObjectNormalizer()
        ];
        $encoders = [new JsonEncoder()];
        $this->serializer = new Serializer($normalizers, $encoders);

        // Verificar autenticación
        $auth = new AuthMiddleware();
        $auth->handle();
    }

    public function index()
    {
        $customerRepository = $this->entityManager->getRepository(Customer::class);
        $customers = $customerRepository->findAll();

        $jsonContent = $this->serializer->serialize($customers, 'json');
        echo $jsonContent;
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validar datos de entrada
        foreach (['name', 'last_name', 'email'] as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                http_response_code(400);
                echo json_encode(['message' => "The field $field is required."]);
                return;
            }
        }

        // Validar email
		// if (isset($data["email"]) && !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $data["email"])) {
        //     $json = array(
        //         "status" => 404,
        //         "detail" => "error in the email field"
        //     );
        //     echo json_encode($json, true);
        //     return;
        // }

        $customer = new Customer();
        $customer->setName($data['name']);
        $customer->setLastName($data['last_name']);
        $customer->setEmail($data['email']);
        $customer->setCustomerId($data['customer_id']);
        $customer->setSecretKey($data['secret_key']);

        try {
            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            echo json_encode(['status' => 'Customer created']);
        } catch (UniqueConstraintViolationException $e) {
            http_response_code(409);
            echo json_encode(['message' => 'The email already exists.']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred']);
        }
    }

    public function show($id)
    {
        $customer = $this->entityManager->find(Customer::class, $id);

        if ($customer) {
            $jsonContent = $this->serializer->serialize($customer, 'json');
            echo $jsonContent;
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'customer not found']);
        }
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $customer = $this->entityManager->find(Customer::class, $id);
        if ($customer) {
            $customer->setName($data['name']);
            $customer->setLastName($data['last_name']);
            $customer->setEmail($data['email']);
            $customer->setCustomerId($data['customer_id']);
            $customer->setSecretKey($data['secret_key']);

            $this->entityManager->flush();

            echo json_encode(['status' => 'customer updated']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'customer not found']);
        }
    }

    public function delete($id)
    {
        $customer = $this->entityManager->find(Customer::class, $id);

        if ($customer) {
            $this->entityManager->remove($customer);
            $this->entityManager->flush();

            echo json_encode(['status' => 'customer deleted']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'customer not found']);
        }
    }
}