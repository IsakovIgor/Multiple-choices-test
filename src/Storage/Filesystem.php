<?php

declare(strict_types=1);

namespace App\Storage;

use Generator;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Filesystem implements Storage
{
    public function get(string $path): Generator
    {
        $fp = fopen($path, 'r');
        if (!$fp) {
            throw new FileException(sprintf('File with path %s wasn\'t found', $path));
        }

        while (($line = fgets($fp)) !== false) {
            yield $line;
        }

        fclose($fp);
    }

    public function getFull(string $path): string
    {
        $file = file_get_contents($path);
        if (!$file) {
            throw new FileException(sprintf('File with path %s wasn\'t found', $path));
        }

        return $file;
    }

    public function set(string $path, string $data): bool
    {
        return (bool) file_put_contents($path, $data . PHP_EOL, FILE_APPEND);
    }
}