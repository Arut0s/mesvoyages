<?php

namespace App\Controller\admin;

use App\Entity\Visite;
use App\Form\VisiteType;
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
class AdminVoyagesController extends AbstractController {

    /**
     * 
     * @var VisiteRepository
     */
    private $repository;

    /**
     * @Route("/admin", name="admin.voyages")
     * @return Response
     */
    public function index(): Response {
        $visites = $this->repository->findAllOrderBy('datecreation', 'DESC');
        return $this->render("admin/admin.voyages.html.twig", [
                    'visites' => $visites
        ]);
    }

    /**
     * @Route("/admin/suppr/{id}", name="admin.voyage.suppr")
     * @param int $id
     * @return Response
     */
    public function suppr(int $id): Response {
        $visite = $this->repository->find($id);
        $this->repository->remove($visite, true);
        return $this->redirectToRoute('admin.voyages');
    }

    /**
     * @Route("/admin/edit/{id}", name="admin.voyage.edit")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function edit(int $id, Request $request): Response {
        $visite = $this->repository->find($id);
        $formVisite = $this->createForm(VisiteType::class, $visite);

        $formVisite->handleRequest($request);
        if ($formVisite->isSubmitted() && $formVisite->isValid()) {
            $this->repository->add($visite, true);
            return $this->redirectToRoute('admin.voyages');
        }
        return $this->render("admin/admin.voyage.edit.html.twig", [
                    'visite' => $visite,
                    'formvisite' => $formVisite->createView()
        ]);
    }

    /**
     * @Route("/admin/ajout", name="admin.voyage.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response {
        $visite = new Visite();
        $formVisite = $this->createForm(VisiteType::class, $visite);

        $formVisite->handleRequest($request);
        if ($formVisite->isSubmitted() && $formVisite->isValid()) {
            $this->repository->add($visite, true);
            return $this->redirectToRoute('admin.voyages');
        }
        return $this->render("admin/admin.voyage.ajout.html.twig", [
                    'visite' => $visite,
                    'formvisite' => $formVisite->createView()
        ]);
    }
    
    /**
     * @Route("admin/sort/{champ}/{ordre}", name="admin.voyages.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
      public function sort($champ, $ordre):Response{
        $visites = $this->repository->findAllOrderBy($champ, $ordre);
        return $this->render("admin/admin.voyages.html.twig", [
            'visites' => $visites
        ]);
    }

    public function __construct(VisiteRepository $repository) {
        $this->repository = $repository;
    }

}
