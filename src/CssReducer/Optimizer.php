<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CssReducer;
use CssReducer\Css\PropertyFactory;
use CssReducer\Css\Selector;

/**
 *
 */
class Optimizer
{
    /**
     *
     * @param array $content
     * @return array
     */
    public function build(array $content)
    {
        $selectorCollection = array();

        foreach ($content as $cssBlock) {
            $selectorValue = key($cssBlock);
            $propertiesValue = reset($cssBlock);

            $newSelector = new Selector($selectorValue);

            foreach ($propertiesValue as $name => $value) {
                $property = PropertyFactory::getInstance()->resolve($name, $value);
                $newSelector->addProperty($property);
            }

            $selectorCollection[] = $newSelector;
        }

        return $selectorCollection;
    }
}
