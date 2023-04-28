<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function list(CampusRepository $campusRepository,
                         LieuRepository $lieuRepository,
                         SortieRepository $sortieRepository,
                         UserRepository $userRepository,
                         VilleRepository $villeRepository
                        ): Response
    {
        $campus = $campusRepository->findAll();
        $lieux = $lieuRepository->findAll();
        $sorties = $sortieRepository->findAll();
        $users = $userRepository->findAll();
        $villes = $villeRepository->findAll();

        return $this->render('admin/index.html.twig', [
            "campus" => $campus,
            "lieux" => $lieux,
            "sorties" => $sorties,
            "users" => $users,
            "villes" => $villes,
        ]);
    }
}
