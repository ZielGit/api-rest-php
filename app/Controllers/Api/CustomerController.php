<?php

namespace App\Controllers\Api;

use App\Entity\Customer;
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
    }

    public function index()
    {
        // Validar credenciales del cliente
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

        $customerRepository = $this->entityManager->getRepository(Customer::class);
        $customers = $customerRepository->findAll();

        $jsonContent = $this->serializer->serialize($customers, 'json');
        echo $jsonContent;
    }

    public function create()
    {
        // Validar credenciales del cliente
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);


        // Validar datos de entrada
        foreach (['name', 'last_name', 'email'] as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                http_response_code(400);
                echo json_encode(['message' => "The field $field is required."]);
                return;
            }
        }

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

        // // Validar email
		// if (isset($data["email"]) && !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $data["email"])) {
        //     $json = array(
        //         "status" => 404,
        //         "detail" => "error in the email field"
        //     );
        //     echo json_encode($json, true);
        //     return;
        // }

        // // Generar credenciales del cliente
        // $customer_id= str_replace("$","c",crypt($data["name"].$data["last_name"].$data["email"] ,'$2a$07$afartwetsdAD52356FEDGsfhsd$'));
        // $secret_key= str_replace("$","a",crypt($data["email"].$data["last_name"].$data["name"] ,'$2a$07$afartwetsdAD52356FEDGsfhsd$'));

        // $data = array(
        //     "name" => $data["name"],
        //     "last_name" => $data["last_name"],
        //     "email" => $data["email"],
        //     "customer_id" => $customer_id,
        //     "secret_key" => $secret_key,
        //     "created_at" => date('Y-m-d h:i:s'),
        //     "updated_at" => date('Y-m-d h:i:s')
		// );

        // $create = $this->customer->create($data);

        // if ($create == "ok") {
        //     $json = array(
        //         "status" => 404,
        //         "detail" => "your credentials are generated",
        //         "customer_id" => $customer_id,
        //         "secret_key" => $secret_key
        //     );
        //     echo json_encode($json, true);
        //     return;
        // }
    }

    public function show($id)
    {
        // Validar credenciales del cliente
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

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
        // Validar credenciales del cliente
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

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

        // if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        //     foreach ($customers as $key => $valueCustomer) {
        //         if (
        //             "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
        //             "Basic " . base64_encode($valueCustomer["customer_id"] . ":" . $valueCustomer["secret_key"])
        //         ) {
        //             // Validar id creador
        //             $curso = $this->customer->show($id);
        //             foreach ($curso as $key => $valuecustomer) {
        //                 if ($valuecustomer->creator_id == $valueCustomer["id"]) {
        //                     // Llevar data al modelo
        //                     $data = array(
        //                         "id" => $id,
        //                         "title" => $data["title"],
        //                         "description" => $data["description"],
        //                         "instructor" => $data["instructor"],
        //                         "image" => $data["image"],
        //                         "price" => $data["price"]
        //                     );
        //                 }
        //             }
        //         }
        //     }
        // }
    }

    public function delete($id)
    {
        // Validar credenciales del cliente
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

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