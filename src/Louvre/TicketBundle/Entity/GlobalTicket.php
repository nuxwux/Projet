<?php

namespace Louvre\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Louvre\TicketBundle\Validator\TicketLimit;
use Louvre\TicketBundle\Validator\Holiday;
use Louvre\TicketBundle\Entity\Ticket;


use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * GlobalTicket
 *
 * @ORM\Table(name="global_ticket")
 * @ORM\Entity(repositoryClass="Louvre\TicketBundle\Repository\GlobalTicketRepository")
 */
class GlobalTicket
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreation", type="datetime")
     */
    private $datecreation;
    /**
     * @var \Datetime
     *
     * @ORM\Column(name="datevisit", type="date")
     * @TicketLimit()
     * @Holiday()
     */
    private $datevisit;
    /**
     * @var string
     *
     * @ORM\Column(name="ticketype", type="string", length=255)
     */
    private $ticketype;
    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     * @Assert\Email(message="Vous n'avez pas rentrez une adresse email valide")
     */
    private $mail;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    /**
     * @ORM\OneToMany(targetEntity="Louvre\TicketBundle\Entity\Ticket", mappedBy="globalticket",cascade={"persist"})
     * @Assert\Valid()
     */
    private $tickets;

    /**
     * @ORM\Column(name="totalprice", type="integer")
     */
    private $totalprice;

    /**
     * @ORM\Column(name="paid", type="boolean")
     */
    private $paid = false;

    /**
     * @ORM\Column(name="md5", type="string", length=255)
     */
    private $md5;


    public function __construct()
    {
        $this->datecreation = new \Datetime();
        $this->name = "Visite du Louvre";
        $this->tickets = new ArrayCollection();
        $this->totalprice = 0;
        $this->md5 = "0";
     
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set datecreation
     *
     * @param \DateTime $datecreation
     *
     * @return GlobalTicket
     */
    public function setDatecreation($datecreation)
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    /**
     * Get datecreation
     *
     * @return \DateTime
     */
    public function getDatecreation()
    {
        return $this->datecreation;
    }

    /**
     * Set datevisit
     *
     * @param \DateTime $datevisit
     *
     * @return GlobalTicket
     */
    public function setDatevisit(\DateTime $datevisit)
    {
        $this->datevisit = $datevisit;

        return $this;
    }

    /**
     * Get datevisit
     *
     * @return \DateTime
     */
    public function getDatevisit()
    {
        return $this->datevisit;
    }

    /**
     * Set ticketype
     *
     * @param string $ticketype
     *
     * @return GlobalTicket
     */
    public function setTicketype($ticketype)
    {
        $this->ticketype = $ticketype;

        return $this;
    }

    /**
     * Get ticketype
     *
     * @return string
     */
    public function getTicketype()
    {
        return $this->ticketype;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return GlobalTicket
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return GlobalTicket
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add ticket
     *
     * @param \Louvre\TicketBundle\Entity\Ticket $ticket
     *
     * @return GlobalTicket
     */
    public function addTicket(\Louvre\TicketBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        $ticket->setGlobalticket($this);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \Louvre\TicketBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\Louvre\TicketBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }


  /**
   * @Assert\Callback
   */
   public function validateTuesdaySunday(ExecutionContextInterface $context)
  {
      
       $dateVisit = $this->getDatevisit()->format("w");
       
       if ($dateVisit === "0" || $dateVisit === "2") {

       $context
         ->buildViolation('Le musée est fermé le mardi et le dimanche.') 
         ->atPath('datevisit')                                                 
         ->addViolation() 
         ;
     }
  }

 /**
   * @Assert\Callback
   */
   public function validatePreviousDays(ExecutionContextInterface $context)
  {
      
       $dateVisit = $this->getDatevisit();
       $today = new \DateTime();
       $interval  = $today->diff($dateVisit);


       if (($interval->invert == "1") && ($interval->invert == "1" && $interval->days != "0")) {

       $context
         ->buildViolation('Vous ne pouvez pas réservez pour un jour passé.')
         ->atPath('datevisit')                                                   
         ->addViolation() 
       ;
     }
 }

 /**
   * @Assert\Callback
   */
   public function validateTicketype(ExecutionContextInterface $context)
  {
      
       $ticketype = $this->getTicketype();
       $dateVisit = $this->getDatevisit();
       $today = new \DateTime();
       $interval  = $today->diff($dateVisit);
      


       if ($ticketype == "Journée" && $today->format("H") >= "14" 
           && ($interval->invert == "1" && $interval->days == "0")) {

       $context
         ->buildViolation('Vous ne pouvez pas prendre une billet journée après 14h') 
         ->atPath('ticketype')                                                  
         ->addViolation()
       ;
     }
  }

   /**
   * @Assert\Callback
   */
   public function validateBirthdate(ExecutionContextInterface $context)
  {


       $var = $this->tickets;
       $var2 = $var->toArray();
       $today = new \DateTime();
       $child = false;
       $adult = false;


       foreach($var2 as $value) {
        $birthdate = $value->getBirthdate();
        $interval = $today->diff($birthdate)->y;

        if ($interval >= "4") {
            $adult = true;

        } else {
            $child = true;
        }
       }

       if ($adult && $child) {

       } else if($child) {
         $context
         ->buildViolation('Un enfant de moins de 4ans ne peut pas acheter un billet seul.')
         ->atPath('tickets')
         ->addViolation()
       ;
       }

  }

    /**
     * Set totalprice
     *
     * @param integer $totalprice
     *
     * @return GlobalTicket
     */
    public function setTotalprice($totalprice)
    {
        $this->totalprice = $totalprice;

        return $this;
    }

    /**
     * Get totalprice
     *
     * @return integer
     */
    public function getTotalprice()
    {
        return $this->totalprice;
    }

    /**
     * Set paid
     *
     * @param boolean $paid
     *
     * @return GlobalTicket
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid
     *
     * @return boolean
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Set md5
     *
     * @param string $md5
     *
     * @return GlobalTicket
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;

        return $this;
    }

    /**
     * Get md5
     *
     * @return string
     */
    public function getMd5()
    {
        return $this->md5;
    }
}
