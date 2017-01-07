<?php


namespace Louvre\TicketBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Louvre\TicketBundle\Entity\GlobalTicket;

class HolidayValidator extends ConstraintValidator
{


  private $requestStack;
  private $em;
  private $holidays;

  // Les arguments déclarés dans la définition du service arrivent au constructeur
  // On doit les enregistrer dans l'objet pour pouvoir s'en resservir dans la méthode validate()
  public function __construct(RequestStack $requestStack, EntityManagerInterface $em, $holidays)
  {
    $this->requestStack = $requestStack;
    $this->em           = $em;
    $this->holidays     = $holidays;
  }

  public function validate( $value, Constraint $constraint)
  {
    $request = $this->requestStack->getCurrentRequest();

    $dateVisit = $value;
    $badDates = $this->holidays;

      

    foreach ($badDates as $badDate) {
      $badDate = new \DateTime($badDate. "-2017");
      if ($dateVisit->format("dd-mm") === $badDate->format("dd-mm")) {  
        $this->context
          ->buildViolation('Le musée est fermé ce jour la.') 
          ->atPath('datevisit')                                                    
          ->addViolation() 
          ;
      }
    }
  }
   
}
