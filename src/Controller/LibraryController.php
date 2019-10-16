<?php

namespace App\Controller;

use App\Entity\Library;
use App\Form\LibraryType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function json_encode;

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
     * @Route("/edit_form", methods={"GET"}, name="libra_edit_form")
     *
     * @param Request $request
     * @return Response
     */
    public function showEditForm(Request $request): Response
    {
        $id = $request->get('id');

        if ($id != "")
        {
            $lib = $this->manager->getRepository(Library::class)->findOneBy(['id' => $id]);
            if ($lib instanceof Library)
            {
                $form = $this->createForm(LibraryType::class, $lib);

                $modal = $this->render('library/modal_forms/edit.html.twig', [
                    'form' => $form->createView()
                ])->getContent();

                return new JsonResponse(json_encode($modal), 200, [], true);
            } else {
                return new JsonResponse('Library entity is null.', 400);
            }
        } else {
            return new JsonResponse('Library id is empty.', 400);
        }
    }
}
