<?php

namespace Louvre\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var \Date
     *
     * @ORM\Column(name="datevisit", type="date")
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
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

   /**
   * @ORM\OneToMany(targetEntity="Louvre\TicketBundle\Entity\Ticket", mappedBy="global_ticket",cascade={"persist"})
   
   */
    private $tickets;


    public function __construct()
    {
        $this->datecreation = new \Datetime();
        $this->name = "Visite de Musée";
        $this->tickets = new ArrayCollection();
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
    public function setDatevisit($datevisit)
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
}
