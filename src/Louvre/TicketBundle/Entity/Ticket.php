<?php

namespace Louvre\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Louvre\TicketBundle\Validator\TicketLimit;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="Louvre\TicketBundle\Repository\TicketRepository")
 */
class Ticket
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Nom trop court, minimum 2 lettres")
     * @Assert\Type("string", message="Vous n'avez pas rentré un nom")
     *
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Prénom trop court, minimum 2 lettres")
     * @Assert\Type("string", message="Vous n'avez pas rentré un nom")
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string", message="Vous n'avez pas rentré un pays")
     */
    private $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime")
     * @Assert\DateTime()
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    /**
     * @ORM\Column(name="reduction" ,type="boolean")
     */
    private $reduction;
    /**
     * @ORM\ManyToOne(targetEntity="Louvre\TicketBundle\Entity\GlobalTicket", inversedBy="tickets")
     * @ORM\JoinColumn()
     */
    private $globalticket;

    public function __construct()
    {
     
        $this->price ="0";
        $this->type = "Normal";
              
        
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
     * Set name
     *
     * @param string $name
     *
     * @return Ticket
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Ticket
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Ticket
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Ticket
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        
        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

   

    /**
     * Set globalticket
     *
     * @param GlobalTicket $globalticket
     *
     * @return Ticket
     */
    public function setGlobalticket(GlobalTicket $globalticket = null)
    {
        $this->globalticket = $globalticket;

        return $this;
    }

    /**
     * Get globalticket
     *
     * @return GlobalTicket
     */
    public function getGlobalticket()
    {
        return $this->globalticket;
    }

    /**
     * Set reduction
     *
     * @param boolean $reduction
     *
     * @return Ticket
     */
    public function setReduction($reduction)
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * Get reduction
     *
     * @return boolean
     */
    public function getReduction()
    {
        return $this->reduction;
    }



    /**
     * Set type
     *
     * @param string $type
     *
     * @return Ticket
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
