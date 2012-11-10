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


class Margin extends Property
{
    /**
     * @throws \InvalidArgumentException
     * @return array
     */
    public function reduce()
    {
        $expandedInputs = array();

        foreach ($this->inputs as $input) {
            if ($input['name'] == 'margin') {
                foreach ($this->expandValues($input) as $expandedInput) {
                    $expandedInputs[] = $expandedInput;
                }
            } else {
                $expandedInputs[] = $input;
            }
        }

        $groupedByPosition = array();

        foreach ($expandedInputs as $expandedInput) {
            list(, $position) = explode('-', $expandedInput['name']);

            $groupedByPosition[$position][] = $expandedInput;
        }

        foreach ($groupedByPosition as $position => $inputs) {
            $groupedByPosition[$position] = $this->override($inputs);

            // shorten dimension
            $groupedByPosition[$position]['value'] = $this->shortDimension(
                $groupedByPosition[$position]['value']
            );
        }

        switch (count($groupedByPosition)) {
            case 1 :

                $reduced = array_values($groupedByPosition);

                break;

            case 2 :

                $reduced = array_values($groupedByPosition);

                break;

            case 3 :

                $reduced = array_values($groupedByPosition);

                break;

            case 4 :

                if (
                    $groupedByPosition['top']['value'] == $groupedByPosition['bottom']['value'] &&
                    $groupedByPosition['left']['value'] == $groupedByPosition['right']['value'] &&
                    $groupedByPosition['top']['value'] == $groupedByPosition['left']['value']
                ) {
                    // all of the sides have the same measurements

                    $reduced = $groupedByPosition['top'];
                    $reduced['name'] = 'margin';
                    $reduced = array($reduced);

                } elseif (
                    $groupedByPosition['top']['value'] == $groupedByPosition['bottom']['value'] &&
                    $groupedByPosition['left']['value'] == $groupedByPosition['right']['value']
                ) {

                    // the left and right and the top and bottom sides have the same measurements

                    $reduced = array(
                        array(
                            'name' => 'margin',
                            'value' => sprintf('%s %s',
                                $groupedByPosition['top']['value'],
                                $groupedByPosition['left']['value']
                            ),
                            'isImportant' => false // FIXME: many inputs are merged so when we should set this flag? for
                            // instance left is import but not the rest, when we set important to true then this will
                            // be applied to all (top, left, right, bottom)
                        )
                    );

                } elseif ($groupedByPosition['left']['value'] == $groupedByPosition['right']['value']) {

                    // the left and right side have the same measurements

                    $reduced = array(
                        array(
                            'name' => 'margin',
                            'value' => sprintf('%s %s %s',
                                $groupedByPosition['top']['value'],
                                $groupedByPosition['left']['value'],
                                $groupedByPosition['bottom']['value']
                            ),
                            'isImportant' => false // FIXME: many inputs are merged so when we should set this flag? for
                            // instance left is import but not the rest, when we set important to true then this will
                            // be applied to all (top, left, right, bottom)
                        )
                    );

                } else {

                    // the sides have different measurements

                    $reduced = array(
                        array(
                            'name' => 'margin',
                            'value' => sprintf('%s %s %s %s',
                                $groupedByPosition['top']['value'],
                                $groupedByPosition['right']['value'],
                                $groupedByPosition['bottom']['value'],
                                $groupedByPosition['left']['value']

                            ),
                            'isImportant' => false // FIXME: many inputs are merged so when we should set this flag? for
                            // instance left is import but not the rest, when we set important to true then this will
                            // be applied to all (top, left, right, bottom)

                        )
                    );

                }

                break;

            default:
                throw new \InvalidArgumentException;

        }

        return $reduced;
    }

    /**
     * @param $input
     * @throws \InvalidArgumentException
     * @return array
     */
    protected function expandValues($input)
    {
        $expandedInputs = array();
        $isImportant = $input['isImportant'];

        $values = explode(' ', $input['value']);

        foreach ($values as $index => $value) {
            $values[$index] = trim($value);
        }

        switch (count($values)) {
            case 1 :
                $case = array(
                    'top' => 1,
                    'bottom' => 1,
                    'left' => 1,
                    'right' => 1,
                );
                break;

            case 2 :
                $case = array(
                    'top' => 1,
                    'bottom' => 1,
                    'left' => 2,
                    'right' => 2,
                );
                break;

            case 3 :
                $case = array(
                    'top' => 1,
                    'bottom' => 3,
                    'left' => 2,
                    'right' => 2,
                );
                break;

            case 4 :
                $case = array(
                    'top' => 1,
                    'bottom' => 3,
                    'left' => 4,
                    'right' => 2,
                );
                break;

            default:

                throw new \InvalidArgumentException;

        }

        foreach ($case as $position => $valueIndex) {

            $expandedInputs[] = array(
                'name' => 'margin-' . $position,
                'value' => $values[$valueIndex - 1],
                'isImportant' => $isImportant,
            );
        }

        return $expandedInputs;
    }
}
