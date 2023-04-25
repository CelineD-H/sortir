<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleFormType;
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
}
