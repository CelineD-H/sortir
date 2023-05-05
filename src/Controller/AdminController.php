<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\User;
use App\Repository\CampusRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    #[Route('/csv', name: 'csv')]
    public function integrateCsvFile(Request $request, EntityManagerInterface $manager, CampusRepository $campusRepository, UserPasswordHasherInterface $userPasswordHasher){

        $form = $this->createFormBuilder()
            ->add('fichier', FileType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $file = $form->get('fichier')->getData();

            if ($file) {
                $csv = \League\Csv\Reader::createFromPath($file->getPathname(), 'r');
                $csv->setHeaderOffset(0);

                foreach ($csv as $row) {
                    $user = new User();
                    $user->setEmail($row['email']);
                    $user->setRoles([$row['role']]);
                    $user->setPassword($userPasswordHasher->hashPassword($user, $row['password']));
                    $user->setFirstName($row['firstName']);
                    $user->setLastName($row['lastName']);
                    $user->setActif($row['actif']);
                    $user->setPseudo($row['pseudo']);
                    $user->setAvatar($row['avatar']);
                    $user->setTelephone($row['telephone']);
                    $user->setCampus($campusRepository->find($row['campus']));

                    $manager->persist($user);
                }
            }
            $manager->flush();

            $this->addFlash('success', 'Intégration par CSV réussie');
            return $this->redirectToRoute('admin_dashboard');
        }
        return $this->render('csv/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
