<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Form\EtatFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/etat', name: 'etat_')]

class EtatController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etat = new Etat();

        $form = $this->createForm(EtatFormType::class, $etat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etat);
            $entityManager->flush();
        }
        return $this->render('etat/create.html.twig', [
            'etatForm' => $form->createView(),
        ]);
    }
}
