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

use CssReducer\Css\Property\Padding;


class PaddingTest extends \PHPUnit_Framework_TestCase
{
    public function testOneArgIsGiven()
    {
        $p = new Padding('padding', '3px');
        $this->assertEquals(array(array(
            'name' => 'padding',
            'value' => '3px',
            'important' => false,
        )), $p->reduce());
    }

    public function testTwoArgAreGiven()
    {
        $p = new Padding('padding', '3px 6px');
        $this->assertEquals(array(array(
            'name' => 'padding',
            'value' => '3px 6px',
            'important' => false,
        )), $p->reduce());
    }

    public function testThreeArgAreGiven()
    {
        $p = new Padding('padding', '3px 6px 2px');
        $this->assertEquals(array(array(
            'name' => 'padding',
            'value' => '3px 6px 2px',
            'important' => false,
        )), $p->reduce());
    }

    public function testFourArgsAreGiven()
    {
        // without any change...
        $p = new Padding('padding', '15px 5px 19px 2px');
        $this->assertEquals(array(array(
            'name' => 'padding',
            'value' => '15px 5px 19px 2px',
            'important' => false,
        )), $p->reduce());

        // 4 args are equal so we can use simple shorthand
        $p = new Padding('padding', '1px 1px 1px 1px');
        $this->assertEquals(array(array(
            'name' => 'padding',
            'value' => '1px',
            'important' => false,
        )), $p->reduce());

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
        $p = new Padding('padding', '1px 5px 3px 5px');
        $this->assertEquals(array(array(
            'name' => 'padding',
            'value' => '1px 5px 3px',
            'important' => false,
        )), $p->reduce());

        // padding-top -left -bottom and -right to shorthand
        $p = new Padding('padding-top', '2px');
        $this->assertEquals(array(array(
            'name' => 'padding-top',
            'value' => '2px',
            'important' => false,
        )), $p->reduce());

        $p = new Padding('padding-right', '7px');
        $this->assertEquals(array(array(
            'name' => 'padding-right',
            'value' => '7px',
            'important' => false,
        )), $p->reduce());

        $p = new Padding('padding-bottom', '4px');
        $this->assertEquals(array(array(
            'name' => 'padding-bottom',
            'value' => '4px',
            'important' => false,
        )), $p->reduce());

        $p = new Padding('padding-left', '3px');
        $this->assertEquals(array(array(
            'name' => 'padding-left',
            'value' => '3px',
            'important' => false,
        )), $p->reduce());
    }

    public function testWithInheritance()
    {
        $p = new Padding('padding', '2px');

        $p->parse('padding-top', '5px');
        $p->parse('padding-top', '9px');

        $p->parse('padding-bottom', '3px');

        $this->assertEquals(array(array(
            'name' => 'padding',
            'value' => '9px 2px 3px',
            'important' => false,
        )), $p->reduce());
    }

    public function testWithInheritanceOverride()
    {
        $p = new Padding('padding-top', '5px');

        $p->parse('padding-top', '9px');
        $p->parse('padding-bottom', '3px');
        $p->parse('padding', '2px');

        $this->assertEquals(array(array(
            'name' => 'padding',
            'value' => '2px',
            'important' => false,
        )), $p->reduce());
    }

    public function testConvert0pxTo0()
    {
        $p = new Padding('padding', '0px');
        $this->assertEquals(array(array(
            'name' => 'padding',
            'value' => '0',
            'important' => false,
        )), $p->reduce());

        $p = new Padding('padding-left', '0px');
        $this->assertEquals(array(array(
            'name' => 'padding-left',
            'value' => '0',
            'important' => false,
        )), $p->reduce());
    }

    public function testConvert0000To0()
    {
        $p = new Padding('padding', '0 0 0 0');
        $this->assertEquals(array(array(
            'name' => 'padding',
            'value' => '0',
            'important' => false,
        )), $p->reduce());
    }

}