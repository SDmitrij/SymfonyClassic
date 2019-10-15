<?php

namespace App\Controller;

use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="index")
     * @param LibraryRepository $libraryRepository
     * @return Response
     */
    public function index(LibraryRepository $libraryRepository)
    {
        return $this->render('base.html.twig', [
            'libs_drop_down' => $this->render('index/libraries_dropdown.html.twig', [
                'libraries'  => $libraryRepository->getLibListToDropDown()
            ])->getContent()
        ]);
    }
}
