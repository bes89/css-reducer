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

use CssReducer\Css\Property\Color;


class ColorTest extends \PHPUnit_Framework_TestCase
{
    public function testOverwriting()
    {
        $color1 = new Color('color', 'blue');
        $color2 = new Color('color', 'red');
        $color3 = new Color('color', '#fff');

        $color1->merge($color2);
        $color1->merge($color3);

        $this->assertEquals(array(array(
            'name' => 'color',
            'value' => '#fff',
            'isImportant' => false,
        )), $color1->reduce());
    }

    public function testOverwritingWithImportantValue()
    {
        $color1 = new Color('color', 'blue!important');
        $color2 = new Color('color', 'red');
        $color3 = new Color('color', '#fff');

        $color1->merge($color2);
        $color1->merge($color3);

        $this->assertEquals(array(array(
            'name' => 'color',
            'value' => 'blue',
            'isImportant' => true,
        )), $color1->reduce());
    }

    public function testConvertingHexColorsToShorthand()
    {
        $color = new Color('color', '#ffffff');

        $this->assertEquals(array(array(
            'name' => 'color',
            'value' => '#fff',
            'isImportant' => false,
        )), $color->reduce());
    }

    public function testConvertingLongColorNamesToShorthandHexValue()
    {
        $color = new Color('color', 'white');

        $this->assertEquals(array(array(
            'name' => 'color',
            'value' => '#fff',
            'isImportant' => false,
        )), $color->reduce());
    }

    public function testShortColorNameAreNotConverted()
    {
        $color = new Color('color', 'gold');

        $this->assertEquals(array(array(
            'name' => 'color',
            'value' => 'gold',
            'isImportant' => false,
        )), $color->reduce());
    }

    public function testShorthandWithTransparentAndInheritAsValueAreNotConverted()
    {
        $color = new Color('color', 'transparent');

        $this->assertEquals(array(array(
            'name' => 'color',
            'value' => 'transparent',
            'isImportant' => false,
        )), $color->reduce());


        $color = new Color('color', 'inherit');

        $this->assertEquals(array(array(
            'name' => 'color',
            'value' => 'inherit',
            'isImportant' => false,
        )), $color->reduce());
    }

    public function testIsValidCssColor()
    {
        $this->assertEquals(true, Color::isColor('#fff'));
        $this->assertEquals(true, Color::isColor('#f8f8f8'));
        $this->assertEquals(true, Color::isColor('black'));
        $this->assertEquals(false, Color::isColor('~rtw345 '));
        $this->assertEquals(false, Color::isColor('#_ewfwf'));
    }
}
