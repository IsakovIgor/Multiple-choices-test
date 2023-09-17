<?php

declare(strict_types=1);

namespace App\Form\FormDataMutator;

interface FormDataMutator
{
    public function mutate(array &$data): void;
}