<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CssReducer\Test;

use CssReducer\Minifier;


class MinifierTest extends \PHPUnit_Framework_TestCase
{
    protected function getUnminifiedCss()
    {
        return "body   ,        p { \n
                background: #ffffff  ;
                color: red  ; \t
                \tfont: Arial\n
            } ";
    }

    public function testMinifingCssRemoveTabs()
    {
        $css = $this->getUnminifiedCss();

        $minifier = new Minifier();

        $this->assertFalse(strpos($minifier->minify($css, array(
            'remove_tabs' => true
        )), "\t"));
    }

    public function testMinifingCssRemoveNewlines()
    {
        $css = $this->getUnminifiedCss();

        $minifier = new Minifier();

        $this->assertFalse(strpos($minifier->minify($css, array(
            'remove_newlines' => true
        )), "\n"));
    }

    public function testMinifingCssRemoveWhitespaces()
    {
        $css = $this->getUnminifiedCss();

        $minifier = new Minifier();
        $minifier->setOption('remove_whitespaces', true);

        $minifiedCss = $minifier->minify($css, array(
            'remove_tabs' => true
        ));

        $chars = array(' ', ',', ';', ':', '{', '}');

        foreach ($chars as $char)
        {
            $charWithLeadingWhitespace = ' '.$char;
            $charWithTrailingWhitespace = $char.' ';

            $this->assertFalse(strpos($minifiedCss, $charWithLeadingWhitespace));
            $this->assertFalse(strpos($minifiedCss, $charWithTrailingWhitespace));
        }
    }

    public function testMinifingCssRemoveComments()
    {
        $css = $this->getUnminifiedCss();

        $minifier = new Minifier();

        $this->assertFalse(strpos($minifier->minify($css, array(
            'remove_comments' => true
        )), "/*"));

        $this->assertFalse(strpos($minifier->minify($css, array(
            'remove_comments' => true
        )), "*/"));
    }
}
