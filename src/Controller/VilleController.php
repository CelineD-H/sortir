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

            $this->addFlash('success', "La ville " . $ville->getNom() . " a bien été créée.");

            return $this->redirectToRoute('ville_view', [
                'id' => $ville->getId()
            ]);
        }

        return $this->render('ville/create.html.twig', [
            'villeForm' => $form->createView(),
            'referer' => $request->headers->get('referer')
        ]);
    }

    #[Route('/list', name: 'list')]
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


        if (!$ville) {
            throw $this->createNotFoundException();
        }

        return $this->render('ville/view.html.twig', [
            "ville" => $ville,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, VilleRepository $villeRepository): Response
    {
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            $this->redirectToRoute('sortie_home');
        }

        $ville = $villeRepository->find($id);
        $form = $this->createForm(VilleFormType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', "La ville " . $ville->getNom() . " a bien été modifiée.");

            return $this->redirectToRoute('ville_view', [
                'id' => $ville->getId()
            ]);
        }

        return $this->render('ville/edit.html.twig', [
            'villeEditForm' => $form->createView(),
            'ville' => $ville
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, Request $request, EntityManagerInterface $entityManager, VilleRepository $villeRepository): Response
    {
        $ville = $villeRepository->find($id);
        $route = "app_home";

        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            $villeRepository->remove($ville);
            $entityManager->flush();

            $this->addFlash('success', "La ville " . $ville->getNom() . " a bien été supprimée.");
            $route = "admin_dashboard";
        }

        return $this->redirectToRoute($route);

        /*return $this->render('ville/edit.html.twig', [
            'villeEditForm' => $form->createView(),
        ]);*/
    }
}
