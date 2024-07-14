<?php

namespace App\Controllers\Api;

use App\Entity\Course;
// use App\Entity\Customer;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class CourseController
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

        $courseRepository = $this->entityManager->getRepository(Course::class);
        $courses = $courseRepository->findAll();

        $jsonContent = $this->serializer->serialize($courses, 'json');
        echo $jsonContent;

        // if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        //     foreach ($customers as $key => $value) {
        //         if (
        //             base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
        //             base64_encode($value["customer_id"] . ":" . $value["secret_key"])
        //         ) {
        //             $courses = $this->course->index();
        //             $json = array(
        //                 "status" => 200,
        //                 "total_records" => count($courses),
        //                 "detail" => $courses
        //             );
        //             echo json_encode($json, true);
        //             return;
        //         }
        //     }
        // }
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
        foreach (['title', 'description', 'instructor', 'image', 'price', 'creator_id'] as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                http_response_code(400);
                echo json_encode(['message' => "The field $field is required."]);
                return;
            }
        }

        $course = new Course();
        $course->setTitle($data['title']);
        $course->setDescription($data['description']);
        $course->setInstructor($data['instructor']);
        $course->setImage($data['image']);
        $course->setPrice($data['price']);
        $course->setCreatorId($data['creator_id']);
        $course->setCreatedAt($data['created_at']);
        $course->setUpdatedAt($data['updated_at']);

        try {
            $this->entityManager->persist($course);
            $this->entityManager->flush();

            echo json_encode(['status' => 'Course created']);
        } catch (UniqueConstraintViolationException $e) {
            http_response_code(409);
            echo json_encode(['message' => 'Title or description already exists.']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred']);
        }
        
        // if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        //     foreach ($customers as $key => $valueCustomer) {
        //         if (
        //             base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
        //             base64_encode($valueCustomer["customer_id"] . ":" . $valueCustomer["secret_key"])
        //         ) {
        //             $courses = $this->course->index();    

        //             // Llevar data al modelo
        //             $data = array(
        //                 "title" => $data["title"],
        //                 "description" => $data["description"],
        //                 "instructor" => $data["instructor"],
        //                 "image" => $data["image"],
        //                 "price" => $data["price"],
        //                 "creator_id" => $valueCustomer["id"],
        //                 "created_at" => date('Y-m-d h:i:s'), // es un timestamp
        //                 "updated_at" => date('Y-m-d h:i:s') // es un timestamp
        //             );

        //             $create = $this->course->create($data);

        //             // Respuesta del modelo
        //             if ($create == "ok") {
        //                 $json = array(
        //                     "status" => 200,
        //                     "detail" => "Registration successful, your course has been saved"
        //                 );
        //                 echo json_encode($json, true);
        //                 return;
        //             }
        //         }
        //     }
        // }
        // $json = array(
        //     "detail" => "you are in the create view"
        // );
        // echo json_encode($json, true);
        // return;
    }

    public function show($id)
    {
        // Validar credenciales del cliente
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

        $course = $this->entityManager->find(Course::class, $id);

        if ($course) {
            $jsonContent = $this->serializer->serialize($course, 'json');
            echo $jsonContent;
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Course not found']);
        }

        // if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        //     foreach ($customers as $key => $valueCustomer) {
        //         if (
        //             base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
        //             base64_encode($valueCustomer["customer_id"] . ":" . $valueCustomer["secret_key"])
        //         ) {
        //             // Mostrar todos los courses
        //             $curso = $this->course->show("courses", "customers", $id);
        //             if (!empty($curso)) {
        //                 $json = array(
        //                     "status" => 200,
        //                     "detail" => $curso
        //                 );
        //                 echo json_encode($json, true);
        //                 // return;
        //             } else {
        //                 $json = array(
        //                     "status" => 200,
        //                     "total_records" => 0,
        //                     "details" => "There is no registered course"
        //                 );
        //                 echo json_encode($json, true);
        //                 // return;
        //             }
        //         }
        //     }
        // }
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

        $course = $this->entityManager->find(Course::class, $id);
        if ($course) {
            $course->setTitle($data['title']);
            $course->setDescription($data['description']);
            $course->setInstructor($data['instructor']);
            $course->setImage($data['image']);
            $course->setPrice($data['price']);
            $course->setCreatorId($data['creator_id']);
            $course->setCreatedAt($data['created_at']);
            $course->setUpdatedAt($data['updated_at']);

            $this->entityManager->flush();

            echo json_encode(['status' => 'Course updated']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Course not found']);
        }

        // if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        //     foreach ($customers as $key => $valueCustomer) {
        //         if (
        //             "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
        //             "Basic " . base64_encode($valueCustomer["customer_id"] . ":" . $valueCustomer["secret_key"])
        //         ) {
        //             // Validar id creador
        //             $curso = $this->course->show($id);
        //             foreach ($curso as $key => $valueCourse) {
        //                 if ($valueCourse->creator_id == $valueCustomer["id"]) {
        //                     // Llevar data al modelo
        //                     $data = array(
        //                         "id" => $id,
        //                         "title" => $data["title"],
        //                         "description" => $data["description"],
        //                         "instructor" => $data["instructor"],
        //                         "image" => $data["image"],
        //                         "price" => $data["price"],
        //                         "updated_at" => date('Y-m-d h:i:s')
        //                     );

        //                     $update = $this->course->update($data);
        //                     if ($update == "ok") {
        //                         $json = array(
        //                             "status" => 200,
        //                             "detail" => "Registration successful, your course has been updated"
        //                         );
        //                         echo json_encode($json, true);
        //                         return;
        //                     } else {
        //                         $json = array(
        //                             "status" => 404,
        //                             "detail" => "You are not authorized to modify this course"
        //                         );
        //                         echo json_encode($json, true);
        //                         return;
        //                     }
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

        $course = $this->entityManager->find(Course::class, $id);

        if ($course) {
            $this->entityManager->remove($course);
            $this->entityManager->flush();

            echo json_encode(['status' => 'Course deleted']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Course not found']);
        }

        // if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        //     foreach ($customers as $key => $valueCustomer) {
        //         if (
        //             "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
        //             "Basic " . base64_encode($valueCustomer["customer_id"] . ":" . $valueCustomer["secret_key"])
        //         ) {
        //             // Validar id creador
        //             $curso = $this->course->show($id);
        //             foreach ($curso as $key => $valueCourse) {
        //                 if ($valueCourse->creator_id == $valueCustomer["id"]) {
        //                     // Llevar data al modelo
        //                     $delete = $this->course->delete($id);
        //                     if ($delete == "ok") {
        //                         $json = array(
        //                             "status" => 200,
        //                             "detail" => "the course has been deleted"
        //                         );
        //                         echo json_encode($json, true);
        //                         return;
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
    }
}