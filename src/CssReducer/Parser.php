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
        if (!array_key_exists($name, $this->options))
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
    protected function removeComments($css)
    {
        return preg_replace("~/\*.*?\*/~s", "", $css);
    }

    /**
     * Removes excess, leading and trailing whitespaces
     *
     * @param string $css
     * @return string
     */
    protected function removeWhitespaces($css)
    {
        // remove excess whitespace
        $css = preg_replace("~\s\s+~", "", $css);

        // remove leading and trailing whitespaces
        $searchAndReplace = array(
            ": " => ":",
            "; " => ";",
            ", " => ",",
            "{ " => "{",
            "} " => "}",
            "*/ " => "*/",
            " :" => ":",
            " ;" => ";",
            " ," => ",",
            " {" => "{",
            " }" => "}",
            " /*" => "/*",
        );

        $css = str_replace(array_keys($searchAndReplace), $searchAndReplace, $css);

        return trim($css);
    }

    /**
     *
     * @param string $css
     * @return string
     */
    protected function removeTabs($css)
    {
        return str_replace("\t", '', $css);
    }

    /**
     *
     * @param string $css
     * @return string
     */
    protected function removeNewlines($css)
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

    /**
     *
     * @param string $fileUrlOrCss
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function load($fileUrlOrCss)
    {
        $content = "";

        if (is_array($fileUrlOrCss))
        {
            foreach ($fileUrlOrCss as $part)
            {
                $content .= $this->load($part);
            }
        }
        else
        {
            if (strpos($fileUrlOrCss, '{') !== false)
            {
                $content = $fileUrlOrCss;
            }
            elseif (strpos($fileUrlOrCss, 'http') !== false || file_exists($fileUrlOrCss))
            {
                $content = file_get_contents($fileUrlOrCss);
            }
            else
            {
                throw new \InvalidArgumentException("File '$fileUrlOrCss' not found.");
            }
        }

        return $content;
    }

    /**
     *
     * @param array $cssDefinitions
     * @return array
     */
    protected function parseProperties(array $cssDefinitions = array())
    {
        foreach ($cssDefinitions as $index => $cssBlock)
        {
            $selector = key($cssBlock);
            $properties = reset($cssBlock);

            $matches = array();

            if (preg_match_all($this->propertiesPattern, $properties, $matches))
            {
                $properties = array_combine($matches[1], $matches[2]);
            }
            else
            {
                $properties = array();
            }

            foreach ($properties as $name => $value)
            {
                $properties[trim($name)] = trim($value);
            }

            $cssDefinitions[$index][$selector] = $properties;
        }

        return $cssDefinitions;
    }

    /**
     *
     * @param $fileUrlOrCss
     * @throws \InvalidArgumentException
     * @return string
     */
    public function parse($fileUrlOrCss)
    {
        $content = $this->load($fileUrlOrCss);

        $matches = array();

        if (!preg_match_all($this->pattern, $content, $matches))
        {
            throw new \InvalidArgumentException("Nothing parsed.");
        }

        $cssDefinitions = array();

        for ($i = 0; $i < count($matches[0]); $i++)
        {
            $cssDefinitions[] = array(
                trim($matches[1][$i]) => trim($matches[2][$i])
            );
        }

        if ($this->getOption('split_selectors'))
        {
            $cssDefinitions = $this->splitSelectors($cssDefinitions);
        }

        $cssDefinitions = $this->parseProperties($cssDefinitions);

        return $cssDefinitions;
    }

    /**
     *
     * @param $fileUrlOrCss
     * @param array $options
     * @return string
     */
    public function minify($fileUrlOrCss, array $options = array())
    {
        $content = $this->load($fileUrlOrCss);

        $this->setOptions($options);

        if ($this->getOption('remove_comments'))
        {
            $content = $this->removeComments($content);
        }

        if ($this->getOption('remove_whitespaces'))
        {
            $content = $this->removeWhitespaces($content);
        }

        if ($this->getOption('remove_tabs'))
        {
            $content = $this->removeTabs($content);
        }

        if ($this->getOption('remove_newlines'))
        {
            $content = $this->removeNewlines($content);
        }

        return $content;
    }
}
