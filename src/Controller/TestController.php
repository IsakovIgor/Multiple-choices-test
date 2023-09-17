<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Test;
use App\Service\ResultService;
use App\Service\TestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    public function __construct(
        private readonly TestService $testService,
        private readonly ResultService $resultService,
    ) {
    }

    #[Route('/test', name: 'test', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $questions = $this->testService->getQuestions();
        $form = $this->createForm(Test::class, null, ['questions' => $questions]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $results = $this->resultService->divideResultsToSuccessfullyAndFailed(
                $this->resultService->handleResult($questions, $form->getData())
            );

            $form = $this->createForm(Test::class, null, ['questions' => $questions]);
        }

        return $this->render('index.html.twig', [
            'form'                => $form->createView(),
            'successfulQuestions' => $results->successfullyAnswers ?? [],
            'failedQuestions'     => $results->failedAnswers ?? [],
        ]);
    }
}