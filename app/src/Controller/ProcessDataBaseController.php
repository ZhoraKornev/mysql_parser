<?php

namespace App\Controller;

use App\Factory\FileGenerator;
use App\Form\ListDataBaseFormDTO;
use App\Form\ListDatabaseFormType;
use App\Service\DataBaseDriver;
use App\Service\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProcessDataBaseController extends AbstractController
{
    /**
     * @Route("/process/data", name="app_process_data_base",methods={"POST"})
     */
    public function processDataBaseToFile(Request $request, DataBaseDriver $tempDataBase, FileGenerator $fileGenerator): Response
    {
        $form = $this->createForm(ListDatabaseFormType::class);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            /** @var ListDataBaseFormDTO $dto */
            $dto = $form->getData();
            $tempDataBase->prepareTempDataBase();
            $tempDataBase->fullFillTempDBWithData(current($dto->databaseFilesToProcess));
            $dataArr = $tempDataBase->getDataFromTempDB($dto->tableToSelect, $dto->contentColumn, $dto->titleColumn);
            $tempDataBase->rollBackToStartInstance();
            $parser = new Parser();
            $parsedContent = $parser->parse($dataArr, $dto->contentColumn, $dto->titleColumn);
            $fileGenerator->generateFile($parsedContent, $dto->selectedExtension);

            dd($parsedContent);
        }

        return $this->render(
            'list.html.twig',
            [ 'mainForm' => $form->createView()]
        );
    }
}
