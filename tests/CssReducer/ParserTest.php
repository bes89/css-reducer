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

use CssReducer\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleCss()
    {

        $css = "body {
                background: #ffffff;
              color: red
            }
            ";


        $p = new Parser();

        $parsedCss = $p->parse($css);

        $this->assertEquals(1, count($parsedCss));
        $this->assertEquals(true, array_key_exists('body', $parsedCss[0]));
        $this->assertEquals(true, array_key_exists('background', $parsedCss[0]['body']));
        $this->assertEquals(true, array_key_exists('color', $parsedCss[0]['body']));
        $this->assertEquals('#ffffff', $parsedCss[0]['body']['background']);
    }

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

        $p = new Parser();

        $this->assertFalse(strpos($p->minify($css, array(
            'remove_tabs' => true
        )), "\t"));
    }

    public function testMinifingCssRemoveNewlines()
    {
        $css = $this->getUnminifiedCss();

        $p = new Parser();

        $this->assertFalse(strpos($p->minify($css, array(
            'remove_newlines' => true
        )), "\n"));
    }

    public function testMinifingCssRemoveWhitespaces()
    {
        $css = $this->getUnminifiedCss();

        $p = new Parser();
        $p->setOption('remove_whitespaces', true);
        $minifiedCss = $p->minify($css, array(
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

        $p = new Parser();

        $this->assertFalse(strpos($p->minify($css, array(
            'remove_comments' => true
        )), "/*"));

        $this->assertFalse(strpos($p->minify($css, array(
            'remove_comments' => true
        )), "*/"));
    }
}
