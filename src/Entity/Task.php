<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\TaskRepository;
use App\State\Processor\TaskPersistProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

enum Status: string
{
    case Todo = "todo";
    case Done = "done";
}

enum Priority: int
{
    case Highest = 1;
    case High = 2;
    case Normal = 3;
    case Low = 4;
    case Lowest = 5;
}

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Delete(
            security: 'is_granted("ROLE_USER") and object.user === user'
        ),
        new Post(
            processor: TaskPersistProcessor::class,
            security: 'is_granted("ROLE_USER")'
        ),
        new Patch(
            security: 'is_granted("ROLE_USER") and object.user === user'
        )
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => ['Task:read'],

    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => ['Task:write'],
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['status' => 'exact', 'priority' => 'exact', 'title' => 'partial', 'description' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['priority', 'createdAt', 'completedAt'])]
class Task
{
    #[Groups(groups: ['Task:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(groups: ['Task:read', 'Task:write'])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Groups(groups: ['Task:read', 'Task:write'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Groups(groups: ['Task:read', 'Task:write'])]
    #[ORM\Column(length: 255, enumType: Status::class)]
    private Status $status;

    #[Groups(groups: ['Task:read', 'Task:write'])]
    #[ORM\Column(enumType: Priority::class)]
    private Priority $priority;

    #[Groups(groups: ['Task:read', 'Task:write'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(groups: ['Task:read', 'Task:write'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $completedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $parentId = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    public ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function setPriority(Priority $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeImmutable $completedAt): static
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): static
    {
        $this->parentId = $parentId;

        return $this;
    }
}
