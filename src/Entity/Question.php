<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private string $id = '';

    #[ORM\Column(type: 'string', length: 255)]
    private string $question = '';

    #[ORM\OneToMany(
        mappedBy: 'question',
        targetEntity: QuestionChoice::class,
        cascade: ['persist']
    )]
    private Collection $choices;

    #[ORM\OneToMany(
        mappedBy: 'question',
        targetEntity: Result::class,
        cascade: ['persist']
    )]
    private Collection $results;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Question
    {
        $this->id = $id;
        return $this;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function setQuestion(string $question): Question
    {
        $this->question = $question;
        return $this;
    }

    public function getAnswers(): Collection
    {
        return $this->choices;
    }

    public function setAnswers(Collection $answers): Question
    {
        $this->choices = $answers;
        return $this;
    }

    /**
     * @return Collection<QuestionChoice>
     */
    public function getChoices(): Collection
    {
        return $this->choices;
    }

    /**
     * @param Collection<QuestionChoice> $choices
     * @return $this
     */
    public function setChoices(Collection $choices): Question
    {
        $this->choices = $choices;
        return $this;
    }

    public function getResults(): Collection
    {
        return $this->results;
    }

    public function setResults(Collection $results): Question
    {
        $this->results = $results;
        return $this;
    }
}