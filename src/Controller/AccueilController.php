<?php

namespace App\Controller;

use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AccueilController
 *
 * @author FENOUILLET Paul
 */
class AccueilController extends AbstractController{
    
    /**
     * 
     * @var type
     */
    private $repository;
    /**
     * @Route("/", name="accueil")
     * @return Response
     */
    public function index(): Response{
        $visites = $this->repository->findAllLasted(2);
                return $this->render("pages/accueil.html.twig", [
                    'visites' => $visites
                ]);
    }
    public function __construct(VisiteRepository $repository) {
        $this->repository = $repository;
    }
}
