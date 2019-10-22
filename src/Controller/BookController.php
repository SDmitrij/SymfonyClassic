<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\LiteraryType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    /**
     * @Route("/get_edit_modal", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function editBookModal(Request $request): JsonResponse
    {
        $id = $request->get('id');
        if ($id != '') {
            $book = $this->manager->getRepository(Book::class)->find($id);
            if ($book instanceof Book) {
                $editBookModal = $this->render('book/modal/edit.html.twig',[
                    'book'           => $book,
                    'literary_types' => $this->manager->getRepository(LiteraryType::class)
                        ->getTypesToBookEdit($book->getLiteraryType())
                ])->getContent();
                return $this->json($editBookModal, 200);
            }
        }
        return $this->json('Something wrong.', 400);
    }

    /**
     * @Route("/edit", methods={"POST"})
     * @param Request $request
     */
    public function edit(Request $request)
    {
        $r = $request->request;
        $id = $r->get('id');
        if ($id != '') {
            $book = $this->manager->getRepository(Book::class)->find($id);
            if ($book instanceof Book) {
                $title   = $r->get('title');
                $content = $r->get('content');
                $type    = $r->get('type');
            }
        }
    }
}
