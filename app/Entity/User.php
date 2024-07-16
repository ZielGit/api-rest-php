<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'users_email_unique', columns: ['email'])
])]
#[ORM\HasLifecycleCallbacks]
class User
{
    #[ORM\Id]
    #[ORM\Column, ORM\GeneratedValue]
    private int $id;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(unique: true)]
    private string $email;

    #[ORM\Column]
    private string $password;

    #[ORM\Column(name: 'created_at', nullable: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(name: 'updated_at', nullable: true)]
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
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

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('America/Lima'));
    }
}