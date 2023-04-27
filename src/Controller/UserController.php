<?php

namespace App\Controller;

use App\Form\ModificationProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $id = $userRepository->findOneBy(['pseudo' => $user->getUserIdentifier()])->getId();

        $form = $this->createForm(ModificationProfilType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "Profil modifié avec succès !");
            return $this->redirectToRoute('user_view', [
                'id' => $id
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
                $route = "admin_dashboard";
        }

        $this->addFlash('success', "Le compte ".$userASupprimer->getPseudo()." a été supprimé !");

        return $this->redirectToRoute($route, [
            'user' => $userASupprimer
        ]);

        /*return $this->render('user/delete.html.twig', [
            "user" => $user
        ]);*/
    }
}