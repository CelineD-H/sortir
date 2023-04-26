<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModificationProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/edit', name: 'edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserRepository $ur): Response
    {
        $user = $this->getUser();
        $id = $ur->findOneBy(['pseudo' => $user->getUserIdentifier()])->getId();

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
}