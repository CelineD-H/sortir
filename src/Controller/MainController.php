<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function list(Request $request, EntityManagerInterface $entityManager, SortieRepository $sortieRepository, UserRepository $userRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        //$sortie = new Sortie();

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
            $user = $userRepository->findOneBy(['pseudo' => $this->getUser()->getUserIdentifier()]);
            $sorties = $sortieRepository->filtreSorties($filtres, $user);
            //dd($sorties);
            //dd($form->getData());
            //$sortie->setEtat(1);
            //dd($sortie->getCampus()->getId());
            //$sorties = $sortieRepository->findBy(['Campus' => $sortie->getCampus()->getId()]);
            //$sorties = $sortieRepository->findAll();
        } else {
            $sorties = $sortieRepository->findAll();
        }

        return $this->render('main/index.html.twig', [
            "sorties" => $sorties,
            "sortiesForm" => $form->createView()
        ]);
    }
}