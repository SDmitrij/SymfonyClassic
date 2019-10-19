<?php

namespace App\Service;

use App\Entity\Library;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class LibraDropDownWidget
{
    protected $container;
    protected $twig;

    public function __construct(ContainerInterface $container, Environment $twig)
    {
        $this->container = $container;
        $this->twig = $twig;
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getLibraDropDownWidget()
    {
        $repo =
            $this->container->get('doctrine.orm.entity_manager')
                ->getRepository(Library::class);

        return
            $this->twig->render('index/libraries_dropdown.html.twig', [
                'libraries'  => $repo->getLibListToDropDown()
            ]);
    }
}
