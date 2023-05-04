<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Form\SortieDeleteFormType;
use App\Form\SortieFormType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sortie', name: 'sortie_')]
class SortieController extends AbstractController
{
    #[Route('/..', name: 'home')]
    public function home(Request $request, SortieRepository $sortieRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createFormBuilder()
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'label' => 'Campus',
                'choice_label' => 'nom',
                'required' => false
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie',
                'required' => false
            ])
            ->add('orga', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false
            ])
            ->add('isInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false
            ])
            ->add('noInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false
            ])
            ->add('passees', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false
            ])->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filtres = $form->getData();
            $sorties = $sortieRepository->filtreSorties($filtres, $this->getUser());
        } else {
            $sorties = $sortieRepository->allSorties();
        }
        dump($sorties);

        return $this->render('main/index.html.twig', [
            "sorties" => $sorties,
            "sortiesForm" => $form->createView()
        ]);
    }

    #[Route('/create', name: 'create')]
    public function createSortie(Request $request, EntityManagerInterface $entityManager, EtatRepository $etatRepository): Response
    {
        $user = $this->getUser();
        $etat = $etatRepository->find(1);

        $sortie = new Sortie();
        $sortie->setEtat($etat);
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
            'referer' => $request->headers->get('referer')
        ]);
    }

    #[Route('/view/{id}', name: 'view')]
    public function details(int $id, SortieRepository $repository): Response
    {
        $sortie = $repository->find($id);

        $expiration = date('Y-m-d H:i:s',date_timestamp_get($sortie->getDateHeureDebut()) + date_timestamp_get($sortie->getDuree())+3600);


        return $this->render('sortie/view.html.twig', [
            "sortie" => $sortie,
            "dateExpiration" => $expiration
        ]);
    }


    #[Route('/join/{id}', name: 'join')]
    public function join(int $id, SortieRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $sortie = $repository->find($id);

        $sortie->addParticipant($user);

        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('sortie_view', [
            'id' => $id
        ]);
    }

    #[Route('/quit/{id}', name: 'quit')]
    public function quit(int $id, SortieRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $sortie = $repository->find($id);

        $sortie->removeParticipant($user);

        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('sortie_view', [
            'id' => $id
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, SortieRepository $repository, EntityManagerInterface $entityManager, EtatRepository $etatRepository, Request $request): Response
    {
        $user = $this->getUser();
        $sortie = $repository->find($id);

        if($user !== $sortie->getOrganisateur() && !in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('sortie_view', [
                'id' => $id
            ]);
        }



        $form = $this->createForm(SortieDeleteFormType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etat = $etatRepository->findOneBy(['libelle' => 'Annulée']);
            $sortie->setEtat($etat);
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