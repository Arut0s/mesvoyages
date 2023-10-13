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
        $visites = $this->repository->findAllOrderBy('datecreation','DESC');
        return $this->render("pages/voyages.html.twig", [
            'visites' => $visites
        ]);
    }
    /**
     * @Route("/voyages/tri/{champ}/{ordre}", name="voyages.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre):Response{
        $visites = $this->repository->findAllOrderBy($champ, $ordre);
        return $this->render("pages/voyages.html.twig", [
            'visites' => $visites
        ]);
    }
    
    
    public function __construct(VisiteRepository $repository) {
        $this->repository=$repository;
    }
}
