<?php



namespace App\Controller\admin;

use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminVoyagesController
 *
 * @author FENOUILLET Paul
 */
class AdminVoyagesController extends AbstractController{

     /**
     * 
     * @var VisiteRepository
     */
    private $repository;
    
    /**
     * @Route("/admin", name="admin.voyages")
     * @return Response
     */
    public function index(): Response{
        $visites = $this->repository->findAllOrderBy('datecreation','DESC');
        return $this->render("admin/admin.voyages.html.twig", [
            'visites' => $visites
        ]);
    }
    
 /**
 * @Route("/admin/suppr/{id}", name="admin.voyage.suppr")
 * @param int $id
 * @return Response
 */
public function suppr(int $id) : Response {
    $visite = $this->repository->find($id);
    $this->repository->remove($visite, true);
    return $this->redirectToRoute('admin.voyages');
}
    
     public function __construct(VisiteRepository $repository) {
        $this->repository=$repository;
    }
}