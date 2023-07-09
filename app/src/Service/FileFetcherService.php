<?php

namespace App\Service;

use Symfony\Component\Finder\Finder;

class FileFetcherService
{
    public function __construct(private string $directory)
    {
    }

    public function getFilesInDirectory(): array
    {
        $finder = new Finder();
        $finder->files()->in($this->directory);

        $files = [];
        foreach ($finder as $file) {
            $files[$file->getFilename()] = $file->getPathname();
        }

        return $files;
    }
}
