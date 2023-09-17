<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\QuestionChoiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity(repositoryClass: QuestionChoiceRepository::class)]
class QuestionChoice
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    #[ORM\JoinColumn(nullable: true)]
    private Question $question;

    #[ORM\Column(type: 'string', length: 255)]
    private string $choiceText;

    #[ORM\Column(type: 'boolean')]
    private bool $isCorrect;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): QuestionChoice
    {
        $this->id = $id;
        return $this;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): QuestionChoice
    {
        $this->question = $question;
        return $this;
    }

    public function getChoiceText(): string
    {
        return $this->choiceText;
    }

    public function setChoiceText(string $choiceText): QuestionChoice
    {
        $this->choiceText = $choiceText;
        return $this;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): QuestionChoice
    {
        $this->isCorrect = $isCorrect;
        return $this;
    }
}