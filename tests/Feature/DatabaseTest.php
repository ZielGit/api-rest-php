<?php

use PHPUnit\Framework\TestCase;
use Config\Database;

class DatabaseTest extends TestCase
{
    public function testGetEntityManager()
    {
        $entityManager = Database::getEntityManager();
        $this->assertInstanceOf(\Doctrine\ORM\EntityManager::class, $entityManager);
    }
}