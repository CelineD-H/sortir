<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupeFormType;
use App\Repository\CampusRepository;
use App\Repository\GroupRepository;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/groupe', name:'groupe_')]
class GroupeController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $group = new Group();

        $form = $this->createForm(GroupeFormType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($group);
            $entityManager->flush();

            $this->addFlash('success', "Le groupe " . $group->getNom() . " a bien été créé.");

            for ($i = 0; $i < count($group->getUsers()); $i++) {
                $this->addFlash('success', "L'utilisateur " . $group->getUsers()->get($i)->getFirstName() . " " . $group->getUsers()->get($i)->getLastName() . " a été ajouté au groupe.");
            }

            return $this->redirectToRoute('user_view', [
                'id' => $this->getUser()->getId()
            ]);
        }
        return $this->render('groupe/index.html.twig', [
            'groupeForm' => $form->createView()
        ]);
    }

    #[Route('/list', name: 'list')]
    public function list(Request $request, EntityManagerInterface $entityManager, GroupRepository $groupeRepository): Response
    {
        $groupes = $groupeRepository->findAll();

        return $this->render('groupe/list.html.twig', [
            "groupes" => $groupes,
        ]);
    }

    #[Route('/view/{id}', name: 'view')]
    public function details(int $id, GroupRepository $groupRepository): Response
    {
        $groupe = $groupRepository->find($id);


        if(!$groupe) {
            throw $this->createNotFoundException();
        }

        return $this->render('groupe/view.html.twig', [
            "groupe" => $groupe,
        ]);
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(int $id, GroupRepository $groupRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $groupe = $groupRepository->find($id);

        if(!$groupe) {
            throw $this->createNotFoundException();
        }

        $users = $groupe->getUsers();

        $form = $this->createForm(GroupeFormType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            for ($i = 0; $i < count($users); $i++) {
                $groupe->addUser($users[$i]);
            }

            $entityManager->persist($groupe);
            $entityManager->flush();

            $this->addFlash('success', "Le groupe " . $groupe->getNom() . " a bien été modifié.");

            for ($i = 0; $i < count($groupe->getUsers()); $i++) {
                $this->addFlash('success', "L'utilisateur " . $groupe->getUsers()->get($i)->getFirstName() . " " . $groupe->getUsers()->get($i)->getLastName() . " a été ajouté au groupe.");
            }

            return $this->redirectToRoute('groupe_view', [
                'id' => $groupe->getId()
            ]);
        }
        return $this->render('groupe/index.html.twig', [
            'groupeForm' => $form->createView()
        ]);
    }
}