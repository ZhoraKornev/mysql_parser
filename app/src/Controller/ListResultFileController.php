<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListResultFileController extends AbstractController
{
    /**
     * @Route("/list/result/file", name="app_list_result_file")
     */
    public function index(): Response
    {
        return $this->render('list_result_file/index.html.twig', [
            'controller_name' => 'ListResultFileController',
        ]);
    }
}
