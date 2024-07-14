<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'customers', uniqueConstraints: [
    new \Doctrine\ORM\Mapping\UniqueConstraint(name: 'customers_email_unique', columns: ['email'])
])]
#[HasLifecycleCallbacks]
class Customer
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column]
    private string $name;

    #[Column(name: 'last_name')]
    private string $lastName;

    #[Column]
    private string $email;

    #[Column(name: 'customer_id')]
    private string $customerId;

    #[Column(name: 'secret_key')]
    private string $secretKey;

    #[Column(name: 'created_at', nullable: true)]
    private ?\DateTime $createdAt = null;

    #[Column(name: 'updated_at', nullable: true)]
    private ?\DateTime $updatedAt = null;

    // Getters y setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function setCustomerId(string $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function setSecretKey(string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    #[PrePersist]
    public function setCreatedAt(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTime('now', new \DateTimeZone('America/Lima'));
        }
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    #[PrePersist]
    #[PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('America/Lima'));
    }
}