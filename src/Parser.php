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
        'remove_comments',
        'remove_whitespaces',
        'remove_tabs',
        'remove_newlines',
        'split_selectors',
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
     * @param $name
     * @return mixed
     */
    public function getOption($name)
    {
        return array_key_exists($name, $this->options) ? $this->options[$name] : $name;
    }
}
