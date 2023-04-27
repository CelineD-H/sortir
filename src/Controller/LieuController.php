<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\LieuFormType;
use App\Form\SortieFiltreType;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lieu', name: 'lieu_')]
class LieuController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lieu = new Lieu();

        $form = $this->createForm(LieuFormType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lieu);
            $entityManager->flush();
        }

        return $this->render('lieu/create.html.twig', [
            'lieuForm' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'list')]
    public function list(Request $request, EntityManagerInterface $entityManager, LieuRepository $lieuRepository): Response
    {
        $lieux = $lieuRepository->findAll();

        return $this->render('lieu/index.html.twig', [
            "lieux" => $lieux
        ]);
    }

    #[Route('/view/{id}', name: 'view')]
    public function details(int $id, LieuRepository $lieuRepository): Response
    {
        $lieu = $lieuRepository->find($id);


        if(!$lieu) {
            throw $this->createNotFoundException();
        }

        return $this->render('lieu/view.html.twig', [
            "lieu" => $lieu,
        ]);
    }

}

