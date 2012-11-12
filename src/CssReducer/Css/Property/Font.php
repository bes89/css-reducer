<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CssReducer\Css\Property;


class Font extends Property
{
    /**
     * @param $input
     * @return array
     */
    protected function expandValues($input)
    {
        $regex = sprintf('~%s$~i', implode('\s*', array(
            'style' => '(inherit|normal|italic|oblique)?',
            'variant' => '(inherit|normal|small-caps)?',
            'weight' => '(inherit|normal|bold(?:er)?|lighter|[1-9]00)?',
            'size' => '(\d+(?:%|px|em|pt)?|(?:x(?:x)?-)?(?:small|large)r?|medium|inherit)',
            'height' => '\/?\s*(\d+(?:%|px|em|pt)?|normal|inherit)?',
            'family' => '(inherit|default|.+)',
        )));

        $expandedInputs = array();
        $matches = array();
        $isImportant = $input['isImportant'];

        if (preg_match($regex, $input['value'], $matches))
        {
            list(, $style, $variant, $weight, $size, $lineHeight, $family) = $matches;

            // font-style is optional
            if ($style)
            {
                $expandedInputs[] = array(
                    'name' => 'font-style',
                    'value' => $style,
                    'isImportant' => $isImportant,
                );
            }

            // font-variant is optional
            if ($variant)
            {
                $expandedInputs[] = array(
                    'name' => 'font-variant',
                    'value' => $variant,
                    'isImportant' => $isImportant,
                );
            }

            // font-weight is optional
            if ($weight)
            {
                $expandedInputs[] = array(
                    'name' => 'font-weight',
                    'value' => $weight,
                    'isImportant' => $isImportant,
                );
            }

            // font-size is required
            $expandedInputs[] = array(
                'name' => 'font-size',
                'value' => $size,
                'isImportant' => $isImportant,
            );

            // line-height is optional
            if ($lineHeight)
            {
                $expandedInputs[] = array(
                    'name' => 'line-height',
                    'value' => $lineHeight,
                    'isImportant' => $isImportant,
                );
            }

            // font-family is required
            $expandedInputs[] = array(
                'name' => 'font-family',
                'value' => $family,
                'isImportant' => $isImportant,
            );

        }
        else
        {
            // We could not parse the shorthand value but it's okay our css reducer is non destructive so we return
            // the input back as result
            $expandedInputs[] = $input;
        }

        return $expandedInputs;
    }
}
