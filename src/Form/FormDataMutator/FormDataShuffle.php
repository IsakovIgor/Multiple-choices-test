<?php

declare(strict_types=1);

namespace App\Form\FormDataMutator;

class FormDataShuffle implements FormDataMutator
{
    public function mutate(array &$data): void
    {
        shuffle($data);
    }
}