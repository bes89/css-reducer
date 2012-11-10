<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CssReducer\Test\Css\Property;

use CssReducer\Css\Property\Margin;


class MarginTest extends \PHPUnit_Framework_TestCase
{

    public function testOneArgIsGiven()
    {
        $p = new Margin('margin', '3px');
        $this->assertEquals(array(
            'name' => 'margin',
            'value' => '3px',
            'isImportant' => false,
        ), $p->reduce());
    }

    public function testTwoArgAreGiven()
    {
        $p = new Margin('margin', '3px 6px');
        $this->assertEquals(array(
            'name' => 'margin',
            'value' => '3px 6px',
            'isImportant' => false,
        ), $p->reduce());
    }

    public function testThreeArgAreGiven()
    {
        $p = new Margin('margin', '3px 6px 2px');
        $this->assertEquals(array(
            'name' => 'margin',
            'value' => '3px 6px 2px',
            'isImportant' => false,
        ), $p->reduce());
    }

    public function testFourArgsAreGiven()
    {
        // without any change...
        $p = new Margin('margin', '15px 5px 19px 2px');
        $this->assertEquals(array(
            'name' => 'margin',
            'value' => '15px 5px 19px 2px',
            'isImportant' => false,
        ), $p->reduce());

        // 4 args are equal so we can use simple shorthand
        $p = new Margin('margin', '1px 1px 1px 1px');
        $this->assertEquals(array(
            'name' => 'margin',
            'value' => '1px',
            'isImportant' => false,
        ), $p->reduce());

        // the last (left) = the second (right) so we can remove the last
        // because:
        //   3 args =>
        //        1. top
        //        2. right & left
        //        3. bottom
        //   4 args =>
        //        1. top
        //        2. right
        //        3. bottom
        //        4. left
        $p = new Margin('margin', '1px 5px 3px 5px');
        $this->assertEquals(array(
            'name' => 'margin',
            'value' => '1px 5px 3px',
            'isImportant' => false,
        ), $p->reduce());

        // margin-top -left -bottom and -right to shorthand
        $p = new Margin('margin-top', '2px');
        $this->assertEquals(array(
            'name' => 'margin-top',
            'value' => '2px',
            'isImportant' => false,
        ), $p->reduce());

        $p = new Margin('margin-right', '7px');
        $this->assertEquals(array(
            'name' => 'margin-right',
            'value' => '7px',
            'isImportant' => false,
        ), $p->reduce());

        $p = new Margin('margin-bottom', '4px');
        $this->assertEquals(array(
            'name' => 'margin-bottom',
            'value' => '4px',
            'isImportant' => false,
        ), $p->reduce());

        $p = new Margin('margin-left', '3px');
        $this->assertEquals(array(
            'name' => 'margin-left',
            'value' => '3px',
            'isImportant' => false,
        ), $p->reduce());
    }

    public function testWithInheritance()
    {
        $p = new Margin();

        $p->parse('margin', '2px');
        $p->parse('margin-top', '5px');
        $p->parse('margin-top', '9px');

        $p->parse('margin-bottom', '3px');

        $this->assertEquals(array(
        'name' => 'margin',
        'value' => '9px 2px 3px',
        'isImportant' => false,
        ), $p->reduce());
    }

    public function testWithInheritanceOverride()
    {
        $p = new Margin();

        $p->parse('margin-top', '5px');
        $p->parse('margin-top', '9px');
        $p->parse('margin-bottom', '3px');
        $p->parse('margin', '2px');

        $this->assertEquals(array(
            'name' => 'margin',
            'value' => '2px',
            'isImportant' => false,
        ), $p->reduce());
    }

    public function testConvert0pxTo0()
    {
        $p = new Margin('margin', '0px');
        $this->assertEquals(array(
            'name' => 'margin',
            'value' => '0',
            'isImportant' => false,
        ), $p->reduce());
    }

    public function testConvert0000To0()
    {
        $p = new Margin('margin', '0 0 0 0');
        $this->assertEquals(array(
            'name' => 'margin',
            'value' => '0',
            'isImportant' => false,
        ), $p->reduce());
    }

}