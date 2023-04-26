<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function list(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, CampusRepository $campusRepository): Response
    {
        $users = $userRepository->findAll();
        $campus = $campusRepository->findAll();

        return $this->render('admin/index.html.twig', [
            "users" => $users,
            "campus" => $campus,
            'controller_name' => 'AdminController',
        ]);
    }
}
