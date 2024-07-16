<?php

namespace App\Controllers\Api;

use App\Entity\Course;
use App\Middleware\AuthMiddleware;
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

        // Verificar autenticación
        $auth = new AuthMiddleware();
        $auth->handle();
    }

    public function index()
    {
        $courseRepository = $this->entityManager->getRepository(Course::class);
        $courses = $courseRepository->findAll();

        $jsonContent = $this->serializer->serialize($courses, 'json');
        echo $jsonContent;
    }

    public function create()
    {
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
    }

    public function show($id)
    {
        $course = $this->entityManager->find(Course::class, $id);

        if ($course) {
            $jsonContent = $this->serializer->serialize($course, 'json');
            echo $jsonContent;
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Course not found']);
        }
    }

    public function update($id)
    {
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
    }

    public function delete($id)
    {
        $course = $this->entityManager->find(Course::class, $id);

        if ($course) {
            $this->entityManager->remove($course);
            $this->entityManager->flush();

            echo json_encode(['status' => 'Course deleted']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Course not found']);
        }
    }
}