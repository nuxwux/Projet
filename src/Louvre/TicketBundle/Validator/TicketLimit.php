<?php


namespace Louvre\TicketBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TicketLimit extends Constraint
{
  public $message = "1000 tickets ont été reservé aujourd'hui.";

  public function validatedBy()
  {
    return 'louvre_ticket_ticketlimit'; // Ici, on fait appel à l'alias du service
  }
}
