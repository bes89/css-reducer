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

use CssReducer\Log\LoggerInterface;


/**
 *
 */
class Minifier
{
    /**
     * @var Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     *
     * @var array
     */
    protected $options = array(
        'remove_comments' => false,
        'remove_whitespaces' => false,
        'remove_tabs' => false,
        'remove_newlines' => false,
    );


    /**
     *
     * @param mixed $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $name => $value) {
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
        if (!array_key_exists($name, $this->options)) {
            throw new \InvalidArgumentException(sprintf('Option "%s" does not exist.' .
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
     *
     * @param string $fileUrlOrCss
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function load($fileUrlOrCss)
    {
        $content = "";

        if (is_array($fileUrlOrCss)) {
            foreach ($fileUrlOrCss as $part) {
                $content .= $this->load($part);
            }
        } else {
            if (strpos($fileUrlOrCss, '{') !== false) {
                $content = $fileUrlOrCss;
            } elseif (strpos($fileUrlOrCss, 'http') !== false || file_exists($fileUrlOrCss)) {
                $content = file_get_contents($fileUrlOrCss);
            } else {
                throw new \InvalidArgumentException("File '$fileUrlOrCss' not found.");
            }
        }

        return $content;
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
     *
     * @param $fileUrlOrCss
     * @param array $options
     * @return string
     */
    public function minify($fileUrlOrCss, array $options = array())
    {
        $content = $this->load($fileUrlOrCss);

        $this->setOptions($options);

        if ($this->getOption('remove_comments')) {
            $content = $this->removeComments($content);
        }

        if ($this->getOption('remove_whitespaces')) {
            $content = $this->removeWhitespaces($content);
        }

        if ($this->getOption('remove_tabs')) {
            $content = $this->removeTabs($content);
        }

        if ($this->getOption('remove_newlines')) {
            $content = $this->removeNewlines($content);
        }

        return $content;
    }
}
