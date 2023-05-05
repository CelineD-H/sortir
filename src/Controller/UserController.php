<?php

namespace App\Controller;

use App\Entity\Upload;
use App\Form\ModificationProfilType;
use App\Repository\GroupRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, FileUploader $fileUploader, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if(in_array('ROLE_ADMIN', $this->getUser()->getRoles())) { // si admin on cherche l'id
            $user = $userRepository->find($id);
        } else { // sinon on se modifie soit-même
            $user = $userRepository->findOneBy(['pseudo' => $this->getUser()->getUserIdentifier()]);
        }


        $form = $this->createForm(ModificationProfilType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $newMdp = $form->get('newPassword')->getData();

            if ($newMdp) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $newMdp));
            }
            $upload = new Upload();
            $file = $form->get('file')->getData();
            if($file){
                $uploadFilename = $fileUploader->upload($file);
                $upload->setFileName($uploadFilename);
                $user->setAvatar($uploadFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "Profil modifié avec succès !");
            return $this->redirectToRoute('user_view', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'ModificationProfilForm' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/view/{id}', name: 'view')]
    public function details(int $id, UserRepository $userRepository, GroupRepository $groupRepository): Response
    {
        $group = null;
        $user = $userRepository->find($id);

        if(!$user) {
            throw $this->createNotFoundException();
        }

        if ($user === $this->getUser()) {
            $group = $groupRepository->findGroupByUser($user);
        }

        return $this->render('user/view.html.twig', [
            "user" => $user,
            "groups" => $group,
        ]);
    }

    #[Route('/{id}/statut/{choice}', name: 'statut')]
    public function statut(int $id, string $choice, UserRepository $userRepository, SortieRepository $sortieRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);

        if(!$user) {
            throw $this->createNotFoundException();
        }

        switch ($choice) {
            case 'enable':
                $user->setActif(true);
                $entityManager->persist($user);
                $this->addFlash('success', "Le compte ".$user->getPseudo()." a été activé !");
                break;
            case 'disable':
                $user->setActif(false);
                $entityManager->persist($user);
                $this->addFlash('success', "Le compte ".$user->getPseudo()." a été désactivé !");
                break;
            case 'delete':
                $sorties = $sortieRepository->findBy(['organisateur' => $user]);
                for ($i = 0; $i < count($sorties); $i++) {
                    $sortieRepository->remove($sorties[$i]);
                    $this->addFlash('success', "La sortie " . $sorties[$i]->getNom() . " a été supprimée.");
                }

                $userRepository->remove($user);
                $this->addFlash('success', "Le compte ".$user->getPseudo()." a été supprimé !");
                break;
        }

        $entityManager->flush();

        return $this->redirectToRoute("user_list");
    }

    #[Route('/list', name: 'list')]
    public function list(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/list.html.twig', [
            "users" => $users,
        ]);
    }
}