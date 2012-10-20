<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CssReducer\Css;

use CssReducer\Css\Property\Property;


class Selector
{
    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var array
     */
    protected $properties = array();


    /**
     * @param $name
     * @param array $properties
     */
    public function __construct($name, array $properties = null)
    {
        $this->name = str_replace(', ', ',', $name);

        if ($properties != null) {
            $this->setProperties($properties);
        }
    }

    /**
     *
     * @param array $properties
     * @throws \InvalidArgumentException
     */
    public function setProperties(array $properties)
    {
        foreach ($properties as $property) {
            if ($property instanceof Property === false) {
                throw new \InvalidArgumentException('Properties must extend CssReducer\Css\Property\Property');
            }
        }

        $this->properties = $properties;
    }

    /**
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     *
     * @param \CssReducer\Css\Property\Property $newProperty
     */
    public function addProperty(Property $newProperty)
    {
        $merged = false;

        foreach ($this->properties as $property) {
            if (get_class($newProperty) === get_class($property) &&
                get_class($property) != '\CssReducer\Css\Property\Property'
            ) {
                /* @var $property Property */
                $property->merge($newProperty);
                $merged = true;
            }
        }

        if ($merged === false) {
            $this->properties[] = $newProperty;
        }
    }
}
