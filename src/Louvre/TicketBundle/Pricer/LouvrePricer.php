<?php

namespace Louvre\TicketBundle\Pricer;

use Doctrine\ORM\EntityManagerInterface;

class LouvrePricer
{
  public function ticketPricer($birthdate, $ticketype, $reduction) {

    $today = new \DateTime();
    $interval  = $today->diff($birthdate);
    

    if ($reduction) {
       $price = 10;

    } else if ($interval->y < "4") {
      $price = 0;

    } else if ($interval->y >= "4" && $interval->y < "12"){
     
        $price = 8;
      

    } else if ($interval->y >= "12" && $interval->y < "60") {
      
        $price = 16;
      
    } else if ($interval->y >= "60") {
      
        $price = 12;
         
    }

    if ($ticketype == "Demi-journée") {
      $price/= 2;
    }
    
    return $price;
    
  }

}
