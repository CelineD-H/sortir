<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleFormType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ville', name: 'ville_')]
class VilleController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ville = new Ville();

        $form = $this->createForm(VilleFormType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();
        }

        return $this->render('ville/create.html.twig', [
            'villeForm' => $form->createView(),
        ]);
    }
    #[Route('/', name: 'list')]
    public function list(Request $request, EntityManagerInterface $entityManager, VilleRepository $villeRepository): Response
    {
        $villes = $villeRepository->findAll();

        return $this->render('ville/index.html.twig', [
            "villes" => $villes
        ]);
    }
    #[Route('/view/{id}', name: 'view')]
    public function details(int $id, VilleRepository $villeRepository): Response
    {
        $ville = $villeRepository->find($id);


        if(!$ville) {
            throw $this->createNotFoundException();
        }

        return $this->render('ville/view.html.twig', [
            "ville" => $ville,
        ]);
    }
}
