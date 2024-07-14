<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'courses', uniqueConstraints: [
    new \Doctrine\ORM\Mapping\UniqueConstraint(name: 'courses_title_unique', columns: ['title']),
    new \Doctrine\ORM\Mapping\UniqueConstraint(name: 'courses_description_unique', columns: ['description'])
])]
class Course
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column]
    private string $title;

    #[Column]
    private string $description;

    #[Column]
    private string $instructor;

    #[Column(nullable: true)]
    private ?string $image = null;

    #[Column]
    private float $price;

    #[Column(name: 'creator_id')]
    private int $creatorId;

    #[Column(name: 'created_at', nullable: true)]
    private ?\DateTime $createdAt = null;

    #[Column(name: 'updated_at', nullable: true)]
    private ?\DateTime $updatedAt = null;

    // Getters y setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getInstructor(): string
    {
        return $this->instructor;
    }

    public function setInstructor(string $instructor): void
    {
        $this->instructor = $instructor;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getCreatorId(): int
    {
        return $this->creatorId;
    }

    public function setCreatorId(int $creatorId): void
    {
        $this->creatorId = $creatorId;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}