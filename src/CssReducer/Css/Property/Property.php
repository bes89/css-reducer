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
            'isImportant' => $isImportant,
        );
    }

    /**
     * @param Property $newProperty
     */
    public function merge(Property $newProperty)
    {
        $name = null;
        $value = null;
        $isImportant = null;

        extract($newProperty->reduce());

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

        $foundAnImportantItem = false;
        $indexOfItemToReturn = 0;

        foreach ($this->inputs as $index => $input)
        {
            if ($foundAnImportantItem && !$input['isImportant'])
            {
                continue;
            }

            if (!$foundAnImportantItem && $input['isImportant'])
            {
                $foundAnImportantItem = true;
            }

            $indexOfItemToReturn = $index;
        }

        return $this->inputs[$indexOfItemToReturn];
    }
}
