<?php

declare(strict_types=1);

namespace App\ResultHandler;

use App\Entity\Question;
use App\Entity\Result;
use App\ResultHandler\Exception\ResultHandlerException;

interface ResultHandler
{
    /**
     * @param Question[] $questions
     * @param array $data
     * @return Result[]
     * @throws ResultHandlerException
     */
    public function handle(array $questions, array $data): array;
}