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

        }
        return $this->render('groupe/index.html.twig', [
            'groupeForm' => $form->createView()
        ]);
    }

    #[Route('/list', name: 'list')]
    public function list(Request $request, EntityManagerInterface $entityManager, GroupeRepository $groupeRepository): Response
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
}