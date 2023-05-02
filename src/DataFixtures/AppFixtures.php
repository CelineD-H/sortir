<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures  extends Fixture
{
    private $userPasswordHasher;
    private $entityManager;


    public function __construct( UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->entityManager = $entityManager;

    }

    public function load(ObjectManager $manager): void
    {

        $etat1 = new Etat();
        $etat1->setLibelle('Créée');
        $manager->persist($etat1);

        $etat2 = new Etat();
        $etat2->setLibelle('Ouverte');
        $manager->persist($etat2);

        $etat3 = new Etat();
        $etat3->setLibelle('Clôturée');
        $manager->persist($etat3);

        $etat4 = new Etat();
        $etat4->setLibelle('Activité en cours');
        $manager->persist($etat4);

        $etat5 = new Etat();
        $etat5->setLibelle('Passée');
        $manager->persist($etat5);

        $etat6 = new Etat();
        $etat6->setLibelle('Annulée');
        $manager->persist($etat6);

        $ville1 = new Ville();
        $ville1->setNom('Nantes');
        $ville1->setCodePostal('44000');
        $manager->persist($ville1);

        $ville2 = new Ville();
        $ville2->setNom('Brantes');
        $ville2->setCodePostal('84390');
        $manager->persist($ville2);

        $ville3 = new Ville();
        $ville3->setNom('Marseille');
        $ville3->setCodePostal('13016');
        $manager->persist($ville3);

        $ville4 = new Ville();
        $ville4->setNom('Lyon');
        $ville4->setCodePostal('69000');
        $manager->persist($ville4);

        $ville5 = new Ville();
        $ville5->setNom('Montcuq');
        $ville5->setCodePostal('46800');
        $manager->persist($ville5);

        $ville6 = new Ville();
        $ville6->setNom('Céreste');
        $ville6->setCodePostal('04280');
        $manager->persist($ville6);

        $ville7 = new Ville();
        $ville7->setNom('Dunkerque');
        $ville7->setCodePostal('59140');
        $manager->persist($ville7);

        $ville8 = new Ville();
        $ville8->setNom('Les Deux Alpes');
        $ville8->setCodePostal('38860');
        $manager->persist($ville8);

        $lieu1 = new Lieu();
        $lieu1->setNom('Bar');
        $lieu1->setRue('Rue de la soif');
        $lieu1->setLatitude('0');
        $lieu1->setLongitude('0');
        $lieu1->setVille($ville1);
        $manager->persist($lieu1);

        $lieu2 = new Lieu();
        $lieu2->setNom('Mont Ventoux');
        $lieu2->setRue('D974');
        $lieu2->setLatitude('44.174');
        $lieu2->setLongitude('5.284');
        $lieu2->setVille($ville2);
        $manager->persist($lieu2);

        $lieu3 = new Lieu();
        $lieu3->setNom('Montcuq');
        $lieu3->setRue('Principale');
        $lieu3->setLatitude('44.333328');
        $lieu3->setLongitude('1.21667');
        $lieu3->setVille($ville6);
        $manager->persist($lieu3);

        $lieu4 = new Lieu();
        $lieu4->setNom('Tour de la Citadelle');
        $lieu4->setRue("Avenue de l'université");
        $lieu4->setLatitude('51.098707');
        $lieu4->setLongitude('2.373288');
        $lieu4->setVille($ville8);
        $manager->persist($lieu4);

        $campus1 = new Campus();
        $campus1->setNom('Campus en ligne');
        $manager->persist($campus1);

        $campus2 = new Campus();
        $campus2->setNom('La Sorbonne');
        $manager->persist($campus2);

        $campus3 = new Campus();
        $campus3->setNom("L'école des Fans");
        $manager->persist($campus3);
        
        $user1 = new User();
        $user1->setEmail('celinette@cel.com');
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setPassword($this->userPasswordHasher->hashPassword($user1, 'celine'));
        $user1->setFirstName('Céline');
        $user1->setLastName('Dudu');
        $user1->setActif(1);
        $user1->setPseudo('Célinette');
        $user1->setAvatar('moi-644b73ac33b80.jpg');
        $user1->setTelephone('0654234578');
        $user1->setCampus($campus1);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('extracode@alan.com');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword($this->userPasswordHasher->hashPassword($user2, 'celine'));
        $user2->setFirstName('Alan2');
        $user2->setLastName('Piron-LaFleur');
        $user2->setActif(1);
        $user2->setPseudo('Nicholas_Cage');
        $user2->setAvatar('Alan.png');
        $user2->setTelephone('0654234578');
        $user2->setCampus($campus1);
        $manager->persist($user2);

        $user3 = new User();
        $user3->setEmail('iron@man.fr');
        $user3->setRoles(['ROLE_ADMIN']);
        $user3->setPassword($this->userPasswordHasher->hashPassword($user3, 'mina123'));
        $user3->setFirstName('tony');
        $user3->setLastName('stark');
        $user3->setActif(1);
        $user3->setPseudo('ironman');
        $user3->setAvatar('ironman-644ae7c84adf2.webp');
        $user3->setTelephone('0612345678');
        $user3->setCampus($campus1);
        $manager->persist($user3);

        $user4 = new User();
        $user4->setEmail('test@test.fr');
        $user4->setRoles(['ROLE_USER']);
        $user4->setPassword($this->userPasswordHasher->hashPassword($user4, 'test123'));
        $user4->setFirstName('test');
        $user4->setLastName('test');
        $user4->setActif(1);
        $user4->setPseudo('test');
        $user4->setAvatar('julien-644b73cc82fe4.jpg');
        $user4->setTelephone('0612333214');
        $user4->setCampus($campus1);
        $manager->persist($user4);

        $user5 = new User();
        $user5->setEmail('seb@superdev.fr');
        $user5->setRoles(['ROLE_ADMIN']);
        $user5->setPassword($this->userPasswordHasher->hashPassword($user5, 'seb123'));
        $user5->setFirstName('seb');
        $user5->setLastName('ro');
        $user5->setActif(1);
        $user5->setPseudo('sebiii');
        $user5->setAvatar('pandalove-644b056258181.gif');
        $user5->setTelephone('0612333214');
        $user5->setCampus($campus1);
        $manager->persist($user5);

        $user6 = new User();
        $user6->setEmail('arthur@test.fr');
        $user6->setRoles(['ROLE_USER']);
        $user6->setPassword($this->userPasswordHasher->hashPassword($user6, 'arthur'));
        $user6->setFirstName('Arthur');
        $user6->setLastName('Minimoys');
        $user6->setActif(1);
        $user6->setPseudo('Tutur');
        $user6->setAvatar('Anthony.png');
        $user6->setTelephone('0612333214');
        $user6->setCampus($campus1);
        $manager->persist($user6);

        $sortie1 = new Sortie();
        $sortie1->setOrganisateur($user6);
        $sortie1->addParticipant($user2);
        $sortie1->addParticipant($user1);
        $sortie1->addParticipant($user6);
        $sortie1->addParticipant($user4);
        $sortie1->addParticipant($user3);
        $sortie1->setNom('Manif du 1er Mai');
        $sortie1->setDateHeureDebut(new \DateTime('2022-05-01 09:00:00'));
        $sortie1->setDuree(new \DateTime('08:00:00'));
        $sortie1->setDateLimiteInscription(new \DateTime('2023-04-30'));
        $sortie1->setNbInscriptionsMax(999);
        $sortie1->setInfosSortie('test');
        $sortie1->setDeleteMessage('test de suppression');
        $sortie1->setEtat($etat2);
        $sortie1->setLieu($lieu1);
        $sortie1->setCampus($campus3);
        $manager->persist($sortie1);

        $sortie2 = new Sortie();
        $sortie2->addParticipant($user2);
        $sortie2->addParticipant($user1);
        $sortie2->addParticipant($user6);
        $sortie2->addParticipant($user4);
        $sortie2->setOrganisateur($user5);
        $sortie2->setNom('test');
        $sortie2->setDateHeureDebut(new \DateTime('2222-12-12 10:10:00'));
        $sortie2->setDuree(new \DateTime('10:10:00'));
        $sortie2->setDateLimiteInscription(new \DateTime('2222-12-11'));
        $sortie2->setNbInscriptionsMax(10);
        $sortie2->setInfosSortie('test');
        $sortie2->setDeleteMessage( '');
        $sortie2->setEtat($etat1);
        $sortie2->setLieu($lieu1);
        $sortie2->setCampus($campus1);
        $manager->persist($sortie2);

        $sortie3 = new Sortie();
        $sortie3->addParticipant($user2);
        $sortie3->addParticipant($user1);
        $sortie3->setOrganisateur($user2);
        $sortie3->setNom('Balade en forêt');
        $sortie3->setDateHeureDebut(new \DateTime('2023-04-30 14:00:00'));
        $sortie3->setDuree(new \DateTime('04:00:00'));
        $sortie3->setDateLimiteInscription(new \DateTime('2023-04-25'));
        $sortie3->setNbInscriptionsMax(2);
        $sortie3->setInfosSortie('Promenons-nous dans les bois, pendant que le loup ...');
        $sortie3->setDeleteMessage( 'Annulé par moi (seb)');
        $sortie3->setEtat($etat6);
        $sortie3->setLieu($lieu1);
        $sortie3->setCampus($campus1);
        $manager->persist($sortie3);
       // dd($sortie3);

        $sortie4 = new Sortie();
        $sortie4->setOrganisateur($user2);
        $sortie4->addParticipant($user4);
        $sortie4->addParticipant($user1);
        $sortie4->addParticipant($user2);
        $sortie4->addParticipant($user3);
        $sortie4->addParticipant($user5);
        $sortie4->addParticipant($user6);
        $sortie4->setNom('Pêche aux moules');
        $sortie4->setDateHeureDebut(new \DateTime('2023-04-25 07:30:00'));
        $sortie4->setDuree(new \DateTime('06:00:00'));
        $sortie4->setDateLimiteInscription(new \DateTime('2023-04-24'));
        $sortie4->setNbInscriptionsMax(6);
        $sortie4->setInfosSortie('A la pêche aux moules moules moules...');
        $sortie4->setDeleteMessage( '');
        $sortie4->setEtat($etat5);
        $sortie4->setLieu($lieu1);
        $sortie4->setCampus($campus1);
        $manager->persist($sortie4);

        $sortie5 = new Sortie();
        $sortie5->setOrganisateur($user4);
        $sortie5->addParticipant($user5);
        $sortie5->addParticipant($user1);
        $sortie5->addParticipant($user2);
        $sortie5->setNom('Rencontre un dev');
        $sortie5->setDateHeureDebut(new \DateTime('2023-04-29 15:00:00'));
        $sortie5->setDuree(new \DateTime('00:30:00'));
        $sortie5->setDateLimiteInscription(new \DateTime('2023-04-28'));
        $sortie5->setNbInscriptionsMax(3);
        $sortie5->setInfosSortie('Rencontre un super dev');
        $sortie5->setDeleteMessage( 'Bisous');
        $sortie5->setEtat($etat1);
        $sortie5->setLieu($lieu1);
        $sortie5->setCampus($campus1);
        $manager->persist($sortie5);

        $sortie6 = new Sortie();
        $sortie6->setOrganisateur($user2);
        $sortie6->addParticipant($user4);
        $sortie6->addParticipant($user1);
        $sortie6->addParticipant($user2);
        $sortie6->addParticipant($user3);
        $sortie6->addParticipant($user5);
        $sortie6->addParticipant($user6);
        $sortie6->setNom('Le mont Ventoux à vélo');
        $sortie6->setDateHeureDebut(new \DateTime('2023-05-11 06:00:00'));
        $sortie6->setDuree(new \DateTime('12:00:00'));
        $sortie6->setDateLimiteInscription(new \DateTime('2023-04-22'));
        $sortie6->setNbInscriptionsMax(10);
        $sortie6->setInfosSortie("C'est une blague");
        $sortie6->setDeleteMessage( "C'était une blague !! Je fais pas de vélo moi !!");
        $sortie6->setEtat($etat6);
        $sortie6->setLieu($lieu2);
        $sortie6->setCampus($campus1);
        $manager->persist($sortie6);

        $manager->flush();
    }
}
