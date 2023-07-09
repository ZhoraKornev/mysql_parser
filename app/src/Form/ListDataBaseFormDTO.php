<?php

namespace App\Form;

class ListDataBaseFormDTO
{
    public string $tableToSelect;
    public string $contentColumn;
    public string $titleColumn;

    public array $databaseFilesToProcess;
    public string $selectedExtension;
}