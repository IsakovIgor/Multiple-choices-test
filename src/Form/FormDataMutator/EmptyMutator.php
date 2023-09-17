<?php

declare(strict_types=1);

namespace App\Form\FormDataMutator;

class EmptyMutator implements FormDataMutator
{
    public function mutate(array &$data): void
    {
        return;
    }
}