<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModificationProfilType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $builder
            ->add('pseudo', null, [
                'data' => $user->getPseudo()
            ])
            ->add('firstName', null, [
                'data' => $user->getFirstName()
            ])
            ->add('lastName', null, [
                'data' => $user->getLastName()
            ])
            ->add('email', null, [
                'data' => $user->getEmail()
            ])
            ->add('password')
            //->add('avatar')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => User::class,
        ]);
    }
}
