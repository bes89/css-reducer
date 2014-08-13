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

use CssReducer\Css\Property\Font;


class FontTest extends \PHPUnit_Framework_TestCase
{

    public function testExtractValuesFromShorthand()
    {
         $steps = array(
             1 => array(
                 'style' => 'italic',
                 'variant' => 'small-caps',
                 'weight' => 'bolder',
                 'size' => '36px',
                 'lineHeight' => '2em',
                 'family' => 'Arial, Verdana, "Times New Roman"',
             ),
             2 => array(
                 'style' => '',
                 'variant' => 'small-caps',
                 'weight' => 'bolder',
                 'size' => '36px',
                 'lineHeight' => '2em',
                 'family' => 'Arial, Verdana, "Times New Roman"',
             ),
             3 => array(
                 'style' => 'italic',
                 'variant' => '',
                 'weight' => 'bolder',
                 'size' => '36px',
                 'lineHeight' => '2em',
                 'family' => 'Arial, Verdana, "Times New Roman"',
             ),
             4 => array(
                 'style' => 'italic',
                 'variant' => 'small-caps',
                 'weight' => '',
                 'size' => '36px',
                 'lineHeight' => '2em',
                 'family' => 'Arial, Verdana, "Times New Roman"',
             ),
             5 => array(
                 'style' => 'italic',
                 'variant' => 'small-caps',
                 'weight' => 'bolder',
                 'size' => '36px',
                 'lineHeight' => '',
                 'family' => 'Arial, Verdana, "Times New Roman"',
             ),
             6 => array(
                 'style' => '',
                 'variant' => '',
                 'weight' => 'bolder',
                 'size' => '36px',
                 'lineHeight' => '',
                 'family' => 'Arial, Verdana, "Times New Roman"',
             ),
             7 => array(
                 'style' => '',
                 'variant' => '',
                 'weight' => '',
                 'size' => '36px',
                 'lineHeight' => '2em',
                 'family' => 'Arial, Verdana, "Times New Roman"',
             ),
             8 => array(
                 'style' => '',
                 'variant' => '',
                 'weight' => '',
                 'size' => '36px',
                 'lineHeight' => '',
                 'family' => 'Arial, Verdana, "Times New Roman"',
             ),
             9 => array(
                 'style' => 'italic',
                 'variant' => '',
                 'weight' => '',
                 'size' => '36px',
                 'lineHeight' => '2em',
                 'family' => 'Arial, Verdana, "Times New Roman"',
             ),
             10 => array(
                 'style' => 'italic',
                 'variant' => '',
                 'weight' => '',
                 'size' => '36px',
                 'lineHeight' => '',
                 'family' => 'Arial, Verdana, "Times New Roman"',
             ),
             11 => array(
                 'style' => 'italic',
                 'variant' => 'small-caps',
                 'weight' => '',
                 'size' => '36px',
                 'lineHeight' => '',
                 'family' => 'Arial, Verdana, "Times New Roman"',
             ),
         );

        foreach ($steps as $givenInput)
        {
            $this->extractValuesFromShorthand($givenInput);
        }
    }

    protected function extractValuesFromShorthand($givenInput)
    {
        $style = '';
        $variant = '';
        $weight = '';
        $size = '';
        $lineHeight = '';
        $family = '';

        extract($givenInput);

        $value = sprintf('%s%s%s%s%s%s',
            $style != '' ? ' '.$style : '',
            $variant != '' ? ' '.$variant : '',
            $weight != '' ? ' '.$weight : '',
            ' '.$size,
            $lineHeight != '' ? '/'.$lineHeight : '',
            ' '.$family
        );

        $input = array(
            'name' => 'font',
            'value' => $value,
            'important' => false
        );

        $f = new Font();
        $method = new \ReflectionMethod(get_class($f), 'expandValues');
        $method->setAccessible(true);

        $expandedInputs = $method->invoke($f, $input);

        $expectedInputs = array();

        if ($style)
        {
            $expectedInputs[] = array(
                'name' => 'font-style',
                'value' => $style,
                'important' => false,
            );
        }

        if ($variant)
        {
            $expectedInputs[] = array(
                'name' => 'font-variant',
                'value' => $variant,
                'important' => false,
            );
        }

        if ($weight)
        {
            $expectedInputs[] = array(
                'name' => 'font-weight',
                'value' => $weight,
                'important' => false,
            );
        }

        $expectedInputs[] = array(
            'name' => 'font-size',
            'value' => $size,
            'important' => false,
        );


        if ($lineHeight)
        {
            $expectedInputs[] = array(
                'name' => 'line-height',
                'value' => $lineHeight,
                'important' => false,
            );
        }

        $expectedInputs[] = array(
            'name' => 'font-family',
            'value' => $family,
            'important' => false,
        );

        foreach ($expectedInputs as $index => $expectedInput)
        {
            $this->assertEquals($expectedInput, $expandedInputs[$index]);
        }
    }
}
