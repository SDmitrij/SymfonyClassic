<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="books_index")
     */
    public function index()
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    /**
     * @Route("/book_list_add", methods={"GET"})
     *
     * @param Request $request
     */
    public function getBooksListToAdd(Request $request)
    {
       $libId = $request->get('id');

    }
}
