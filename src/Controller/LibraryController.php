<?php

namespace App\Controller;

use App\Repository\LibraryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/{libId}", methods={"GET"}, name="library_index", requirements={"libId"="\d+"})
     *
     * @param int $libId
     * @return Response
     */
    public function index(int $libId): Response
    {
        $lib = $this->manager->getRepository(LibraryRepository::class)->findOneBy(['id' => $libId]);

        return $this->render('library/index.html.twig', [
            'library' => $lib
        ]);
    }
}
