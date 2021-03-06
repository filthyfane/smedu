<?php

namespace App\Form;

use App\Entity\SchoolService;
use App\Entity\SchoolYear;
use App\Entity\SchoolUnit;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

#this is used for forms
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
//use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SchoolServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!empty($options['school_unit'])) {

          //echo'<script>console.log(\'single option\')</script>';

          $unitchoice = array();
          $unitchoice[$options['school_unit']->getUnitname()] = $options['school_unit'];

          $currentSchoolyear=$options['school_unit']->getSchoolyear();

          $yearchoice = array();
          $yearchoice[$currentSchoolyear->getYearlabel()] = $currentSchoolyear;

          $builder
              ->add('schoolYear', ChoiceType::class, array(
                'choices'  => $yearchoice,
                'attr' => array(
                  'class' => 'col-4 form-control',
                  'readonly' => 'readonly',
                ),
              ))
              ->add('schoolUnit', ChoiceType::class, array(
                'choices'  => $unitchoice,
                'attr' => array(
                  'class' => 'col-4 form-control',
                  'readonly' => 'readonly',
                ),
              ))
          ;
        }

        if (!empty($options['school_units'])) {

          //echo'<script>console.log(\'multiple options\')</script>';

          $unitchoices = array();
          foreach ($options['school_units'] as $schoolunit) {
            $label = $schoolunit->getUnitname();
            $unitchoices[$label] = $schoolunit;
          }

          $currentSchoolyear=$options['school_year'];

          $yearchoices = array();
          $yearchoices[$currentSchoolyear->getYearlabel()] = $currentSchoolyear;


          $builder
              ->add('schoolYear', ChoiceType::class, array(
                'choices'  => $yearchoices,
                'attr' => array(
                  'class' => 'col-4 form-control',
                  'readonly' => 'readonly',
                ),
              ))
              ->add('schoolUnit', ChoiceType::class, array(
                'choices'  => $unitchoices,
                'attr' => array(
                  'class' => 'col-4 form-control',
                ),
              ))
          ;

        }

        $builder
            ->add('serviceName', TextType::class, array(
              'label'    => 'Denumire Serviciu',
              'attr' => array('class' => 'col-6 form-control')
            ))
            ->add('serviceDescription', TextareaType::class, array(
              'label'    => 'Descriere',
              'attr' => array('class' => 'col-6 form-control'),
            ))
            ->add('inAdvance', CheckboxType::class, array(
              'label'    => 'Taxare în avans?',
              'required' => false,
              'attr' => array('class' => 'form-check form-check-inline'),
            ))
            ->add('servicePrice', MoneyType::class, array(
              'label'    => 'Taxă Lunară',
              'currency' => 'RON',
              'scale' => 2,
              'attr' => array('class' => 'col-3 form-control'),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SchoolService::class,
            'school_units' => null,
            'school_unit' => null,
            'school_year' => null,
        ]);
    }
}
