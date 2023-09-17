<?php

declare(strict_types=1);

namespace App\DTO;

class AnswerDTO
{
    /** @var string[] */
    public array $successfullyAnswers;

    /** @var array string[] */
    public array $failedAnswers;
}