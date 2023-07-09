<?php

namespace App\Controller;

use App\Entity\ResultFile;
use App\Enum\AvailableFilesExtension;
use App\Repository\ResultFileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class ListResultFileController extends AbstractController
{
    /**
     * @Route("/list/result/file", name="app_list_result_file")
     */
    public function index(ResultFileRepository $fileRepository, Filesystem $filesystem, Request $request): Response
    {
        $files = $fileRepository->findAll();

        $form = $this->createFormBuilder()
            ->add(
                'selectedFiles',
                ChoiceType::class, [
                'label' => 'Select Files',
                'choice_label' => function ($file) {
                    /** @var ResultFile $file */
                    return 'File ID: ' . $file->getFileName();
                },
                'choices' => $files,
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'form-control',
                    'size' => 5,
                ],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedFiles = $form->getData()['selectedFiles'];
            $concatenatedContent = '';
            $ext = AvailableFilesExtension::TXT;
            foreach ($selectedFiles as $selectedFile) {
                /** @var ResultFile $selectedFile */
                $concatenatedContent .= file_get_contents($selectedFile->getResultFile());
                $ext = $selectedFile->getExtension();
            }
            $outputFilename = uniqid() . '.' . $ext;
            $outputFilePath = '/app/source/db/tmp/' . $outputFilename;

            $filesystem->dumpFile('/app/source/db/tmp/' . $outputFilename, $concatenatedContent);

            $response = new BinaryFileResponse($outputFilePath);
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $outputFilename
            );
            $response->headers->set('Content-Type', $this->getMimeTypeFromExtension($ext));

            return $response;
        }

        return $this->render('list_result_file/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/list/result/file/list", name="app_list_result_file_list")
     */
    public function list(ResultFileRepository $fileRepository): Response
    {
        $files = $fileRepository->findAll();

        return $this->render('list_result_file/list.html.twig', [
            'files' => $files,
        ]);
    }

    /**
     * @Route("/list/result/download/{id}", name="download_file")
     */
    public function downloadFile($id, ResultFileRepository $fileRepository)
    {
        $resultFile = $fileRepository->findOneBy(['id' => $id]);
        $file = $resultFile->getResultFile();

        // Set the response headers for the file download
        $response = new Response(file_get_contents($file));
        $response->headers->set('Content-Type', $this->getMimeTypeFromExtension($resultFile->getExtension()));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $resultFile->getFilename() . '"');

        return $response;
    }

    private function getMimeTypeFromExtension(string $extension): string
    {
        $mimeTypes = [
            'txt' => 'text/plain',
            'csv' => 'text/csv',
            'xml' => 'application/xml',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}
