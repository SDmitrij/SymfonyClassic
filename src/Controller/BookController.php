<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Library;
use App\Entity\LiteraryType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
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
    public function index(Request $request, PaginatorInterface $paginator) : Response
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
        if ($id) {
            return $this->json('Invalid id.', 400);
        }
        $book = $this->manager->getRepository(Book::class)->find($id);
        if (!$book instanceof Book) {
            return $this->json('Not found.', 404);
        }
        $editBookModal = $this->render('book/modal/edit.html.twig',[
            'book'           => $book,
            'literary_types' => $this->manager->getRepository(LiteraryType::class)
                ->getTypesToBookEdit($book->getLiteraryType()),
        ])->getContent();
        return $this->json($editBookModal, 200);
    }

    /**
     * @Route("/edit", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     */
    public function edit(Request $request)
    {
        $r = $request->request;

        $id = $r->get('id');
        if ($id) {
            return $this->json('Invalid id.', 400);
        }
        $book = $this->manager->getRepository(Book::class)->find($id);
        if (!$book instanceof Book) {
            return $this->json('Not found.', 404);
        }
        $bookToEdit = clone $book;

        $title   = $r->get('title');
        $content = $r->get('content');
        $type    = $r->get('type');

        if ($title != ''
            && $bookToEdit->getTitle() != $title) {
            $bookToEdit->setTitle($title);
        }
        if ($content
            && $bookToEdit->getContent() != $content) {
            $bookToEdit->setContent($content);
        }
        if ($type
            && $bookToEdit->getLiteraryType() != $type) {
            $bookToEdit->setLiteraryType($type);
        }
        if ($book !== $bookToEdit) {
            $this->manager->merge($bookToEdit);
            $this->manager->flush();
            return $this->json('Book updated.', 200);
        } else {
            return $this->json('Nothing to update.', 200);
        }
    }

    /**
     * @Route("/delete")
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     */
    public function delete(Request $request) : JsonResponse
    {
        $id = $request->get('id');
        if ($id)
        {
            return $this->json('Invalid id.', 400);
        }
        $book = $this->manager->getReference(Book::class, $id);
        if (!$book instanceof Book) {
            return $this->json('Not found.', 404);
        }
        $libras = $this->manager->getRepository(Library::class)->findBy([
            'id' => $this->manager->getRepository(Library::class)->getLibraIdsToBookRemove($book->getId())
        ]);
        /** @var Library $libra */
        foreach ($libras as $libra) {
            if ($libra instanceof Library) {
                $libra->removeBook($book);
                $this->manager->merge($libra);
            }
        }
        $this->manager->remove($book);
        $this->manager->flush();
        return $this->json(['status' => true,
            'message' => 'Book has been deleted successfully.'], 200);
    }
}
