<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lieu', name: 'lieu_')]
class LieuController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
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
}

