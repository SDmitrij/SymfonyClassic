<?php

namespace App\Controller;

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
     * @Route("/show_edit_form", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ORMException
     */
    public function showEditForm(Request $request): Response
    {
        $id = $request->get('id');

        if ($id != "")
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
    public function edit(Request $request)
    {
        $r = $request->request;

        $id   = $r->get('id');
        $addr = $r->get('address');

        if ($id && $addr != "")
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
}
