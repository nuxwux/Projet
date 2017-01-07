<?php
// src/OC/PlatformBundle/Validator/AntifloodValidator.php

namespace Louvre\TicketBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class TicketLimitValidator extends ConstraintValidator
{


  private $requestStack;
  private $em;

  // Les arguments déclarés dans la définition du service arrivent au constructeur
  // On doit les enregistrer dans l'objet pour pouvoir s'en resservir dans la méthode validate()
  public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
  {
    $this->requestStack = $requestStack;
    $this->em           = $em;
  }

  public function validate($value, Constraint $constraint)
  {
    
    $request = $this->requestStack->getCurrentRequest();

    $numberOfTicket = $this->em
      ->getRepository('LouvreTicketBundle:Ticket')
      ->getTicketsAtDate($value)
      ;
    $numberOfTicket = intval($numberOfTicket["nb"]);
      // dump($numberOfTicket);
      // die();

      if ($numberOfTicket > 1000) {
        $this->context
         ->buildViolation('Trop de tickets vendus') 
         ->atPath('ticketype')                                                
         ->addViolation()    
       ;
      }
    }
   

    
}
