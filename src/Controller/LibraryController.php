<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Library;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
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
     * @Route("/{id}", methods={"GET"}, defaults={"id":"1"}, name="libra_index", requirements={"id"="\d+"})
     *
     * @param int $id
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(int $id, Request $request, PaginatorInterface $paginator): Response
    {
        /** @var Library $lib */
        $lib = $this->manager->getRepository(Library::class)->getLibBooksToPagination($id)[0];

        $pagBooks = $paginator->paginate($lib->getBooks(),
            $request->query->getInt('page', 1), self::LIMIT_PER_PAGE);

        return $this->render('library/index.html.twig', [
            'lib'       => $lib,
            'pag_books' => $pagBooks
        ]);
    }

    /**
     * @Route("/show_edit_modal", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ORMException
     */
    public function showEditModal(Request $request): Response
    {
        $id = $request->get('id');

        if ($id != '')
        {
            $lib = $this->manager->getReference(Library::class, $id);
            if ($lib instanceof Library)
            {
                $modal = $this->render('library/modal/edit.html.twig', [
                    'address' => $lib->getAddress(),
                    'id'      => $lib->getId()
                ])->getContent();

                return $this->json($modal, 200);
            }
        }

        return $this->json('Something wrong.', 400);
    }

    /**
     * @Route("/edit", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function edit(Request $request): JsonResponse
    {
        $r    = $request->request;
        $id   = $r->get('id');
        $addr = $r->get('address');

        if ($id && $addr != '')
        {
            $lib = $this->manager->getRepository(Library::class)->findOneBy(['id' => $id]);
            if ($lib instanceof Library && $lib->getAddress() != $addr)
            {
                $lib->setAddress($addr);
                $this->manager->flush();
                return $this->json('Library updated.', 200);
            }
        }
        return $this->json('Something wrong.', 400);
    }

    /**
     * @Route("/delete", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->request->get('id');
        if ($id != '')
        {
            $lib = $this->manager->getReference(Library::class, $id);
            if ($lib instanceof Library)
            {
                $this->manager->remove($lib);
                $this->manager->flush();
                return $this->json(['status' => true,
                    'message' => 'Library has been deleted successfully.'], 200);
            }
        }
        return $this->json('Something wrong.', 400);
    }

    /**
     * @Route("/book_list_to_add", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getBookListToAdd(Request $request): JsonResponse
    {
        $id = $request->get('id');
        if ($id != '') {
            $booksToAddModal = $this->render('library/modal/add_new_books.html.twig', [
                'books_to_add' => $this->manager->getRepository(Library::class)->getBooksToAdd($id)
            ])->getContent();

            return $this->json($booksToAddModal, 200);
        }
        return $this->json('Something wrong', 400);
    }

    /**
     * @Route("/add_new_books", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function addNewBooks(Request $request): JsonResponse
    {
        $r       = $request->request;
        $libId   = $r->get('id');
        $bookIds = $r->get('bookIds');

        if (!empty($bookIds) && $libId != '') {
            $booksToAdd = $this->manager->getRepository(Book::class)->findBy(['id' => $bookIds]);
            /** @var Library $lib */
            $lib = $this->manager->getRepository(Library::class)->findOneBy(['id' => $libId]);
            if ($lib instanceof Library) {
                foreach ($booksToAdd as $book) {
                    $lib->addBook($book);
                }
                $this->manager->flush();
                return $this->json(['status' => true, 'message' => 'Books added.'],
                    200);
            }
        }
        return $this->json(['status' => false, 'message' => 'Something wrong.'], 400);
    }

    /**
     * @Route("/remove_book", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function removeBook(Request $request): JsonResponse
    {
        $libId  = $request->get('libId');
        $bookId = $request->get('bookId');

        if ($libId != '' && $bookId != '') {

            $lib  = $this->manager->getRepository(Library::class)->find($libId);
            $book = $this->manager->getRepository(Book::class)->find($bookId);

            if ($lib instanceof Library && $book instanceof Book) {
                $lib->removeBook($book);
                $this->manager->merge($lib);
                $this->manager->flush();

                return $this->json(['message' => 'Book successfully removed.'], 200);
            }
        }
        return $this->json('Something wrong.', 400);
    }
}
