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
     * @return array
     */
    public function reduce()
    {
        $reduced = array();
        $expandedInputs = array();

        foreach ($this->inputs as $input) {
            if ($input['name'] == 'font') {
                foreach ($this->expandValues($input) as $expandedInput) {
                    $expandedInputs[] = $expandedInput;
                }
            } else {
                $expandedInputs[] = $input;
            }
        }

        $groups = array();

        foreach ($expandedInputs as $expandedInput) {
            list(, $subproperty) = explode('-', $expandedInput['name']);

            $groups[$subproperty][] = $expandedInput;
        }

        foreach ($groups as $subproperty => $inputs) {
            $groups[$subproperty] = $this->override($inputs);

            // shorten dimension
            if (in_array($subproperty, array('size', 'height')))
            {
                $groups[$subproperty]['value'] = $this->shortDimension(
                    $groups[$subproperty]['value']
                );
            }

            if ($subproperty == 'weight')
            {
                $groups[$subproperty]['value'] = $this->shortFontWeight(
                    $groups[$subproperty]['value']
                );
            }
        }

        // size and family is required to reduce the input to shorthand
        if (array_key_exists('size', $groups) && array_key_exists('family', $groups))
        {
            $value = "";

            $order = array('style', 'variant', 'weight', 'size', 'height', 'family');

            foreach ($order as $key)
            {
                if (array_key_exists($key, $groups))
                {
                    if ($value != "")
                    {
                        if (array_key_exists('size', $groups) && array_key_exists('height', $groups)
                        && $key == 'height')
                        {
                            $value .= "/";
                        }
                        else
                        {
                            $value .= " ";
                        }
                    }

                    $value .= $groups[$key]['value'];
                }
            }

            $reduced = array(array(
                'name' => 'font',
                'value' => $value,
                'important' => false,
            ));
        }
        else
        {
            $reduced = array_values($groups);
        }

        return $reduced;
    }


    /**
     * @param $input
     * @return array
     */
    protected function expandValues($input)
    {
        if ($input['name'] == 'font' && $input['value'] == 'inherit') {
            $input['value'] = str_repeat($input['value'], 6);
        }

        $regex = sprintf('~%s$~i', implode('\s*', array(
            'style' => '(inherit|normal|italic|oblique)?',
            'variant' => '(inherit|normal|small-caps)?',
            'weight' => '(inherit|normal|bold(?:er)?|lighter|[1-9]00)?',
            'size' => '(\d+(?:%|px|em|pt)?|(?:x(?:x)?-)?(?:small|large)r?|medium|inherit)',
            'height' => '\/?\s?(\d+(?:%|px|em|pt)?|normal|inherit)?',
            'family' => '(inherit|default|.+)',
        )));

        $expandedInputs = array();
        $matches = array();
        $isImportant = $input['important'];

        if (preg_match($regex, $input['value'], $matches))
        {
            list(, $style, $variant, $weight, $size, $lineHeight, $family) = $matches;

            // font-style is optional
            if ($style)
            {
                $expandedInputs[] = array(
                    'name' => 'font-style',
                    'value' => $style,
                    'important' => $isImportant,
                );
            }

            // font-variant is optional
            if ($variant)
            {
                $expandedInputs[] = array(
                    'name' => 'font-variant',
                    'value' => $variant,
                    'important' => $isImportant,
                );
            }

            // font-weight is optional
            if ($weight)
            {
                $expandedInputs[] = array(
                    'name' => 'font-weight',
                    'value' => $weight,
                    'important' => $isImportant,
                );
            }

            // font-size is required
            $expandedInputs[] = array(
                'name' => 'font-size',
                'value' => $size,
                'important' => $isImportant,
            );

            // line-height is optional
            if ($lineHeight)
            {
                $expandedInputs[] = array(
                    'name' => 'line-height',
                    'value' => $lineHeight,
                    'important' => $isImportant,
                );
            }

            // font-family is required
            $expandedInputs[] = array(
                'name' => 'font-family',
                'value' => $family,
                'important' => $isImportant,
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

    /**
     *
     * @param string $value
     * @return string
     */
    protected function shortFontWeight($value)
    {
        switch ($value)
        {
            case 'bold': $value = '700';
                break;
            case 'normal': $value = '400';
                break;
            default: break;
        }

        return $value;
    }
}
