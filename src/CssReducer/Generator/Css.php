<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CssReducer\Generator;

use CssReducer\Log\LoggerInterface;


class Css
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function generate(array $optimizedCss)
    {
        $css = "";

        foreach ($optimizedCss as $selector)
        {
            $css .= sprintf("%s {\n", $selector->getName());

            foreach ($selector->getProperties() as $index => $property)
            {
                $propertyData = $property->reduce();

                $css .= sprintf("  %s: %s%s%s\n",
                    $propertyData['name'],
                    $propertyData['value'],
                    $propertyData['isImportant'] ? ' !important' : '',
                    count($selector->getProperties())-1 > $index ? ';' : ''
                );
            }

            $css .= "}\n\n";
        }

        return $css;
    }
}
