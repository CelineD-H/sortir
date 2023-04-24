<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonControleur extends AbstractController
{
    #[Route('/participants', name: 'participants')]
    public function listeParticipants(): Response

    {
        $repository = $this->getDoctrine()->getRepository(Participant::class);
        $participants = $repository->findAll();

        return $this->render('participants.html.twig', [
            'participants' => $participants,
        ]);
    }

    #[Route('/sorties', name: 'sorties')]
    public function listeSorties(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $repository->findAll();

        return $this->render('sorties.html.twig', [
            'sorties' => $sorties,
        ]);
    }

    #[Route('/campus', name: 'campus')]
    public function listeCampus(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Campus::class);
        $campus = $repository->findAll();

        return $this->render('campus.html.twig', [
            'campus' => $campus,
        ]);
    }

    #[Route('/lieux', name: 'lieux')]
    public function listeLieux(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Lieu::class);
        $lieux = $repository->findAll();

        return $this->render('lieux.html.twig', [
            'lieux' => $lieux,
        ]);
    }

    #[Route('/villes', name: 'villes')]
    public function listeVilles(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Ville::class);
        $villes = $repository->findAll();

        return $this->render('villes.html.twig', [
            'villes' => $villes,
        ]);
    }

    #[Route('/etats', name: 'etats')]
    public function listeEtats(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Etat::class);
        $etats = $repository->findAll();

        return $this->render('etats.html.twig', [
            'etats' => $etats,
        ]);
    }
}
