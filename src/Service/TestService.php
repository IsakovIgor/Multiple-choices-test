<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Question;
use App\Repository\QuestionRepository;

class TestService
{
    public function __construct(
        private readonly QuestionRepository $questionRepo
    ) {
    }

    /**
     * @return Question[]
     */
    public function getQuestions(): array
    {
        return $this->questionRepo->findAll();
    }
}