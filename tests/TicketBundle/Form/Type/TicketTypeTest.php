<?php
/**
 * Created by PhpStorm.
 * User: Nux
 * Date: 29/09/2017
 * Time: 11:08
 */
namespace Tests\TicketBundle\Form\Type;

use Louvre\TicketBundle\Entity\Ticket;
use Louvre\TicketBundle\Form\Type\TicketType;
use Symfony\Component\Form\Test\TypeTestCase;

class TicketTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'name' => 'mon-nom',
            'firstname' => 'mon-prenom',
            'country' => 'mon-pays',
            'birthdate' => array( 'year' => 1991, 'month' => 03, 'day' => 06)

        );

        $form = $this->factory->create(TicketType::class);

        $form->submit($formData);


          $date = new \DateTime('1991/03/06');



        $ticket = new Ticket();
        $ticket
            ->setReduction(false)
            ->setName('mon-nom')
            ->setFirstname('mon-prenom')
            ->setBirthdate(new \Datetime('1991/03/06'))
            ->setCountry('mon-pays');


        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($ticket, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key){
            $this->assertArrayHasKey($key,$children);
        }
    }
}