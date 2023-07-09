<?php

namespace App\Factory;

use App\Enum\AvailableFilesExtension;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;


class FileGenerator
{
    private string $filePathToSave;

    public function __construct(private SerializerInterface $serializer, private Filesystem $filesystem)
    {
        $this->filePathToSave = '/app/source/db/tmp/';
        if (!is_dir($this->filePathToSave)) {
            mkdir($this->filePathToSave, 0755, true);
        }
    }

    public function generateFile(array $data, string $format): string
    {
        if (in_array($format, [
            AvailableFilesExtension::CSV,
            AvailableFilesExtension::XML,
        ])) {
            $xml = $this->serializer->serialize($data, $format);
            $pathToSave = $this->filePathToSave . time() . '.' . $format;
            $this->filesystem->dumpFile($pathToSave, $xml);

            return $pathToSave;
        }
        if ($format === AvailableFilesExtension::TXT) {
            $text = implode(', ', array_map(function ($entry) {
                if (is_array($entry)) {
                    return implode("\n", $entry);
                }
                return $entry;
            }, $data));
            $pathToSave = $this->filePathToSave . time() . '.' . $format;
            $this->filesystem->dumpFile($pathToSave, $text);

            return $pathToSave;
        }

        throw new ValidatorException('Format not valid');
    }
}