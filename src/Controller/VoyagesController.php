<?php



namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VisiteRepository;


/**
 * Description of VoyagesController
 *
 * @author FENOUILLET Paul
 */
class VoyagesController extends AbstractController{
    
    /**
     * 
     * @var VisiteRepository
     */
    private $repository;
    
    /**
     * @Route("/voyages", name="voyages")
     * @return Response
     */
    public function index(): Response{
        $visite = $this->repository->findAll();
        return $this->render("pages/voyages.html.twig", [
            'visites' => $visite
        ]);
    }
    
    public function __construct(VisiteRepository $repository) {
        $this->repository=$repository;
    }
}
