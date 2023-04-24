<?php

namespace App\Controller;

use App\Form\ModificationProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ModificationProfilType::class);
        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid()) {
            //TODO: faire les actions d'édition de l'user
        }

        return $this->render('user/index.html.twig', [
            'ModificationProfilForm' => $form->createView()
        ]);
    }
}
