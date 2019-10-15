<?php

namespace App\Controller;

use App\Entity\Library;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
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
    /**
     * @var EntityManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/{id}", methods={"GET"}, defaults={"id":"1"}, name="library_index", requirements={"id"="\d+"})
     *
     * @param int $id
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(int $id, Request $request, PaginatorInterface $paginator): Response
    {
        $lib = $this->manager->getRepository(Library::class)
            ->getLibBooksToPagination($id)[0];
        $pagBooks = $paginator->paginate($lib['books'], $request->query->getInt('page', 1), 3);

        return $this->render('library/index.html.twig', [
            'lib_addr'  => $lib['address'],
            'lib_id'    => $lib['id'],
            'pag_books' => $pagBooks
        ]);
    }
}
