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


class Property
{
    /**
     * @var array
     */
    protected $inputs = array();

    /**
     * @param null|string $name
     * @param null|string $value
     */
    public function __construct($name = null, $value = null)
    {
        if ($name !== null && $value !== null)
        {
            $this->parse($name, $value);
        }
    }

    /**
     * @param $name
     * @param $value
     */
    public function parse($name, $value)
    {
        $isImportant = false;

        if (strpos($value, '!important') !== false)
        {
            $value = str_replace('!important', '', $value);
            $isImportant = true;
        }

        $this->inputs[] = array(
            'name' => $name,
            'value' => $value,
            'is_important' => $isImportant,
        );
    }

    /**
     * @param Property $newProperty
     */
    public function merge(Property $newProperty)
    {
        list($name, $value, $isImportant) = $newProperty->reduce();

        if ($isImportant)
        {
            $value .= '!important';
        }

        $this->parse($name, $value);
    }

    /**
     * @throws \Exception
     * @return array
     */
    public function reduce()
    {
        if (count($this->inputs) == 0)
        {
            throw new \Exception('There are no inputs.');
        }

        return $this->inputs[0];
    }
}
