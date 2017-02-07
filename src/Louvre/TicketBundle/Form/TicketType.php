<?php

namespace Louvre\TicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType ;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name',      TextType::class, array(
            'label' => ('Nom')))
        ->add('firstname', TextType::class, array(
            'label' => ('Prénom')))
        ->add('country',   TextType::class, array(
            'label' => ('Pays')))
        ->add('birthdate', BirthdayType::class,array(
            'label' => ('Naissance'),
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'html5' => 'false',
            'attr' => ['class' => 'datepicker2'],
            )) 
        ->add('reduction', CheckboxType::class, array(
            'required' => false,
            'label_attr' => ['id' => 'checkbox'],
            'label' => ('Tarif réduit')
        ))

        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\TicketBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_ticketbundle_ticket';
    }


}
