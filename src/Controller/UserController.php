<?php

namespace App\Controller;

use App\Entity\Upload;
use App\Form\ModificationProfilType;
use App\Repository\CampusRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, FileUploader $fileUploader): Response
    {
        if(in_array('ROLE_ADMIN', $this->getUser()->getRoles())) { // si admin on cherche l'id
            $user = $userRepository->find($id);
        } else { // sinon on se modifie soit-même
            $user = $userRepository->findOneBy(['pseudo' => $this->getUser()->getUserIdentifier()]);
        }


        $form = $this->createForm(ModificationProfilType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
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
            'ModificationProfilForm' => $form->createView()
        ]);
    }

    #[Route('/view/{id}', name: 'view')]
    public function details(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if(!$user) {
            throw $this->createNotFoundException();
        }

        return $this->render('user/view.html.twig', [
            "user" => $user
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, UserRepository $userRepository): Response
    {
        $userASupprimer = $userRepository->find($id);
        $userQuiSupprimer = $this->getUser();

        if(!$userASupprimer) {
            throw $this->createNotFoundException();
        }

        $route = "app_home";

        if (in_array('ROLE_ADMIN', $userQuiSupprimer->getRoles()) || $userQuiSupprimer === $userASupprimer) {
            $userRepository->remove($userASupprimer, true);

            if (in_array('ROLE_ADMIN', $userQuiSupprimer->getRoles()) && $userQuiSupprimer !== $userASupprimer)
                $route = "user_list";
        }

        $this->addFlash('success', "Le compte ".$userASupprimer->getPseudo()." a été supprimé !");

        return $this->redirectToRoute($route, [
            'user' => $userASupprimer
        ]);

        /*return $this->render('user/delete.html.twig', [
            "user" => $user
        ]);*/
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