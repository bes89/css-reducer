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

use CssReducer\Log\LoggerInterface;


class PropertyFactory
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var PropertyFactory
     */
    protected static $instance = null;

    /**
     * @var array
     */
    protected static $propertyMapping = array(
        /*
        'Background' => array(
            'background',
            'background-*',
        ),
        'BorderCollapse' => array(
            'border-collapse'
        ),
        'BorderSpacing' => array(
            'border-spacing'
        ),
        'BorderRadius' => array(
            'border-radius',
            'border-*-radius'
        ),
        'Border' => array(
            'border',
            'border-*'
        ),
        'Bottom' => array(
            'bottom'
        ),
        'BoxShadow' => array(
            'box-shadow'
        ),
        */
        'Color' => array(
            'color'
        ),
        /*
        'Clear' => array(
            'clear'
        ),
        'Clip' => array(
            'clip'
        ),
        'Cursor' => array(
            'cursor'
        ),
        'Content' => array(
            'content'
        ),
        'Direction' => array(
            'direction'
        ),
        'Display' => array(
            'display'
        ),
        'Font' => array(
            'font',
            'font-*'
        ),
        'Float' => array(
            'float'
        ),
        'Filter' => array(
            'filter'
        ),css
        'Height' => array(
            'height'
        ),
        'Left' => array(
            'left'
        ),
        'LetterSpacing' => array(
            'letter-spacing'
        ),
        'LineHeight' => array(
            'line-height'
        ),
        'ListStyle' => array(
            'list-style',
            'list-style-*'
        ),
        'Margin' => array(
            'margin',
            'margin-*'
        ),
        'MaxHeight' => array(
            'max-height'
        ),
        'MaxWidth' => array(
            'max-width'
        ),
        'MinHeight' => array(
            'min-height'
        ),
        'MinWidth' => array(
            'min-width'
        ),
        'Outline' => array(
            'outline',
            'outline-*'
        ),
        'Overflow' => array(
            'overflow',
            'overflow-*'
        ),
        'Opacity' => array(
            'opacity'
        ),
        'Position' => array(
            'position'
        ),
        'Padding' => array(
            'padding',
            'padding-*',
        ),
        'Quotes' => array(
            'quotes'
        ),
        'Resize' => array(
            'resize'
        ),
        'Right' => array(
            'right'
        ),
        'TextAlign' => array(
            'text-align'
        ),
        'TextDecoration' => array(
            'text-decoration'
        ),
        'TextIndent' => array(
            'text-indent'
        ),
        'TextTransform' => array(
            'text-transform'
        ),
        'TextOverflow' => array(
            'text-overflow'
        ),
        'TextShadow' => array(
            'text-shadow'
        ),
        'Top' => array(
            'top'
        ),
        'UnicodeBidi' => array(
            'unicode-bidi'
        ),
        'VerticalAlign' => array(
            'vertical-align'
        ),
        'Visibility' => array(
            'visibility'
        ),
        'Width' => array(
            'width'
        ),
        'WhiteSpace' => array(
            'white-space'
        ),
        'WordWrap' => array(
            'word-wrap'
        ),
        'WordSpacing' => array(
            'word-spacing'
        ),
        'ZIndex' => array(
            'z-index'
        ),
        'Zoom' => array(
            'zoom'
        ),
        */
        'Property' => array(
            '*'
        )
    );

    protected function __construct()
    {
    }

    /**
     * @param \CssReducer\Log\LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @return PropertyFactory|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new PropertyFactory();
        }

        return self::$instance;
    }


    /**
     *
     * @param string $name
     * @param string $value
     * @throws \InvalidArgumentException
     * @return Property\Property
     */
    public function resolve($name, $value)
    {
        $name = trim($name);
        $value = trim($value);

        foreach (self::$propertyMapping as $class => $associatedProperties) {
            $matchedWildcard = false;

            foreach ($associatedProperties as $associatedProperty) {
                if (strpos($associatedProperty, '*') !== false) {
                    $pattern = '~^' . str_replace('*', '.*?', $associatedProperty) . '$~is';

                    if (preg_match($pattern, $name)) {
                        $matchedWildcard = true;
                        break;
                    }
                }
            }

            if ($matchedWildcard === true || in_array($name, $associatedProperties)) {
                $className = '\CssReducer\Css\Property\\'.$class;

                return new $className($name, $value, $this->logger);
            }
        }

        throw new \InvalidArgumentException('Unable to handle CSS property: '.$name);
    }
}
