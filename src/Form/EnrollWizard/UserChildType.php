<?php

namespace App\Form\EnrollWizard;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

#this is used for forms
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

# Symfony4 best practice is to not use a submit type in the formType or Controller
#use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserChildType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, array(
              'label' => 'Nume',
              'attr' => array('class' => 'form-control')
            ))
            ->add('firstName', TextType::class, array(
              'label' => 'Prenume',
              'attr' => array('class' => 'form-control')
            ))
            ->add('username', HiddenType::class, array(
              'mapped' => true,
              'data' => 'temporary.username',
              // 'error_mapping' => array(
              //     '.' => 'lastName',
              // ),
            ))
            ->add('email', HiddenType::class, array(
              'mapped' => true,
              'data' => 'temp.email@iteachsmart.ro',
              // 'error_mapping' => array(
              //     '.' => 'lastName',
              // ),
            ))
            //TODO: generate random P@ssword
            ->add('password', HiddenType::class, array(
              'mapped' => true,
              'data' => '73mPa@$$Sm3dU',
              // 'error_mapping' => array(
              //     '.' => 'lastName',
              // ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => array('Default'),
            'error_mapping' => array(
                '.' => 'firstName',
            ),
        ]);
    }
}
