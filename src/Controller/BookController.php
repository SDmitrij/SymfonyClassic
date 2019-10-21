<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("book")
 */
class BookController extends AbstractController
{
    public const LIMIT_PER_PAGE = 3;

    /**
     * @var EntityManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/", methods={"GET"}, name="books_index")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $books = $this->manager->getRepository(Book::class)->getAllBooks();

        $pagBooks = $paginator->paginate($books,
            $request->query->getInt('page', 1), self::LIMIT_PER_PAGE);

        return $this->render('book/index.html.twig', [
            'pag_books' => $pagBooks
        ]);
    }
}
