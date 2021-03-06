<?php

namespace App\Form;

use App\Entity\AccountReceipt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

#this is used for forms
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class AccountInvoiceReceiptType extends AbstractType
{
    private $formCount;

    public function __construct()
    {
        $this->formCount = 0;
    }

    public function getBlockPrefix()
    {
        return parent::getBlockPrefix().'_'.$this->formCount;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        ++$this->formCount;

        $builder
            ->add('receiptDate', DateType::class, array(
              'label' => 'Dată Chitanță',
              'input' => 'datetime',
              'widget' => 'single_text',
              'attr' => array('type' => 'datetime', 'class' => 'form-control'),
            ))
            ->add('receiptSerial', TextType::class, array(
              'label' => 'Serie',
              'attr' => array('class' => 'form-control'),
            ))
            ->add('receiptNumber', NumberType::class, array(
              'label' => 'Număr',
              'attr' => array('class' => 'form-control'),
            ))
            ->add('totalPaid', MoneyType::class, array(
              'label'    => 'Total Achitat',
              'currency' => 'RON',
              'data' => $options['total'],
              'scale' => 2,
              'mapped' => false,
              'attr' => array('class' => 'form-control'),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccountReceipt::class,
            'total' => Integer::class,
            'validation_groups' => array('receiptDetails'),
        ]);
    }
}
