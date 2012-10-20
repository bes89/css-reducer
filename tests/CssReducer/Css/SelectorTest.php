<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CssReducer\Test\Css;

use CssReducer\Css\Selector;
use CssReducer\Css\Property\Property;
use CssReducer\Css\Property\Color;


class SelectorTest extends \PHPUnit_Framework_TestCase
{
    public function testSetAndGetProperty()
    {
        $property = new Property();

        $selector = new Selector();
        $selector->setName('body');
        $selector->addProperty($property);

        $this->assertSame($property, reset($selector->getProperties()));
    }

    public function testBasePropertiesAreNotMerged()
    {
        $property1 = new Property();
        $property2 = new Property();

        $selector = new Selector();
        $selector->setName('body');
        $selector->addProperty($property1);
        $selector->addProperty($property2);

        $this->assertSame(2, count($selector->getProperties()));
    }

    public function testNoBasePropertiesOfDifferentTypesAreNotMerged()
    {
        $property1 = new Property();
        $property2 = new Color();

        $selector = new Selector();
        $selector->setName('body');
        $selector->addProperty($property1);
        $selector->addProperty($property2);

        $this->assertSame(2, count($selector->getProperties()));
    }

    public function testNoBasePropertiesOfSameTypeAreMerged()
    {
        $property1 = new Color();
        $property2 = new Color();

        $selector = new Selector();
        $selector->setName('body');
        $selector->addProperty($property1);
        $selector->addProperty($property2);

        $this->assertSame(1, count($selector->getProperties()));
    }
}
