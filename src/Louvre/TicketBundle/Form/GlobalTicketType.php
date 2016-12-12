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






class GlobalTicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
        ->add('datevisit', DateType::class)
        ->add('ticketype', CheckboxType::class, array(
            'label'    => 'Billet demi-journée',
            'required' => false,
        )  )
        ->add('mail',      EmailType::class)
        ->add('tickets',   CollectionType::class, array(
        'entry_type'   =>  TicketType::class,
        'allow_add'    => true,
        'allow_delete' => true,
        'by_reference' => false,
        
         ))
        ->add('save',      SubmitType::class)       
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
