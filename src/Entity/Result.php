<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ResultRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Result
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private string $id;

    #[ORM\Column(type: 'date')]
    private DateTimeInterface $created;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    #[ORM\JoinColumn(nullable: true)]
    private Question $question;

    #[ORM\Column(type: 'boolean')]
    private bool $isCorrect;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Result
    {
        $this->id = $id;
        return $this;
    }

    public function getCreated(): DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface $created): Result
    {
        $this->created = $created;
        return $this;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): Result
    {
        $this->question = $question;
        return $this;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setCorrect(bool $isCorrect): Result
    {
        $this->isCorrect = $isCorrect;
        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->created = new DateTimeImmutable();
    }
}