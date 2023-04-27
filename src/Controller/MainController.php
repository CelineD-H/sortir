<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieFiltreType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function list(Request $request, EntityManagerInterface $entityManager, SortieRepository $sortieRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $sortie = new Sortie();
        $form = $this->createForm(SortieFiltreType::class, $sortie);

        if ($form->isSubmitted() && $form->isValid()) {
            //TODO: appliquer les filtres du formulaire si le formulaire de filtre est rempli
        } else {
            $sorties = $sortieRepository->findAll();
        }

        return $this->render('main/index.html.twig', [
            "sorties" => $sorties,
            "sortiesForm" => $form->createView()
        ]);
    }
}
