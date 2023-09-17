<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\AnswerDTO;
use App\Entity\Question;
use App\Entity\Result;
use App\Repository\ResultRepository;
use App\ResultHandler\ResultHandler;

class ResultService
{
    public function __construct(
        private readonly ResultHandler $resultHandler,
        private readonly ResultRepository $resultRepo,
    ) {
    }

    /**
     * @param Question[] $questions
     * @param array $data
     * @return Result[]
     */
    public function handleResult(array $questions, array $data): array
    {
        $results = $this->resultHandler->handle($questions, $data);
        foreach ($results as $result) {
            $this->resultRepo->persist($result);
        }
        $this->resultRepo->save();
        return $results;
    }

    /**
     * @param Result[] $results
     * @return AnswerDTO
     */
    public function divideResultsToSuccessfullyAndFailed(array $results): AnswerDTO
    {
        $res = new AnswerDTO();
        foreach ($results as $result) {
            if ($result->isCorrect()) {
                $res->successfullyAnswers[] = $result->getQuestion()->getQuestion();
            }
            if (!$result->isCorrect()) {
                $res->failedAnswers[] = $result->getQuestion()->getQuestion();
            }
        }

        return $res;
    }
}