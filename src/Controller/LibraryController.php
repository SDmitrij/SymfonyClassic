<?php

namespace App\Controller;

use App\Entity\Library;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
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
     * @return Response
     */
    public function index(int $id): Response
    {
        $lib = $this->manager->getRepository(Library::class)->find(['id' => $id]);

        return $this->render('library/index.html.twig', [
            'library' => $lib
        ]);
    }
}
