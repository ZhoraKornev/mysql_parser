<?php

namespace App\Factory;

use App\Entity\ResultFile;
use App\Form\ListDataBaseFormDTO;
use App\Repository\ResultFileRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class ResultFileFactory
{
    public function __construct(private ResultFileRepository $resultFileRepository)
    {
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function createResultFileEntityAndSave(ListDataBaseFormDTO $dto, string $pahToFile): void
    {
        $resultFile = new ResultFile(
            $pahToFile,
            $dto->tableToSelect . $dto->titleColumn . $dto->selectedExtension,
            $dto->selectedExtension
        );

        $this->resultFileRepository->add($resultFile);
    }
}
