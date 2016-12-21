<?php

namespace Louvre\TicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;






class GlobalTicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choix = array(
            'Journée' => 'Journée',
            'Demi-journée' => 'Demi-journée',
            );

        $builder
        ->add('datevisit', DateType::class, array(
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'html5' => 'false',
            'attr' => ['class' => 'datepicker'],
            ))

        ->add('ticketype', ChoiceType::class , array(
            'multiple' => false,
            'expanded' => true,
            'choices' => $choix,
           ))
        ->add('mail',      EmailType::class, array(
            'attr' => ['id' => 'monemail']))
        ->add('tickets',   CollectionType::class, array(
            'entry_type'   =>  TicketType::class,
            'allow_add'    => true,
            'allow_delete' => true,
            'by_reference' => false,
         ))
        ->add('save',      SubmitType::class, array(
            'attr' => array('class' => 'btn btn-primary')))       
        ;

    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\TicketBundle\Entity\GlobalTicket'
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_ticketbundle_globalticket';
    }


}
