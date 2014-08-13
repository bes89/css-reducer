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

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * This method expects results from Optimizer
     *
     * @param array $optimizedCss
     * @return string
     */
    public function generate(array $optimizedCss)
    {
        $css = "";

        foreach ($optimizedCss as $selector) {
            $n = count($selector->getProperties());
            $i = 0;

            $css .= sprintf("%s {\n", $selector->getName());

            foreach ($selector->getProperties() as $index => $property) {
                $propertyData = $property->reduce();

                if (count($propertyData) > 1)
                {
                    $n += count($propertyData) - 1;
                }

                foreach ($propertyData as $data) {
                    $i++;

                    $css .= sprintf("  %s: %s%s%s\n",
                        $data['name'],
                        $data['value'],
                        $data['important'] ? ' !important' : '',
                        $n > $i ? ';' : ''
                    );
                }
            }

            $css .= "}\n\n";
        }

        return $css;
    }

    /**
     *
     * @param string $css
     * @return string
     */
    public function format($css)
    {
        $parser = new \CssReducer\Parser();
        $parsedCss = $parser->parse($css);

        $formattedCss = "";

        foreach ($parsedCss as $item) {

            foreach ($item as $selector => $properties)
            {
                $formattedCss .= sprintf("%s {\n", $selector);
                $n = 0;

                foreach ($properties as $propertyName => $propertyValue) {
                    $formattedCss .= sprintf("  %s: %s%s\n",
                        $propertyName,
                        $propertyValue,
                        count($properties) - 1 > $n ? ';' : ''
                    );
                    $n++;
                }

                $formattedCss .= "}\n\n";
            }
        }

        return $formattedCss;
    }
}
