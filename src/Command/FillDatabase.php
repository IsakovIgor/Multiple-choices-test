<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\Exception\JsonParseException;
use App\Entity\Question;
use App\Entity\QuestionChoice;
use App\Storage\Storage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FillDatabase extends Command
{
    protected string $name = 'app:db:fill';
    protected string $description = 'Truncate and fill the database';

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Storage $storage,
        private readonly string $questionsPath,
    ) {
        parent::__construct($this->name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->em->beginTransaction();
            $this->fillDb();
            $this->em->commit();
        } catch (JsonParseException $e) {
            $this->em->rollback();
            $io->error($e->getMessage());
            return Command::FAILURE;
        } catch (ORMException $e) {
            $this->em->rollback();
            $io->error($e->getMessage() . "\n" . $e->getTraceAsString());
            return Command::FAILURE;
        }
        $io->success('All actions has done');

        return Command::SUCCESS;
    }

    /**
     * @throws JsonParseException
     * @throws ORMException
     */
    private function fillDb(): void
    {
        $json = json_decode($this->storage->getFull($this->questionsPath), true);
        foreach ($json as $i => $data) {
            if (!isset($data['question']) || !isset($data['choices'])) {
                // also we can just continue
                throw new JsonParseException(
                    sprintf('incorrect json at %d position in file %s', $i, $this->questionsPath)
                );
            }
            $question = (new Question())->setQuestion($data['question']);
            foreach ($data['choices'] as $ch => $correct) {
                $choice = (new QuestionChoice())
                    ->setQuestion($question)
                    ->setChoiceText((string)$ch)
                    ->setIsCorrect($correct);
                $this->em->persist($choice);
            }
            $this->em->persist($question);
        }

        $this->em->flush();
    }
}