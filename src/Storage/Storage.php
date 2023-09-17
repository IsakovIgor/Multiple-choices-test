<?php

declare(strict_types=1);

namespace App\Storage;

use Generator;

interface Storage
{
    public function get(string $path): Generator;
    public function getFull(string $path): string;
    public function set(string $path, string $data): bool;
}