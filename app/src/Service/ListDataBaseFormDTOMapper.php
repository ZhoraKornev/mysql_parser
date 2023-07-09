<?php

namespace App\Service;

use App\Form\ListDataBaseFormDTO;
use Symfony\Component\Form\DataMapperInterface;

class ListDataBaseFormDTOMapper implements DataMapperInterface
{
    public function mapDataToForms($viewData, $forms): void
    {
        if (null !== $viewData) {
            $forms = iterator_to_array($forms);
            $forms['tableToSelect']->setData($viewData->tableToSelect);
            $forms['selectedExtension']->setData($viewData->selectedExtension);
            $forms['databaseFilesToProcess']->setData($viewData->databaseFilesToProcess);
            $forms['contentColumn']->setData($viewData->contentColumn);
            $forms['titleColumn']->setData($viewData->titleColumn);


        }
    }

    public function mapFormsToData($forms, &$viewData): void
    {
        $forms = iterator_to_array($forms);

        $viewData = new ListDataBaseFormDTO();
        $viewData->tableToSelect = $forms['tableToSelect']->getData();
        $viewData->selectedExtension = $forms['selectedExtension']->getData();
        $viewData->databaseFilesToProcess = $forms['databaseFileToProcess']->getData();
        $viewData->contentColumn = $forms['contentColumn']->getData();
        $viewData->titleColumn = $forms['titleColumn']->getData();
    }
}
