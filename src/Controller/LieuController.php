<?php

namespace App\Controller;

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





        return $this->render('lieu/index.html.twig', [
            'lieuForm' => 'LieuController',
        ]);
    }
}
