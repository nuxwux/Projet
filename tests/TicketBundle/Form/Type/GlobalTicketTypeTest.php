<?php
/**
 * Created by PhpStorm.
 * User: Nux
 * Date: 28/09/2017
 * Time: 17:01
 */
namespace Tests\TicketBundle\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Louvre\TicketBundle\Form\Type\GlobalTicketType;

class GlobalTicketTypeTest extends TypeTestCase
{
    public function testSubmitData()
    {
        $formData = array(
            'datevisit' => '29/10/2017',
            'ticketype' => 'Journée',
            'mail' => 'testmail@gmail.com'
        );

        $form = $this->factory->create(GlobalTicketType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key ) {
            $this->assertArrayHasKey($key, $children);
        }

    }
}