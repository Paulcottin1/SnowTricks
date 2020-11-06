<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

Class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom : ',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom : ',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email : ',
            ])
            ->add('avatar', FileType::class,[
                'data_class' => null,
                'label' => 'Changer votre avatar :',
                'required' => false,
                'empty_data' => $user->getAvatar(),

            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe : ',
                'required' => false,
                'empty_data' => $user->getPassword()
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'user' => User::class,
        ]);
    }
}
