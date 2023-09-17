<?php

declare(strict_types=1);

namespace App\Tests\ResultHandler;

use App\Entity\Question;
use App\Entity\QuestionChoice;
use App\Entity\Result;
use App\ResultHandler\Exception\ResultHandlerException;
use App\ResultHandler\MultipleChoiceResultHandler;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResultHandlerTest extends TestCase
{
    #[DataProvider('getData')]
    public function testResultHandler(array $questions, array $data, array $expected): void
    {
        $handler = new MultipleChoiceResultHandler();
        $result = $handler->handle($questions, $data);
        $this->assertEquals($expected, $result);
    }

    #[DataProvider('getDataForNoQuestionException')]
    public function testResultHandlerThereIsNoQuestionException(array $questions, array $data): void
    {
        $handler = new MultipleChoiceResultHandler();
        $this->expectException(ResultHandlerException::class);
        $this->expectExceptionMessage('There is no any answers for question with id 1');
        $handler->handle($questions, $data);
    }

    #[DataProvider('getDataForNoAnswersForQuestionException')]
    public function testResultHandlerThereIsNoAnswersForQuestionException(array $questions, array $data): void
    {
        $handler = new MultipleChoiceResultHandler();
        $this->expectException(ResultHandlerException::class);
        $this->expectExceptionMessage('There is no any answers with ids ["r"] in db');
        $handler->handle($questions, $data);
    }

    public static function getData(): array
    {
        $question1 = (new Question())->setId('1')->setChoices(new ArrayCollection([
            (new QuestionChoice())->setId('q')->setIsCorrect(true),
            (new QuestionChoice())->setId('w')->setIsCorrect(true),
            (new QuestionChoice())->setId('e')->setIsCorrect(false),
        ]));
        $question2 = (new Question())->setId('2')->setChoices(new ArrayCollection([
            (new QuestionChoice())->setId('q')->setIsCorrect(true),
            (new QuestionChoice())->setId('w')->setIsCorrect(true),
            (new QuestionChoice())->setId('e')->setIsCorrect(false),
        ]));
        $question3 = (new Question())->setId('3')->setChoices(new ArrayCollection([
            (new QuestionChoice())->setId('q')->setIsCorrect(true),
            (new QuestionChoice())->setId('w')->setIsCorrect(true),
            (new QuestionChoice())->setId('e')->setIsCorrect(false),
        ]));
        $question4 = (new Question())->setId('4')->setChoices(new ArrayCollection([
            (new QuestionChoice())->setId('q')->setIsCorrect(true),
            (new QuestionChoice())->setId('w')->setIsCorrect(true),
            (new QuestionChoice())->setId('e')->setIsCorrect(false),
        ]));
        return [
            [
                'questions' => [
                    $question1,
                    $question2,
                    $question3,
                    $question4,
                ],
                'data'      => [
                    // one of correct choices was checked
                    '1' => [
                        'q',
                    ],
                    // all correct choices were checked
                    '2' => [
                        'q',
                        'w',
                    ],
                    // choice isn't correct totally
                    '3' => [
                        'e',
                    ],
                    // one choice is correct and one isn't
                    '4' => [
                        'q',
                        'e',
                    ],
                ],
                'expected'  => [
                    (new Result())->setQuestion($question1)->setCorrect(true),
                    (new Result())->setQuestion($question2)->setCorrect(true),
                    (new Result())->setQuestion($question3)->setCorrect(false),
                    (new Result())->setQuestion($question4)->setCorrect(false),
                ],
            ]
        ];
    }

    public static function getDataForNoQuestionException(): array
    {
        return [
            [
                'questions' => [
                    (new Question())->setId('1')->setChoices(new ArrayCollection([
                        (new QuestionChoice())->setId('q')->setIsCorrect(true),
                        (new QuestionChoice())->setId('w')->setIsCorrect(true),
                        (new QuestionChoice())->setId('e')->setIsCorrect(false),
                    ])),
                ],
                'data'      => [
                    // It isn't correct id for question
                    '2' => [
                        'q',
                    ],
                ],
            ]
        ];
    }

    public static function getDataForNoAnswersForQuestionException(): array
    {
        return [
            [
                'questions' => [
                    (new Question())->setId('1')->setChoices(new ArrayCollection([
                        (new QuestionChoice())->setId('q')->setIsCorrect(true),
                        (new QuestionChoice())->setId('w')->setIsCorrect(true),
                        (new QuestionChoice())->setId('e')->setIsCorrect(false),
                    ])),
                ],
                'data'      => [
                    // It isn't correct id for question
                    '1' => [
                        'r',
                    ],
                ],
            ]
        ];
    }
}