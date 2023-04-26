<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieDeleteFormType;
use App\Form\SortieFormType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sortie', name: 'sortie_')]
class SortieController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function createSortie(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($this->getUser());
        $sortie = new Sortie();
        $sortie->setEtat(0);
        $sortie->setOrganisateur($user);
        $sortie->addParticipant($user);

        $form = $this->createForm(SortieFormType::class, $sortie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sortie_view', [
                'id' => $sortie->getId()
                ]);
        }

        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $form->createView(),
        ]);
    }

    #[Route('/view/{id}', name: 'view')]
    public function details(int $id, SortieRepository $repository): Response
    {
        $sortie = $repository->find($id);
        $dateDebut = date('Y-m-d H:i:s', date_timestamp_get($sortie->getDateHeureDebut()));
        $dateDuree = date('Y-m-d H:i:s', date_timestamp_get($sortie->getDuree())+3600);
        $expirationString = date('d/m/Y à H:i', strtotime($dateDebut) + strtotime($dateDuree));
        $expiration = date('Y-m-d H:i:s', strtotime($dateDebut) + strtotime($dateDuree));

        if(!$sortie) {
            throw $this->createNotFoundException();
        }

        return $this->render('sortie/view.html.twig', [
            "sortie" => $sortie,
            "dateExpiration" => $expiration,
            "dateExpirationString" => $expirationString
        ]);
    }


    #[Route('/join/{id}', name: 'join')]
    public function join(int $id, SortieRepository $repository, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($this->getUser());
        $sortie = $repository->find($id);

        $sortie->addParticipant($user);

        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('sortie_view', [
            'id' => $id
        ]);
    }

    #[Route('/quit/{id}', name: 'quit')]
    public function quit(int $id, SortieRepository $repository, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($this->getUser());
        $sortie = $repository->find($id);

        $sortie->removeParticipant($user);

        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('sortie_view', [
            'id' => $id
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, SortieRepository $repository, EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request): Response
    {
        $user = $userRepository->find($this->getUser());
        $sortie = $repository->find($id);

        if($user !== $sortie->getOrganisateur()) {
            return $this->redirectToRoute('sortie_view', [
                'id' => $id
            ]);
        }

        $sortie->setEtat(1);

        $form = $this->createForm(SortieDeleteFormType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sortie_view', [
                'id' => $id
            ]);
        }

        return $this->render('sortie/delete.html.twig', [
            'sortieDeleteForm' => $form->createView()
        ]);
    }
}