<?php

namespace App\Controller;

use App\Form\LieuFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    #[Route('/lieu', name: 'app_lieu')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lieu = new Lieu();

        $form = $this->createForm(LieuFormType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lieu);
            $entityManager->flush();


            return $this->render('lieu/index.html.twig', [
                'lieuForm' => 'LieuController',
            ]);
        }
    }
}

