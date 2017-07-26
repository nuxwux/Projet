<?php


namespace Louvre\TicketBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Holiday extends Constraint
{
  public $message = "Le musée est fermé ce jour la.";

  public function validatedBy()
  {
    return 'louvre_ticket_holiday'; // Ici, on fait appel à l'alias du service
  }
}
