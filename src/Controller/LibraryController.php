<?php

namespace App\Controller;

use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LibraryController
 * @package App\Controller
 * @Route("library")
 */
class LibraryController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="library_index")
     * @param LibraryRepository $libraryRepository
     * @return Response
     */
    public function index(LibraryRepository $libraryRepository)
    {
        $lib = $libraryRepository->findOneBy(['id' => 1]);

        return $this->render('library/index.html.twig', [
            'library' => $lib
        ]);
    }
}
