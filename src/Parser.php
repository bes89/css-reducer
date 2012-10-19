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

/**
 *
 */
class Parser
{
    /**
     *
     * @var string
     */
    protected $pattern = "~([^{]*)\{([^}]*+)\}~ms";

    /**
     *
     * @var string
     */
    protected $propertiesPattern = "~([^ :;]*):([^;]*)[;]?~ms";

    /**
     *
     * @var array
     */
    protected $options = array(
        'remove_comments' => false,
        'remove_whitespaces' => false,
        'remove_tabs' => false,
        'remove_newlines' => false,
        'split_selectors' => false,
    );


    /**
     *
     * @param mixed $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $name => $value)
        {
            $this->setOption($name, $value);
        }
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     * @throws \InvalidArgumentException
     */
    public function setOption($name, $value)
    {
        if (array_key_exists($name, $this->options))
        {
            throw new \InvalidArgumentException(sprintf('Option "%s" does not exist.'.
                'The following options are allowed: %s', $name, join(', ', array_keys($this->options))));
        }

        $this->options[$name] = $value;
    }

    /**
     *
     * @param $name
     * @return mixed
     */
    public function getOption($name)
    {
        return array_key_exists($name, $this->options) ? $this->options[$name] : $name;
    }

    /**
     * Strips C style comments
     *
     * @param string $css
     * @return string
     */
    public function removeComments($css)
    {
        return preg_replace("~/\*.*?\*/~s", "", $css);
    }

    /**
     *
     * @param string $css
     * @return string
     */
    public function removeWhitespaces($css)
    {
        $searchAndReplace = array(
            ": " => ":",
            "; " => ";",
            ", " => ",",
            " :" => ":",
            " ;" => ";",
            " ," => ",",
            " {" => "{",
            " }" => "}",
            "{ " => "{",
            "} " => "}",
            " /*" => "/*",
            "*/ " => "*/",
        );

        $css = str_replace(array_keys($searchAndReplace), $searchAndReplace, $css);

        // remove excess whitespace
        $css = preg_replace("~\s\s+~", "", $css);

        return trim($css);
    }

    /**
     *
     * @param string $css
     * @return string
     */
    public function removeTabs($css)
    {
        return str_replace("\t", '', $css);
    }

    /**
     *
     * @param string $css
     * @return string
     */
    public function removeNewlines($css)
    {
        return str_replace(array("\r", "\r\n", "\n"), "", $css);
    }

    /**
     * Splits css selectors, e.g.  a, b {} => a {} b {}
     *
     * @param array $cssDefinitions
     * @return array
     */
    protected function splitSelectors(array $cssDefinitions)
    {
        $cssDefinitionSplitted = array();

        $n = 0;

        foreach ($cssDefinitions as $cssBlock)
        {
            $selectors = key($cssBlock);
            $properties = reset($cssBlock);

            if (strpos($selectors, ',') !== false)
            {
                foreach (explode(',', $selectors) as $selector)
                {
                    $cssDefinitionSplitted[$n][trim($selector)] = $properties;
                    $n++;
                }
            }
            else
            {
                $cssDefinitionSplitted[$n][$selectors] = $properties;
            }

            $n++;
        }

        return $cssDefinitionSplitted;
    }
}
