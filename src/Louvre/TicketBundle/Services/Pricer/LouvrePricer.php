<?php

namespace Louvre\TicketBundle\Services\Pricer;

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

    public function ticketTyper($price, $ticketype){
      if (($price == 8 &&  $ticketype == "Journée") || $price == 4){
          $type = "Enfant";
      }else if (($price == 8 && $ticketype == "Demi-journée") || $price == 16){
          $type = "Normal";
      } else if ($price == 12 || $price == 6){
          $type = "Senior";
      }else if ($price == 10 || $price == 5){
          $type = "Réduit";
      }else if ($price == 0){
          $type = "Gratuit";
      }
      return $type;
    }



}
