<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Question;
use App\Form\FormDataMutator\FormDataMutator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Test extends AbstractType
{
    public function __construct(private readonly FormDataMutator $mutator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->mutator->mutate($options['questions']);
        foreach ($options['questions'] as $question) {
            /** @var $question Question */
            $choices = [];
            $qCh = $question->getChoices()->toArray();
            $this->mutator->mutate($qCh);
            foreach ($qCh as $choice) {
                $choices[$choice->getChoiceText()] = $choice->getId();
            }
            $builder
                ->add($question->getId(), ChoiceType::class, [
                    'multiple' => true,
                    'choices'  => $choices,
                    'label'    => $question->getQuestion(),
                ]);
        }

        $builder->add('check',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['questions' => []]);
    }
}
