<?php

declare(strict_types=1);

namespace App\ResultHandler;

use App\Entity\Question;
use App\Entity\QuestionChoice;
use App\Entity\Result;
use App\ResultHandler\Exception\ResultHandlerException;
use DateTimeImmutable;

class MultipleChoiceResultHandler implements ResultHandler
{
    /**
     * @param Question[] $questions
     * @param array $data
     * @return Result[]
     * @throws ResultHandlerException
     */
    public function handle(array $questions, array $data): array
    {
        $res = [];
        foreach ($questions as $question) {
            $hit = false; // if is false in the end of answer`s section, it's impossible
            $result = (new Result())
                ->setQuestion($question)
                ->setCorrect(true);

            if (!isset($data[$question->getId()])) {
                throw new ResultHandlerException(
                    'There is no any answers for question with id ' . $question->getId()
                );
            }

            foreach ($data[$question->getId()] as $answer) {
                /** @var QuestionChoice[] $choices */
                $choices = array_values(
                    array_filter(
                        $question->getChoices()->toArray(),
                        fn(QuestionChoice $qc) => $qc->getId() === $answer
                    )
                );
                if (!isset($choices[0])) {
                    continue;
                }

                $hit = true;

                // if one of answers is not correct, so the question will not correct totally
                if (!$choices[0]->isCorrect()) {
                    $result->setCorrect(false);
                }
            }

            if (!$hit) {
                throw new ResultHandlerException(
                    sprintf('There is no any answers with ids %s in db', json_encode($data[$question->getId()]))
                );
            }

            $res[] = $result;
        }

        return $res;
    }
}