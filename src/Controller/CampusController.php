<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusFormType;
use App\Repository\CampusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/campus', name: 'campus_')]
class CampusController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $campus = new Campus();

        $form = $this->createForm(CampusFormType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($campus);
            $entityManager->flush();
        }
        return $this->render('campus/create.html.twig', [
            'campusForm' => $form->createView(),
        ]);
    }

    #[Route('/list', name: 'list')]
    public function list(Request $request, EntityManagerInterface $entityManager, CampusRepository $campusRepository): Response
    {
        $campus = $campusRepository->findAll();

        return $this->render('campus/list.html.twig', [
            "campus" => $campus,
        ]);
    }
}
